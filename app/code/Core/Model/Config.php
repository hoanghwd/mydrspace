<?php

/**
 * Created by PhpStorm.
 * User: hdo
 * Date: 3/27/2019
 * Time: 3:17 PM
 */
class Core_Model_Config extends Core_Config_Base
{
    /**
     * Empty configuration object for loading and merging configuration parts
     *
     * @var Core_Config_Base $_prototype
     */
    protected $_prototype;

    /**
     * Configuration options
     *
     * @var Core_Config_Options $_options
     */
    protected $_options;

    /**
     * Flag which identify what local configuration is loaded
     *
     * @var bool
     */
    protected $_isLocalConfigLoaded = false;

    /**
     * Resource model
     * Used for operations with DB
     *
     * @var Virtual_Db_Adapter_Pdo_Mysql
     */
    protected $_resourceModel;


    /**
     * Core_Model_Config constructor.
     * @param null $sourceData
     */
    public function __construct($sourceData = NULL)
    {
        $this->_options = new Core_Config_Options($sourceData);
        $this->_prototype = new Core_Config_Base();
    }

    /**
     * @return Core_Config_Options
     */
    public function getOptions()
    {
        return $this->_options;
    }

    /**
     * @param $options
     * @return Core_Model_Config
     */
    public function setOptions($options)
    {
        if (is_array($options)) {
            $this->getOptions()->addData($options);
        }

        return $this;
    }

    /**
     * @param $modelClass
     * @return string
     */
    public function getModelClassName($modelClass)
    {
        $modelClass = trim($modelClass);
        if (strpos($modelClass, '/')===false) {
            return $modelClass;
        }

        return $this->getGroupedClassName('model', $modelClass);
    }

    /**
     * Retrieve class name by class group
     *
     * @param   string $groupType currently supported model, block, helper
     * @param   string $classId slash separated class identifier, ex. group/class
     * @param   string $groupRootNode optional config path for group config
     * @return  string
     */
    public function getGroupedClassName($groupType, $classId, $groupRootNode=null)
    {
        if (empty($groupRootNode)) {
            $groupRootNode = 'global/'.$groupType.'s';
        }

        $classArr = explode('/', trim($classId));
        $group = $classArr[0];
        $class = !empty($classArr[1]) ? $classArr[1] : null;
        $config = $this->_xml->global->{$groupType.'s'}->{$group};

        $className = null;
        if (!empty($config)) {
            $className = (string)$config->class;
        }
        if (empty($className)) {
            $className = $group.'_'.$groupType;
        }
        if (!empty($class)) {
            $className .= '_'.$class;
        }

        $className = uc_words($className);

        return $className;
    }

    /**
     * Initialization of core configuration
     *
     * @param array $options
     * @return Core_Model_Config
     */
    public function init($options=array())
    {
        return $this;
    }

    /**
     * Load base system configuration (config.xml and local.xml files)
     *
     * @return Core_Model_Config
     */
    public function loadBase()
    {
        $etcDir = $this->getOptions()->getEtcDir();
        $files = glob($etcDir.DS.'*.xml');
        $this->loadFile(current($files));

        while ($file = next($files)) {
            $merge = clone $this->_prototype;
            $merge->loadFile($file);
            $this->extend($merge);
        }

        if (in_array($etcDir.DS.'local.xml', $files)) {
            $this->_isLocalConfigLoaded = true;
        }

        return $this;
    }

    /**
     * Load modules configuration
     *
     * @return Core_Model_Config
     */
    public function loadModules()
    {
        Varien_Profiler::start('config/load-modules');
        $this->_loadDeclaredModules();

        $resourceConfig = sprintf('config.%s.xml', $this->_getResourceConnectionModel('core'));
        $this->loadModulesConfiguration(array('config.xml',$resourceConfig), $this);

        /**
         * Prevent local.xml directives overwriting
         */
        $mergeConfig = clone $this->_prototype;
        $this->_isLocalConfigLoaded = $mergeConfig->loadFile($this->getOptions()->getEtcDir().DS.'local.xml');
        if ($this->_isLocalConfigLoaded) {
            $this->extend($mergeConfig);
        }

        $this->applyExtends();

        //Virtual::dump($this->_xml->frontend->asArray());

        Varien_Profiler::stop('config/load-modules');

        return $this;
    }

