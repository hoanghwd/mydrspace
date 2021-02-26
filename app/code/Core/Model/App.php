<?php

/**
 * Created by PhpStorm.
 * User: hdo
 * Date: 3/27/2019
 * Time: 1:52 PM
 */
class Core_Model_App
{
    const DEFAULT_ERROR_HANDLER = 'virtualCoreErrorHandler';

    const DEFAULT_STORE_ID = 1;

    /**
     * Admin store Id
     *
     */
    const ADMIN_STORE_ID = 0;


    /**
     * Default store Id (for install)
     */
    const DISTRO_STORE_ID       = 1;

    /**
     * Application loaded areas array
     *
     * @var array
     */
    protected $_areas = array();

    /**
     * Application configuration object
     *
     * @var Core_Model_Config $_config
     */
    protected $_config;

    /**
     * Application design package object
     *
     * @var Core_Model_Design_Package
     */
    protected $_design;

    /**
     * Application layout object
     *
     * @var Core_Model_Layout
     */
    protected $_layout;

    /**
     * Application front controller
     *
     * @var Core_Controller_Varien_Front
     */
    protected $_frontController;

    /**
     * Request object
     *
     * @var Zend_Controller_Request_Http
     */
    protected $_request;

    /**
     * Response object
     *
     * @var Zend_Controller_Response_Http
     */
    protected $_response;

    /**
     * Websites cache
     *
     * @var array
     */
    protected $_websites = array();

    /**
     * Groups cache
     *
     * @var array
     */
    protected $_groups = array();

    /**
     * Stores cache
     *
     * @var array
     */
    protected $_stores = array();

    /**
     * is a single store mode
     *
     * @var bool
     */
    protected $_isSingleStore;

    /**
     * Default store code
     *
     * @var string
     */
    protected $_currentStore;


    /**
     * Constructor
     */
    public function __construct()
    {
    }

    /**
     * @param $code
     * @param null $type
     * @param array $options
     * @return Core_Model_App
     * @throws Zend_Controller_Request_Exception
     * @throws Zend_Controller_Response_Exception
     */
    public function init($code, $type = null, $options = array())
    {
        $this->_initEnvironment();
        if (is_string($options)) {
            $options = array('etc_dir'=>$options);
        }

        $this->_config = Virtual::getConfig();
        $this->_config->setOptions($options);
        $this->_initBaseConfig();
        $this->_config->init($options);

        $this->_initVirtual();
        $this->_initRequest();

        return $this;
    }

    /**
     * @param $scopeCode
     * @param null $scopeType
     * @param array $options
     * @param array $modules
     */
    public function initSpecified($scopeCode, $scopeType = null, $options = array(), $modules = array())
    {

    }

    /**
     * @param $params
     * @return Core_Model_App
     * @throws Zend_Controller_Request_Exception
     * @throws Zend_Controller_Response_Exception
     */
    public function run($params)
    {
        $options = isset($params['options']) ? $params['options'] : array();
        $this->baseInit($options);
        Virtual::register('application_params', $params);

        /**
         * At this time, database connection and all modules should be ready
         */
        $this->_initModules();
        $this->loadAreaPart(Core_Model_App_Area::AREA_GLOBAL);

        if( $this->_config->isLocalConfigLoaded() ) {
            $this->_initSession();
            $this->_initRequest();
        }

        $this->getFrontController()->dispatch();

        return $this;
    }

    /**
     * Init session
     * @return Core_Model_App
     * @throws Zend_Controller_Request_Exception
     * @throws Zend_Controller_Response_Exception
     */
    protected function _initSession()
    {
        $this->_getNewSession()->start(Core_Model_Session_Abstract::COOKIE_FRONT_END);

        return $this;
    }

    /**
     * @return Core_Model_Virtual
     */
    public function getVirtual()
    {
        return new Core_Model_Virtual();
    }

    /**
     * Retrieve front controller object
     *
     * @return Core_Controller_Varien_Front
     */
    public function getFrontController()
    {
        if (!$this->_frontController) {
            $this->_initFrontController();
        }

        return $this->_frontController;
    }

    /**
     * Initialize application front controller
     *
     * @return Core_Model_App
     */
    protected function _initFrontController()
    {
        $this->_frontController = new Core_Controller_Varien_Front();
        Virtual::register('controller', $this->_frontController);
        Varien_Profiler::start('virtual::app::init_front_controller');
        $this->_frontController->init();
        Varien_Profiler::stop('virtual::app::init_front_controller');

        return $this;
    }

    /**
     * @return Core_Model_App
     * @throws Zend_Controller_Request_Exception
     */
    protected function _initRequest()
    {
        $this->getRequest()->setPathInfo();

        return $this;
    }

    /**
     * @return Core_Controller_Request_Http|Zend_Controller_Request_Http
     * @throws Zend_Controller_Request_Exception
     */
    public function getRequest()
    {
        if (empty($this->_request)) {
            $this->_request = new Core_Controller_Request_Http();
        }

        return $this->_request;
    }

    /**
     * Loading part of area data
     * @param $area
     * @return Core_Model_App
     * @throws Zend_Controller_Request_Exception
     */
    public function loadAreaPart($area)
    {
        $this->getArea($area)->load();

        return $this;
    }

