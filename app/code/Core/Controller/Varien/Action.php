<?php

abstract class Core_Controller_Varien_Action
{
    const FLAG_NO_CHECK_INSTALLATION    = 'no-install-check';
    const FLAG_NO_DISPATCH              = 'no-dispatch';
    const FLAG_NO_PRE_DISPATCH          = 'no-preDispatch';
    const FLAG_NO_POST_DISPATCH         = 'no-postDispatch';
    const FLAG_NO_START_SESSION         = 'no-startSession';
    const FLAG_NO_DISPATCH_BLOCK_EVENT  = 'no-beforeGenerateLayoutBlocksDispatch';
    const FLAG_NO_COOKIES_REDIRECT      = 'no-cookies-redirect';

    const PARAM_NAME_SUCCESS_URL        = 'success_url';
    const PARAM_NAME_ERROR_URL          = 'error_url';
    const PARAM_NAME_REFERER_URL        = 'referer_url';
    const PARAM_NAME_BASE64_URL         = 'r64';
    const PARAM_NAME_URL_ENCODED        = 'uenc';

    /**
     * Request object
     *
     * @var Zend_Controller_Request_Abstract
     */
    protected $_request;

    /**
     * Response object
     *
     * @var Zend_Controller_Response_Abstract
     */
    protected $_response;

    /**
     * Real module name (like 'Mage_Module')
     *
     * @var string
     */
    protected $_realModuleName;

    /**
     * Action flags
     *
     * for example used to disable rendering default layout
     *
     * @var array
     */
    protected $_flags = array();

    /**
     * Action list where need check enabled cookie
     *
     * @var array
     */
    protected $_cookieCheckActions = array();

    /**
     * Currently used area
     *
     * @var string
     */
    protected $_currentArea;

    /**
     * Namespace for session.
     * Should be defined for proper working session.
     *
     * @var string
     */
    protected $_sessionNamespace;

    /**
     * Whether layout is loaded
     *
     * @see self::loadLayout()
     * @var bool
     */
    protected $_isLayoutLoaded = false;

    /**
     * Title parts to be rendered in the page head title
     *
     * @see self::_title()
     * @var array
     */
    protected $_titles = array();

    /**
     * Whether the default title should be removed
     *
     * @see self::_title()
     * @var bool
     */
    protected $_removeDefaultTitle = false;

    /**
     * Constructor
     *
     * @param Zend_Controller_Request_Abstract $request
     * @param Zend_Controller_Response_Abstract $response
     * @param array $invokeArgs
     */
    public function __construct(Zend_Controller_Request_Abstract $request, Zend_Controller_Response_Abstract $response, array $invokeArgs = array())
    {
        $this->_request = $request;
        $this->_response= $response;

        Virtual::app()->getFrontController()->setAction($this);

        $this->_construct();
    }

    protected function _construct()
    {
    }

    public function hasAction($action)
    {
        return method_exists($this, $this->getActionMethodName($action));
    }

    /**
     * Retrieve request object
     *
     * @return Core_Controller_Request_Http
     */
    public function getRequest()
    {
        return $this->_request;
    }

    /**
     * Retrieve response object
     *
     * @return Core_Controller_Response_Http
     */
    public function getResponse()
    {
        return $this->_response;
    }

    /**
     * Retrieve full bane of current action current controller and
     * current module
     *
     * @param   string $delimiter
     * @return  string
     */
    public function getFullActionName($delimiter='_')
    {
        return
            $this->getRequest()->getRequestedRouteName().$delimiter.
            $this->getRequest()->getRequestedControllerName().$delimiter.
            $this->getRequest()->getRequestedActionName();
    }

    /**
     * Retrieve action method name
     *
     * @param string $action
     * @return string
     */
    public function getActionMethodName($action)
    {
        return $action . 'Action';
    }

