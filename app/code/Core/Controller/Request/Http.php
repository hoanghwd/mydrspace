<?php
/**
 * Created by PhpStorm.
 * User: hdo
 * Date: 3/29/2019
 * Time: 10:46 PM
 */

class Core_Controller_Request_Http extends Zend_Controller_Request_Http
{
    const DEFAULT_HTTP_PORT = 80;
    const DEFAULT_HTTPS_PORT = 443;

    /**
     * ORIGINAL_PATH_INFO
     * @var string
     */
    protected $_originalPathInfo= '';
    protected $_storeCode       = null;
    protected $_requestString   = '';

    protected $_requestedRouteName = null;
    protected $_routingInfo = array();

    protected $_route;

    protected $_directFrontNames = null;
    protected $_controllerModule = null;

    /**
     * Request's original information before forward.
     *
     * @var array
     */
    protected $_beforeForwardInfo = array();

    /**
     * Flag for recognizing if request internally forwarded
     *
     * @var bool
     */
    protected $_internallyForwarded = false;


    /**
     * Specify/get _isStraight flag value
     *
     * @param bool $flag
     * @return bool
     */
    public function isStraight($flag = null)
    {
        if ($flag !== null) {
            $this->_isStraight = $flag;
        }
        return $this->_isStraight;
    }

    /**
     * Set the PATH_INFO string
     * Set the ORIGINAL_PATH_INFO string
     *
     * @param string|null $pathInfo
     * @return Zend_Controller_Request_Http
     */
    public function setPathInfo($pathInfo = null)
    {
        if ($pathInfo === null) {
            $requestUri = $this->getRequestUri();
            if (null === $requestUri) {
                return $this;
            }

            // Remove the query string from REQUEST_URI
            $pos = strpos($requestUri, '?');
            if ($pos) {
                $requestUri = substr($requestUri, 0, $pos);
            }

            $baseUrl = $this->getBaseUrl();
            $pathInfo = substr($requestUri, strlen($baseUrl));

            if ($baseUrl && $pathInfo && (0 !== stripos($pathInfo, '/'))) {
                $pathInfo = '';
                $this->setActionName('noRoute');
            }
            elseif ((null !== $baseUrl) && (false === $pathInfo)) {
                $pathInfo = '';
            }
            elseif (null === $baseUrl) {
                $pathInfo = $requestUri;
            }

            $this->_originalPathInfo = (string) $pathInfo;
            $this->_requestString = $pathInfo . ($pos!==false ? substr($requestUri, $pos) : '');
        }

        $this->_pathInfo = (string) $pathInfo;

        return $this;
    }

    /**
     * @param bool $raw
     * @return string
     */
    public function getBaseUrl($raw = false)
    {
        $url = parent::getBaseUrl($raw);
        $url = str_replace('\\', '/', $url);

        return $url;
    }

    /**
     * @param $route
     * @return Core_Controller_Request_Http
     * @throws Zend_Controller_Request_Exception
     */
    public function setRouteName($route)
    {
        $this->_route = $route;
        $router = Virtual::app()->getFrontController()->getRouterByRoute($route);

        if (!$router)
            return $this;

        $module = $router->getFrontNameByRoute($route);
        if ($module) {
            $this->setModuleName($module);
        }

        return $this;
    }

    /**
     * Specify module name where was found currently used controller
     *
     * @param   string $module
     * @return  Core_Controller_Request_Http
     */
    public function setControllerModule($module)
    {
        $this->_controllerModule = $module;

        return $this;
    }

    /**
     * Get request string
     *
     * @return string
     */
    public function getRequestString()
    {
        return $this->_requestString;
    }

    /**
     * Retrieve HTTP HOST
     * @param bool $trimPort
     * @return bool|mixed|string
     * @throws Zend_Controller_Response_Exception
     */
    public function getHttpHost($trimPort = true)
    {
        if (!isset($_SERVER['HTTP_HOST'])) {
            return false;
        }
        $host = $_SERVER['HTTP_HOST'];
        if ($trimPort) {
            $hostParts = explode(':', $_SERVER['HTTP_HOST']);
            $host =  $hostParts[0];
        }

        if (strpos($host, ',') !== false || strpos($host, ';') !== false) {
            $response = new Zend_Controller_Response_Http();
            $response->setHttpResponseCode(400)->sendHeaders();
            exit();
        }

        return $host;
    }