    /**
     * Iterate all active modules "etc" folders and combine data from
     * specidied xml file name to one object
     *
     * @param   string $fileName
     * @param   null|Core_Model_Config_Base $mergeToObject
     * @return  Core_Model_Config_Base
     */
    public function loadModulesConfiguration($fileName, $mergeToObject = null, $mergeModel=null)
    {
        if ($mergeToObject === null) {
            $mergeToObject = clone $this->_prototype;
            $mergeToObject->loadString('<config/>');
        }
        if ($mergeModel === null) {
            $mergeModel = clone $this->_prototype;
        }
        $modules = $this->getNode('modules')->children();
        foreach ($modules as $modName=>$module) {
            $moduleArr = $module->asArray();
            if( isset($moduleArr['codePool']) &&  isset($moduleArr['active']) ) {
                $active = (strtolower($moduleArr['active']) === 'true');
                if( $active ) {
                    if (!is_array($fileName)) {
                        $fileName = array($fileName);
                    }
                    foreach ($fileName as $configFile) {
                        $configFile = $this->getModuleDir('etc', $modName) . DS . $configFile;
                        if ($mergeModel->loadFile($configFile)) {
                            $mergeToObject->extend($mergeModel, true);
                        }
                    }
                }//if
            }
        }//foreach

        //Virtual::dump($mergeToObject);

        return $mergeToObject;
    }

    /**
     * Retrieve resource connection model name
     *
     * @param string $moduleName
     * @return string
     */
    protected function _getResourceConnectionModel($moduleName = null)
    {
       return (string)$this->_xml->global->resources->connection->model;
    }

    /**
     * Load declared modules configuration
     *
     * @param   null $mergeConfig depricated
     * @return  Core_Model_Config
     */
    protected function _loadDeclaredModules($mergeConfig = null)
    {
        $moduleFiles = $this->_getDeclaredModuleFiles();

        if (!$moduleFiles) {
            return ;
        }

        $unsortedConfig = new Core_Config_Base();
        $unsortedConfig->loadString('<config/>');
        $configBase = new Core_Config_Base();

        // load modules declarations
        foreach ($moduleFiles as $file) {
            $configBase->loadFile($file);
            $unsortedConfig->extend($configBase);
        }

        $this->extend($unsortedConfig);

        return $this;
    }

    /**
     * Retrieve Declared Module file list
     *
     * @return array
     */
    protected function _getDeclaredModuleFiles()
    {
        $etcDir = $this->getOptions()->getEtcDir();
        $moduleFiles = glob($etcDir . DS . 'modules' . DS . '*.xml');
        if (!$moduleFiles) {
            return false;
        }

        $collectModuleFiles = array();
        foreach ($moduleFiles as $files) {
            array_push($collectModuleFiles, $files);
        }

        return $collectModuleFiles;
    }

    /**
     * Check if local configuration (DB connection, etc) is loaded
     *
     * @return bool
     */
    public function isLocalConfigLoaded()
    {
        return $this->_isLocalConfigLoaded;
    }

    /**
     * Load config data from DB
     * @return Core_Model_Config
     * @throws Exception
     */
    public function loadDb()
    {
        if ( $this->_isLocalConfigLoaded ) {
            Varien_Profiler::start('config/load-db');
            /**
             * Load all data in core_config_data table into master xml node
             */
            $dbConf = $this->getResourceModel();
            $dbConf->loadToXml($this);
            Varien_Profiler::stop('config/load-db');
        }

        return $this;
    }

    /**
     * Returns node found by the $path and scope info
     *
     * @param   string $path
     * @param   string $scope
     * @param   string|int $scopeCode
     * @return Core_Config_Element
     */
    public function getNode($path=null, $scope='', $scopeCode=null)
    {
        return  parent::getNode($path);
    }

    /**
     * @return bool|Virtual_Db_Adapter_Pdo_Mysql
     * @throws Exception
     */
    public function getResourceModel()
    {
        if (is_null($this->_resourceModel)) {
            /**
             * This will convert to  "core_resource/config"
             *
             */
            $this->_resourceModel = Virtual::getResourceModel('core/config');
        }

        return $this->_resourceModel;
    }