    /**
     * @param $action
     */
    public function dispatch($action)
    {
        try {
            $actionMethodName = $this->getActionMethodName($action);
            if (!method_exists($this, $actionMethodName)) {
                $actionMethodName = 'norouteAction';
            }

            if ($this->getRequest()->isDispatched()) {
                $this->$actionMethodName();
            }
        }
        catch(Exception $e) {
            echo $e->getMessage();
        }
    }

    /**
     * @param null $coreRoute
     */
    public function norouteAction($coreRoute = null)
    {

    }

    /**
     * Load layout by handles(s)
     * @param null $handles
     * @param bool $generateBlocks
     * @param bool $generateXml
     * @return Core_Controller_Varien_Action
     * @throws Exception
     */
    public function loadLayout($handles = null, $generateBlocks = true, $generateXml = true)
    {
        // if handles were specified in arguments load them first
        if (false!==$handles && ''!==$handles) {
            $this->getLayout()->getUpdate()->addHandle($handles ? $handles : 'default');
        }

        // add default layout handles for this action
        $this->addActionLayoutHandles();

        $this->loadLayoutUpdates();

        if (!$generateXml) {
            return $this;
        }

        $this->generateLayoutXml();

        if (!$generateBlocks) {
            return $this;
        }

        $this->generateLayoutBlocks();

        $this->_isLayoutLoaded = true;

        return $this;
    }

    /**
     * @return Core_Controller_Varien_Action
     * @throws Exception
     */
    public function generateLayoutBlocks()
    {
        $this->getLayout()->generateBlocks();

        return $this;
    }

    /**
     * @return Core_Controller_Varien_Action
     * @throws Exception
     */
    public function generateLayoutXml()
    {
        $this->getLayout()->generateXml();

        return $this;
    }

    /**
     * Load layout updates for each registered modules
     */
    public function loadLayoutUpdates()
    {
        $this->getLayout()->getUpdate()->load();
        //Virtual::dump($this->getLayout()->getUpdate()->load());
    }

    /**
     * Add default layout handles for this action
     */
    public function addActionLayoutHandles()
    {
        $update = $this->getLayout()->getUpdate();
        $update->addHandle('WEB_'.Virtual::app()->getVirtual()->getCode());
        //Virtual::dump('WEB_'.Virtual::app()->getVirtual()->getCode());

        // load theme handle
        /**
         * @var Core_Model_Design_Package $packages
         */
        $package = Virtual::getSingleton('core/design_package');
        $update->addHandle(
            'THEME_'.$package->getArea().'_'.$package->getPackageName().'_'.$package->getTheme('layout')
        );

        // load action handle
        $update->addHandle(strtolower($this->getFullActionName()));
        //Virtual::dump($update);
    }

    /**
     * Render request view
     * @param string $output
     * @return $this
     */
    /**
     * @param string $output
     * @return $this
     * @throws Exception
     */
    public function renderLayout($output='')
    {
        $output = $this->getLayout()->getOutput();
        $this->getResponse()->appendBody($output);

        return $this;
    }

    /**
     * Retrieve current layout object
     * @return Core_Model_Layout
     * @throws Exception
     */
    public function getLayout()
    {
        return Virtual::getSingleton('core/layout');
    }

    /**
     * Set redirect url into response
     *
     * @param   string $url
     * @return  Core_Controller_Varien_Action
     */
    protected function _redirectUrl($url)
    {
        $this->getResponse()->setRedirect($url);

        return $this;
    }

    /**
     * Throw control to different action (control and module if was specified).
     *
     * @param string $action
     * @param string|null $controller
     * @param string|null $module
     * @param array|null $params
     */
    protected function _forward($action, $controller = null, $module = null, array $params = null)
    {
        $request = $this->getRequest();

        $request->initForward();

        if (isset($params)) {
            $request->setParams($params);
        }

        if (isset($controller)) {
            $request->setControllerName($controller);

            // Module should only be reset if controller has been specified
            if (isset($module)) {
                $request->setModuleName($module);
            }
        }

        $request->setActionName($action)
                ->setDispatched(false);
    }





}//End of class