    /**
     * Set a member of the $_POST superglobal
     *
     * @param string|array $key
     * @param mixed $value
     *
     * @return Core_Controller_Request_Http
     */
    public function setPost($key, $value = null)
    {
        if (is_array($key)) {
            $_POST = $key;
        }
        else {
            $_POST[$key] = $value;
        }

        return $this;
    }

    /**
     * @return Zend_Controller_Request_Http
     * @throws Zend_Controller_Request_Exception
     */
    public function getOriginalRequest()
    {
        $request = new Zend_Controller_Request_Http();
        $request->setPathInfo($this->getOriginalPathInfo());

        return $request;
    }

    /**
     * Returns ORIGINAL_PATH_INFO.
     * This value is calculated instead of reading PATH_INFO
     * directly from $_SERVER due to cross-platform differences.
     *
     * @return string
     */
    public function getOriginalPathInfo()
    {
        if (empty($this->_originalPathInfo)) {
            $this->setPathInfo();
        }
        return $this->_originalPathInfo;
    }

    /**
     * @return mixed|string
     */
    public function getBasePath()
    {
        $path = parent::getBasePath();
        if (empty($path)) {
            $path = '/';
        }
        else {
            $path = str_replace('\\', '/', $path);
        }

        return $path;
    }

    /**
     * Collect properties changed by _forward in protected storage
     * before _forward was called first time.
     * @return Core_Controller_Request_Http
     */
    public function initForward()
    {
        if (empty($this->_beforeForwardInfo)) {
            $this->_beforeForwardInfo = array(
                'params' => $this->getParams(),
                'action_name' => $this->getActionName(),
                'controller_name' => $this->getControllerName(),
                'module_name' => $this->getModuleName()
            );
        }

        return $this;
    }

    /**
     * Define that request was forwarded internally
     *
     * @param boolean $flag
     * @return Core_Controller_Request_Http
     */
    public function setInternallyForwarded($flag = true)
    {
        $this->_internallyForwarded = (bool)$flag;

        return $this;
    }

    /**
     * Checks if request was forwarded internally
     *
     * @return bool
     */
    public function getInternallyForwarded()
    {
        return $this->_internallyForwarded;
    }

    /**
     * Check is Request from AJAX
     *
     * @return boolean
     */
    public function isAjax()
    {
        if ($this->isXmlHttpRequest()) {
            return true;
        }
        if ($this->getParam('ajax') || $this->getParam('isAjax')) {
            return true;
        }
        return false;
    }

    /**
     * Retrieve property's value which was before _forward call.
     * If property was not changed during _forward call null will be returned.
     * If passed name will be null whole state array will be returned.
     *
     * @param string $name
     * @return array|string|null
     */
    public function getBeforeForwardInfo($name = null)
    {
        if (is_null($name)) {
            return $this->_beforeForwardInfo;
        } elseif (isset($this->_beforeForwardInfo[$name])) {
            return $this->_beforeForwardInfo[$name];
        }

        return null;
    }

    /**
     * Set routing info data
     *
     * @param array $data
     * @return Core_Controller_Request_Http
     */
    public function setRoutingInfo($data)
    {
        if (is_array($data)) {
            $this->_routingInfo = $data;
        }
        return $this;
    }

    /**
     * Get action name used in request (ignore rewrite)
     *
     * @return string
     */
    public function getRequestedActionName()
    {
        if (isset($this->_routingInfo['requested_action'])) {
            return $this->_routingInfo['requested_action'];
        }

        return $this->getActionName();
    }

    /**
     * Get controller name used in request (ignore rewrite)
     *
     * @return string
     */
    public function getRequestedControllerName()
    {
        if (isset($this->_routingInfo['requested_controller'])) {
            return $this->_routingInfo['requested_controller'];
        }

        return $this->getControllerName();
    }

    /**
     * Get route name used in request (ignore rewrite)
     *
     * @return string
     */
    public function getRequestedRouteName()
    {
        if (isset($this->_routingInfo['requested_route'])) {
            return $this->_routingInfo['requested_route'];
        }
        if ($this->_requestedRouteName === null) {
            // no rewritten path found, use default route name
            return $this->getRouteName();

        }
        return $this->_requestedRouteName;
    }

    /**
     * Retrieve the action name
     *
     * @return string
     */
    public function getActionName()
    {
        return $this->_action;
    }

    /**
     * Retrieve the controller name
     *
     * @return string
     */
    public function getControllerName()
    {
        return $this->_controller;
    }

    /**
     * Retrieve the module name
     *
     * @return string
     */
    public function getModuleName()
    {
        return $this->_module;
    }

    public function getRouteName()
    {
        return $this->_route;
    }

}//End of class