<?php
/**
 * Created by PhpStorm.
 * User: hdo
 * Date: 3/27/2019
 * Time: 11:50 AM
 */

define('DS', DIRECTORY_SEPARATOR);
define('PS', PATH_SEPARATOR);
define('BP', dirname(dirname(__FILE__)));

Virtual::register('original_include_path', get_include_path());

$paths = array();
$paths[] = BP . DS . 'app' . DS . 'code';
$paths[] = BP . DS . 'app' . DS . 'code'. DS. 'Core';
$paths[] = BP . DS . 'app' . DS . 'code'. DS. 'Local';
$paths[] = BP . DS . 'errors';
$paths[] = BP . DS . 'lib';

$appPath = implode(PS, $paths);
set_include_path($appPath . PS . Virtual::registry('original_include_path'));

include_once "config.php";
include_once "functions.php";
include_once "Varien/Autoload.php";

Varien_Autoload::register();

include_once "phpseclib/bootstrap.php";
include_once "mcryptcompat/mcrypt.php";
require_once "securimage/securimage.php";
require_once "mail/PHPMailerAutoload.php";

final class Virtual
{
    /**
     * Registry collection
     *
     * @var array
     */
    static private $_registry = array();

    /**
     * Application root absolute path
     *
     * @var string
     */
    static private $_appRoot;

    /**
     * Core Model Config
     *
     * @var Core_Model_Config $_config
     */
    static private $_config;

    /**
     * Application model
     *
     * @var Core_Model_App $_app
     */
    static private $_app;

    /**
     * Is allow throw Exception about headers already sent
     *
     * @var bool
     */
    public static $headersSentThrowsException   = true;

    /**
     * @param string $code
     * @param string $type
     * @param array $options
     * @throws Exception
     */
    public static function run($code = '', $type = 'default', $options = array())
    {
        try {
            self::setRoot();
            self::$_app = new Core_Model_App();
            self::_setConfigModel($options);
            self::$_app->run(
                array(
                    'scope_code' => $code,
                    'scope_type' => $type,
                    'options'    => $options,
            ));
        }
        catch (Core_Model_Exception $e) {
            require_once(self::getBaseDir() . DS . 'errors' . DS . '404.php');
            die();
        }
        catch (Exception $e) {
          self::printException($e, $e->getMessage());
        }

    }

    /**
     * @param string $code
     * @param string $type
     * @param array $options
     * @param array $modules
     * @throws Exception
     */
    public static function init($code = '', $type = 'default', $options = array(), $modules = array())
    {
        try {
            self::setRoot();
            self::$_app = new Core_Model_App();
            self::_setConfigModel($options);

            if (!empty($modules)) {
                self::$_app->initSpecified($code, $type, $options, $modules);
            }
            else {
                self::$_app->init($code, $type, $options);
            }
        }

        catch (Exception $e) {
            self::printException($e, $e->getMessage());
        }
    }

    /**
     * @param string $code
     * @param string $type
     * @param array $options
     * @return Core_Model_App
     * @throws Zend_Controller_Request_Exception
     * @throws Zend_Controller_Response_Exception
     */
    public static function app($code = '', $type = 'default', $options = array())
    {
        if (null === self::$_app) {
            self::$_app = new Core_Model_App();
            self::setRoot();
            self::_setConfigModel($options);
            self::$_app->init($code, $type, $options);
        }

        return self::$_app;
    }

    /**
     * Set application root absolute path
     *
     * @param string $appRoot
     */
    public static function setRoot($appRoot = '')
    {
        if (self::$_appRoot) {
            return ;
        }

        if ('' === $appRoot) {
            $appRoot = dirname(__FILE__);
        }

        if (is_dir($appRoot) and is_readable($appRoot)) {
            self::$_appRoot = $appRoot;
        }
        else {
            echo ($appRoot . ' is not a directory or not readable by this user');
        }
    }

    /**
     * @return string
     */
    public static function getRoot()
    {
        return self::$_appRoot;
    }