    /**
     * @param string $modelClass
     * @param array $constructArguments
     * @return bool
     * @throws Exception
     */
    public function getResourceModelInstance($modelClass='', $constructArguments=array())
    {
        $factoryName = $this->_getResourceModelFactoryClassName($modelClass);

        if (!$factoryName) {
            return false;
        }

        return $this->getModelInstance($factoryName, $constructArguments);
    }

    /**
     * @param string $modelClass
     * @param array $constructArguments
     * @return bool
     * @throws Exception
     */
    public function getModelInstance($modelClass='', $constructArguments=array())
    {
        $className = $this->getModelClassName($modelClass);
        //echo $className.'<br/>';
        if (class_exists($className)) {
            Varien_Profiler::start('CORE::create_object_of::'.$className);
            $obj = new $className($constructArguments);
            Varien_Profiler::stop('CORE::create_object_of::'.$className);
            return $obj;
        }
        else {
            Virtual::throwException('Could not find class name '.$className.'<br/>');
            return false;
        }
    }

    /**
     * Get factory class name for a resource
     *
     * @param string $modelClass
     * @return string|false
     */
    protected function _getResourceModelFactoryClassName($modelClass)
    {
        $classArray = explode('/', $modelClass);


        if (count($classArray) != 2) {
            return false;
        }

        list($module, $model) = $classArray;

        //echo $module.' -> '.$model.'<br/>';
        //Virtual::dump($this->_xml->global->models);

        if (!isset($this->_xml->global->models->{$module})) {
            return false;
        }

        $moduleNode = $this->_xml->global->models->{$module};

        if (!empty($moduleNode->resourceModel)) {
            $resourceModel = (string)$moduleNode->resourceModel;
        }
        else {
            return false;
        }

        return $resourceModel . '/' . $model;
    }

    /**
     * Get module directory by directory type
     *
     * @param   string $type
     * @param   string $moduleName
     * @return  string
     */
    public function getModuleDir($type, $moduleName)
    {
        $codePool = (string)$this->getModuleConfig($moduleName)->codePool;
        $codePoolParts = explode("_",$codePool);
        if( is_array($codePoolParts) && sizeof($codePoolParts) > 1 ) {
            $codePool = $codePoolParts[0].DS.$codePoolParts[1];
        }

        $dir = $this->getOptions()->getCodeDir().DS.$codePool.DS.uc_words($moduleName, DS);

        switch ($type) {
            case 'etc':
                $dir .= DS.'etc';
                break;

            case 'controllers':
                $dir .= DS.'controllers';
                break;
        }//switch

        $dir = str_replace('/', DS, $dir);

        return $dir;
    }

    /**
     * Get module config node
     * @param string $moduleName
     * @return Core_Config_Element|SimpleXMLElement
     */
    public function getModuleConfig($moduleName='')
    {
        $modules = $this->getNode('modules');

        if (''===$moduleName) {
            return $modules;
        }
        else {
            return $modules->$moduleName;
        }
    }

    /**
     * Get standard path variables.
     *
     * To be used in blocks, templates, etc.
     * @param null $args
     * @return array
     * @throws Zend_Controller_Request_Exception
     */
    public function getPathVars($args=null)
    {
        $path = array();

        $path['baseUrl'] = Virtual::getBaseUrl();
        $path['baseSecureUrl'] = Virtual::getBaseUrl('link', true);

        return $path;
    }

    /**
     * Retrieve block class name
     *
     * @param   string $blockType
     * @return  string
     */
    public function getBlockClassName($blockType)
    {
        if (strpos($blockType, '/')===false) {
            return $blockType;
        }

        return $this->getGroupedClassName('block', $blockType);
    }

    /**
     * Retrieve helper class name
     *
     * @param $helperName
     * @return string
     */
    public function getHelperClassName($helperName)
    {
        if (strpos($helperName, '/') === false) {
            $helperName .= '/data';
        }

        return $this->getGroupedClassName('helper', $helperName);
    }

}//End of class