    /**
     * Retrieve application area
     *
     * @param   string $code
     * @return  Core_Model_App_Area
     */
    public function getArea($code)
    {
        if (!isset($this->_areas[$code])) {
            $this->_areas[$code] = new Core_Model_App_Area($code, $this);
        }

        //virtual::dump($this->_areas[$code]);
        return $this->_areas[$code];
    }

    /**
     * Initialize active modules configuration and data
     * @return Core_Model_App
     * @throws Exception
     */
    protected function _initModules()
    {
        $this->_config->loadModules();

        if ( $this->_config->isLocalConfigLoaded() ) {
            Varien_Profiler::start('mage::app::init::apply_db_schema_updates');
            //Mage_Core_Model_Resource_Setup::applyAllUpdates();
            Varien_Profiler::stop('mage::app::init::apply_db_schema_updates');
        }

        /**
         * At this call database connection should be ready
         */
        $this->_config->loadDb();

        return $this;
    }

    /**
     * Common logic for all run types
     *
     * @param  string|array $options
     * @return Core_Model_App
     */
    public function baseInit($options)
    {
        $this->_initEnvironment();

        $this->_config = Virtual::getConfig();
        $this->_config->setOptions($options);

        $this->_initBaseConfig();

        return $this;
    }

    /**
     * Initialize base system configuration (local.xml and config.xml files).
     * Base configuration provide ability initialize DB connection and cache backend
     *
     * @return Core_Model_App
     */
    protected function _initBaseConfig()
    {
        Varien_Profiler::start('virtual::app::init::system_config');
        $this->_config->loadBase();
        Varien_Profiler::stop('virtual::app::init::system_config');

        return $this;
    }

    /**
     * Initialize PHP environment
     *
     * @return Core_Model_App
     */
    protected function _initEnvironment()
    {
        $this->setErrorHandler(self::DEFAULT_ERROR_HANDLER);
        date_default_timezone_set(DEFAULT_TIMEZONE);

        return $this;
    }

    /**
     * Redeclare custom error handler
     *
     * @param   string $handler
     * @return  Core_Model_App
     */
    public function setErrorHandler($handler)
    {
        set_error_handler($handler);

        return $this;
    }

    /**
     * Retrieve configuration object
     *
     * @return Core_Model_Config
     */
    public function getConfig()
    {
        return $this->_config;
    }

    /**
     * Response setter
     *
     * @param Core_Controller_Response_Http $response
     * @return Core_Model_App
     */
    public function setResponse(Core_Controller_Response_Http $response)
    {
        $this->_response = $response;

        return $this;
    }

    /**
     * Retrieve response object
     *
     * @return Zend_Controller_Response_Http
     */
    public function getResponse()
    {
        if (empty($this->_response)) {
            $this->_response = new Core_Controller_Response_Http();
            $this->_response->headersSentThrowsException = Virtual::$headersSentThrowsException;
            $this->_response->setHeader("Content-Type", "text/html; charset=UTF-8");
        }

        return $this->_response;
    }

    /**
     * @return Core_Model_Session
     */
    protected function _getNewSession()
    {
        return new Core_Model_Session();
    }

    /**
     * Set current default store
     *
     * @param string $store
     * @return Core_Model_App
     */
    public function setCurrentStore($store)
    {
        $this->_currentStore = $store;

        return $this;
    }

    /**
     *  Retrieve application store object
     * @param null $id
     * @return bool|Core_Model_Store|mixed
     * @throws Exception
     */
    public function getStore($id = null)
    {
        if (is_null($id)) {
            $id = self::DISTRO_STORE_ID;
        }

        if (empty($this->_stores[$id])) {
            $store = Virtual::getModel('core/store');

            /* @var $store Core_Model_Store */
            if (is_numeric($id)) {
                $store->load($id);
            }
            elseif (is_string($id)) {
                $store->load($id, 'code');
            }

            if (!$store->getCode()) {
                Virtual::throwException('Could not find store code');
            }

            $this->_stores[$store->getStoreId()] = $store;
            $this->_stores[$store->getCode()] = $store;
        }

        return $this->_stores[$id];
    }

    /**
     * @param null $id
     * @return Core_Model_Website|mixed
     * @throws Exception
     */
    public function getWebsite($id=null)
    {
        if (is_null($id)) {
            $id = self::DEFAULT_STORE_ID;
        }
        elseif ($id instanceof Core_Model_Website) {
            return $id;
        }
        elseif ($id === true) {
            return $this->_website;
        }

        if (empty($this->_websites[$id])) {
            /**
             * @var Core_Model_Website $website
             */
            $website = Virtual::getModel('core/website');
            if (is_numeric($id)) {
                $website->load($id);
                if (!$website->hasWebsiteId()) {
                    Virtual::throwException('Invalid website id requested.');
                }
            }

            $this->_websites[$website->getWebsiteId()] = $website;
            $this->_websites[$website->getCode()] = $website;
            //Virtual::dump($website);
        }

        return $this->_websites[$id];
    }

}//End of class