    /**
     * Set application Config model
     *
     * @param array $options
     */
    protected static function _setConfigModel($options = array())
    {
        self::$_config = new Core_Model_Config($options);
    }

    /**
     * Retrieve configuration object
     *
     * @return Core_Model_Config
     */
    public static function getConfig()
    {
        return self::$_config;
    }

    /**
     * @param string $type
     * @return mixed
     * @throws Exception
     */
    public static function getBaseDir($type = 'base')
    {
        return self::getConfig()->getOptions()->getDir($type);
    }

    /**
     * Retrieve a value from registry by a key
     *
     * @param string $key
     * @return mixed
     */
    public static function registry($key)
    {
        if (isset(self::$_registry[$key])) {
            return self::$_registry[$key];
        }
        return null;
    }

    /**
     * @param $key
     * @param $value
     * @param bool $graceful
     */
    public static function register($key, $value, $graceful = false)
    {
        if (isset(self::$_registry[$key])) {
            if ($graceful) {
                return;
            }
        }
        self::$_registry[$key] = $value;
    }

    /**
     * Dump variable
     * @param $var
     */
    public static function dump($var)
    {
        print '<pre>';
        var_dump($var);
        print '</pre>';
    }

    /**
     * @param $message
     * @param string $errorMsg
     * @throws Exception
     */
    public static function throwException($message, $errorMsg = '')
    {
        print '<pre>';
        echo $errorMsg.'<br/>';
        throw new Exception($message);
        print '</pre>';
    }

    /**
     * @param Exception $e
     * @param $message
     * @throws Exception
     */
    public static function printException(Exception $e, $message)
    {
        self::throwException($message);

        print '<pre>';
        if (!empty($extra)) {
            print $extra . "\n\n";
        }

        print $e->getMessage() . "\n\n";
        print $e->getTraceAsString();
        print '</pre>';
    }

    /**
     * Get config resource model
     *
     * @param $modelClass
     * @param array $arguments
     * @return bool
     * @throws Exception
     */
    public static function getResourceModel($modelClass, $arguments = array())
    {
        //echo $modelClass.' ';

        return self::getConfig()->getResourceModelInstance($modelClass, $arguments);
    }

    /**
     * Retrieve model object singleton
     *
     * @param string $modelClass
     * @param array $arguments
     * @return mixed
     * @throws Exception
     */
    public static function getSingleton($modelClass='', array $arguments=array())
    {
        $registryKey = '_singleton/'.$modelClass;
        if (!self::registry($registryKey)) {
            self::register($registryKey, self::getModel($modelClass, $arguments));
        }

        return self::registry($registryKey);
    }

    /**
     * Retrieve model object
     *
     * @param string $modelClass
     * @param array $arguments
     * @return bool
     * @throws Exception
     */
    public static function getModel($modelClass = '', $arguments = array())
    {
        return self::getConfig()->getModelInstance($modelClass, $arguments);
    }

    /**
     * Get base URL path by type
     * @param string $type
     * @return string
     * @throws Zend_Controller_Request_Exception
     * @throws Zend_Controller_Response_Exception
     */
    public static function getBaseUrl($type = '')
    {
        $secure = IS_SECURE ? 'secure' : 'unsecure';
        $baseUrl = (string)self::app()->getConfig()->getNode()->default->web->{$secure}->base_url->asArray();
        if($type !== '') {
            $baseUrl .= DS.$type;
        }

        return $baseUrl;
    }

    /**
     * @return string
     * @throws Zend_Controller_Request_Exception
     * @throws Zend_Controller_Response_Exception
     */
    public static function getImgUrl()
    {
        return
            self::getBaseUrl('skin').DS.
            Core_Model_Design_Package::DEFAULT_AREA.DS.
            Core_Model_Design_Package::BASE_PACKAGE.DS.
            Core_Model_Design_Package::DEFAULT_THEME.DS.
            'images';
    }

    /**
     * Retrieve module absolute path by directory type
     *
     * @param string $type
     * @param string $moduleName
     * @return string
     */
    public static function getModuleDir($type, $moduleName)
    {
        return self::getConfig()->getModuleDir($type, $moduleName);
    }

    /**
     * Retrieve Controller instance by ClassName
     *
     * @param string $class
     * @param Core_Controller_Request_Http $request
     * @param Core_Controller_Response_Http $response
     * @param array $invokeArgs
     * @return Core_Controller_Front_Action
     */
    public static function getControllerInstance($class, $request, $response, array $invokeArgs = array())
    {
        return new $class($request, $response, $invokeArgs);
    }

    /**
     * Retrieve config value for store by path
     * @param $path
     * @return string|null
     * @throws Zend_Controller_Request_Exception
     * @throws Zend_Controller_Response_Exception
     */
    public static function getStoreConfig($path)
    {
        return self::app()->getVirtual()->getConfig($path);
    }

    /**
     * @return Core_Model_Design_Package
     * @throws Exception
     */
    public static function getDesign()
    {
        return self::getSingleton('core/design_package');
    }

    /**
     * Retrieve helper object
     *
     * @param string $name the helper name
     * @return Core_Helper_Abstract
     */
    public static function helper($name)
    {
        $registryKey = '_helper/' . $name;
        //echo $registryKey;

        if (!self::registry($registryKey)) {
            $helperClass = self::getConfig()->getHelperClassName($name);
            //Virtual::dump(self::getConfig());
            self::register($registryKey, new $helperClass);
        }

        return self::registry($registryKey);
    }

    /**
     * Retrieve resource model object singleton
     *
     * @param string $modelClass
     * @param array $arguments
     * @return mixed
     * @throws Exception
     */
    public static function getResourceSingleton($modelClass = '', array $arguments = array())
    {
        $registryKey = '_resource_singleton/'.$modelClass;
        if (!self::registry($registryKey)) {
            self::register($registryKey, self::getResourceModel($modelClass, $arguments));
        }

        return self::registry($registryKey);
    }

    /**
     * Generate url by route and parameters
     *
     * @param string $route
     * @param array $params
     * @return mixed
     * @throws Exception
     */
    public static function getUrl($route = '', $params = array())
    {
        return self::getModel('core/url')->getUrl($route, $params);
    }

    /**
     * @return string
     * @throws Zend_Controller_Request_Exception
     * @throws Zend_Controller_Response_Exception
     */
    public static function getErrorDir()
    {
        return self::getBaseUrl().DS.'errors';
    }

    /**
     * Return new exception by module to be thrown
     *
     * @param string $module
     * @param string $message
     * @param integer $code
     * @return Core_Exception
     */
    public static function exception($module = 'Core', $message = '', $code = 0)
    {
        $className = $module . '_Exception';

        return new $className($message, $code);
    }

    /**
     * Retrieve config flag for store by path
     *
     * @param string $path
     * @param mixed $store
     * @return bool
     */
    public static function getStoreConfigFlag($path, $store = null)
    {
        $flag = strtolower(self::getStoreConfig($path, $store));
        if (!empty($flag) && 'false' !== $flag) {
            return true;
        }
        else {
            return false;
        }
    }

    /**
     * @param $module
     * @return string
     * @throws Zend_Controller_Request_Exception
     * @throws Zend_Controller_Response_Exception
     */
    public static function getTooltipsUrl($module)
    {
        return
            self::getBaseUrl('app/design').DS.
            Core_Model_Design_Package::DEFAULT_AREA.DS.
            Core_Model_Design_Package::BASE_PACKAGE.DS.
            Core_Model_Design_Package::DEFAULT_THEME.DS.
            'template'.DS.
            $module .DS.
            'tooltips';
    }

    /**
     * @param $module
     * @return string
     */
    public static function getAbsModuleTemplate($module)
    {
        return
            self::getConfig()->getOptions()->getDesignDir(). DS.
            Core_Model_Design_Package::DEFAULT_AREA.DS.
            Core_Model_Design_Package::BASE_PACKAGE.DS.
            Core_Model_Design_Package::DEFAULT_THEME.DS.
            'template'.DS.
            $module;
    }

}//End of class
