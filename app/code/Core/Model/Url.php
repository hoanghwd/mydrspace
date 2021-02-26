<?php

class Core_Model_Url extends Varien_Object
{
    /**
     * Default controller name
     */
    const DEFAULT_CONTROLLER_NAME   = 'index';

    /**
     * Default action name
     */
    const DEFAULT_ACTION_NAME       = 'index';

    /**
     * XML base url path unsecure
     */
    const XML_PATH_UNSECURE_URL     = 'web/unsecure/base_url';

    /**
     * XML base url path secure
     */
    const XML_PATH_SECURE_URL       = 'web/secure/base_url';

    /**
     * XML path for using in frontend
     */
    const XML_PATH_SECURE_IN_FRONT  = 'web/secure/use_in_frontend';

    /**
     * Param name for form key functionality
     */
    const FORM_KEY = 'form_key';

    /**
     * Configuration data cache
     *
     * @var array
     */
    static protected $_configDataCache;

    /**
     * Encrypted session identifier
     *
     * @var string|null
     */
    static protected $_encryptedSessionId;

    /**
     * Reserved Route parameter keys
     *
     * @var array
     */
    protected $_reservedRouteParams = array(
        '_store', '_type', '_secure', '_forced_secure', '_use_rewrite', '_nosid',
        '_absolute', '_current', '_direct', '_fragment', '_escape', '_query',
        '_store_to_url'
    );

    /**
     * Controller request object
     *
     * @var Zend_Controller_Request_Http
     */
    protected $_request;

    /**
     * Use Session ID for generate URL
     *
     * @var bool
     */
    protected $_useSession;

    protected function _construct()
    {
        $this->setStore(null);
    }

    /**
     * @param $data
     * @return $this
     * @throws Zend_Controller_Request_Exception
     * @throws Zend_Controller_Response_Exception
     */
    public function setStore($data)
    {
        $this->setData('store', Virtual::app()->getStore($data));

        return $this;
    }

    /**
     * Get current store for the url instance
     *
     * @return Core_Model_Store
     */
    public function getStore()
    {
        if (!$this->hasData('store')) {
            $this->setStore(null);
        }
        return $this->_getData('store');
    }


    /**
     * Build url by requested path and parameters
     *
     * @param string|null $routePath
     * @param array|null $routeParams
     * @return  string
     */
    public function getUrl($routePath = null, $routeParams = null)
    {
        $escapeQuery = false;

        /**
         * All system params should be unset before we call getRouteUrl
         * this method has condition for adding default controller and action names
         * in case when we have params
         */
        if (isset($routeParams['_fragment'])) {
            $this->setFragment($routeParams['_fragment']);
            unset($routeParams['_fragment']);
        }

        if (isset($routeParams['_escape'])) {
            $escapeQuery = $routeParams['_escape'];
            unset($routeParams['_escape']);
        }

        $query = null;
        if (isset($routeParams['_query'])) {
            $this->purgeQueryParams();
            $query = $routeParams['_query'];
            unset($routeParams['_query']);
        }

        $noSid = null;
        if (isset($routeParams['_nosid'])) {
            $noSid = (bool)$routeParams['_nosid'];
            unset($routeParams['_nosid']);
        }


        $url = $this->getRouteUrl($routePath, $routeParams);

        /**
         * Apply query params, need call after getRouteUrl for rewrite _current values
         */
        if ($query !== null) {
            if (is_string($query)) {
                $this->setQuery($query);
            } elseif (is_array($query)) {
                $this->setQueryParams($query, !empty($routeParams['_current']));
            }
            if ($query === false) {
                $this->setQueryParams(array());
            }
        }

        $query = $this->getQuery($escapeQuery);
        if ($query) {
            $mark = (strpos($url, '?') === false) ? '?' : ($escapeQuery ? '&amp;' : '&');
            $url .= $mark . $query;
        }

        if ($this->getFragment()) {
            $url .= '#' . $this->getFragment();
        }

        return $this->escape($url);
    }

    /**
     * Build url by direct url and parameters
     *
     * @param string $url
     * @param array $params
     * @return string
     */
    public function getDirectUrl($url, $params = array()) {
        $params['_direct'] = $url;
        return $this->getUrl('', $params);
    }

    /**
     * Escape (enclosure) URL string
     *
     * @param string $value
     * @return string
     */
    public function escape($value)
    {
        $value = str_replace('"', '%22', $value);
        $value = str_replace("'", '%27', $value);
        $value = str_replace('>', '%3E', $value);
        $value = str_replace('<', '%3C', $value);
        return $value;
    }


    /**
     * Retrieve route URL
     *
     * @param string $routePath
     * @param array $routeParams
     *
     * @return string
     */
    public function getRouteUrl($routePath = null, $routeParams = null)
    {
        $this->unsetData('route_params');

        if (isset($routeParams['_direct'])) {
            if (is_array($routeParams)) {
                $this->setRouteParams($routeParams, false);
            }
            return $this->getBaseUrl() . $routeParams['_direct'];
        }

        if (!is_null($routePath)) {
            $this->setRoutePath($routePath);
        }
        if (is_array($routeParams)) {
            $this->setRouteParams($routeParams, false);
        }

        $url = $this->getBaseUrl() . $this->getRoutePath($routeParams);

        return $url;
    }

    /**
     * Retrieve route path
     *
     * @param array $routParams
     * @return string
     */
    public function getRoutePath($routeParams = array())
    {
        if (!$this->hasData('route_path')) {
            $routePath = $this->getRequest()->getAlias(Core_Model_Url_Rewrite::REWRITE_REQUEST_PATH_ALIAS);
            if (!empty($routeParams['_use_rewrite']) && ($routePath !== null)) {
                $this->setData('route_path', $routePath);
                return $routePath;
            }
            $routePath = $this->getActionPath();
            if ($this->getRouteParams()) {
                foreach ($this->getRouteParams() as $key => $value) {
                    if (is_null($value) || false === $value || '' === $value || !is_scalar($value)) {
                        continue;
                    }
                    $routePath .= $key . '/' . $value . '/';
                }
            }
            if ($routePath != '' && substr($routePath, -1, 1) !== '/') {
                $routePath .= '/';
            }
            $this->setData('route_path', $routePath);

        }

        return $this->_getData('route_path');
    }

    /**
     * Retrieve route params
     *
     * @return array
     */
    public function getRouteParams()
    {
        return $this->_getData('route_params');
    }

    /**
     * Retrieve action path
     *
     * @return string
     */
    public function getActionPath()
    {
        if (!$this->getRouteName()) {
            return '';
        }

        $hasParams = (bool) $this->getRouteParams();
        $path = $this->getRouteFrontName() . '/';

        if ($this->getControllerName()) {
            $path .= $this->getControllerName() . '/';
        }
        elseif ($hasParams) {
            $path .= $this->getDefaultControllerName() . '/';
        }
        if ($this->getActionName()) {
            $path .= $this->getActionName() . '/';
        }
        elseif ($hasParams) {
            $path .= $this->getDefaultActionName() . '/';
        }

        return $path;
    }

    /**
     * Retrieve default action name
     *
     * @return string
     */
    public function getDefaultActionName()
    {
        return self::DEFAULT_ACTION_NAME;
    }

    /**
     * Retrieve action name
     *
     * @return string|null
     */
    public function getActionName()
    {
        return $this->_getData('action_name');
    }

    /**
     * Set route param
     *
     * @param string $key
     * @param mixed $data
     * @return Core_Model_Url
     */
    public function setRouteParam($key, $data)
    {
        $params = $this->_getData('route_params');
        if (isset($params[$key]) && $params[$key] == $data) {
            return $this;
        }
        $params[$key] = $data;
        $this->unsetData('route_path');

        return $this->setData('route_params', $params);
    }


    /**
     * Set route params
     *
     * @param array $data
     * @param boolean $unsetOldParams
     * @return Core_Model_Url
     */
    public function setRouteParams(array $data, $unsetOldParams = true)
    {
        if (isset($data['_type'])) {
            $this->setType($data['_type']);
            unset($data['_type']);
        }

        if (isset($data['_store'])) {
            $this->setStore($data['_store']);
            unset($data['_store']);
        }

        if (isset($data['_forced_secure'])) {
            $this->setSecure((bool)$data['_forced_secure']);
            $this->setSecureIsForced(true);
            unset($data['_forced_secure']);
        } elseif (isset($data['_secure'])) {
            $this->setSecure((bool)$data['_secure']);
            unset($data['_secure']);
        }

        if (isset($data['_absolute'])) {
            unset($data['_absolute']);
        }

        if ($unsetOldParams) {
            $this->unsetData('route_params');
        }

        $this->setUseUrlCache(true);
        if (isset($data['_current'])) {
            if (is_array($data['_current'])) {
                foreach ($data['_current'] as $key) {
                    if (array_key_exists($key, $data) || !$this->getRequest()->getUserParam($key)) {
                        continue;
                    }
                    $data[$key] = $this->getRequest()->getUserParam($key);
                }
            } elseif ($data['_current']) {
                foreach ($this->getRequest()->getUserParams() as $key => $value) {
                    if (array_key_exists($key, $data) || $this->getRouteParam($key)) {
                        continue;
                    }
                    $data[$key] = $value;
                }
                foreach ($this->getRequest()->getQuery() as $key => $value) {
                    $this->setQueryParam($key, $value);
                }
                $this->setUseUrlCache(false);
            }
            unset($data['_current']);
        }

        if (isset($data['_use_rewrite'])) {
            unset($data['_use_rewrite']);
        }

        if (isset($data['_store_to_url']) && (bool)$data['_store_to_url'] === true) {
            if (!Virtual::getStoreConfig(Core_Model_Store::XML_PATH_STORE_IN_URL, $this->getStore())
                && !Mage::app()->isSingleStoreMode()
            ) {
                $this->setQueryParam('___store', $this->getStore()->getCode());
            }
        }
        unset($data['_store_to_url']);

        foreach ($data as $k => $v) {
            $this->setRouteParam($k, $v);
        }

        return $this;
    }


    /**
     * Retrieve default controller name
     *
     * @return string
     */
    public function getDefaultControllerName()
    {
        return self::DEFAULT_CONTROLLER_NAME;
    }

    /**
     * Set URL query param(s)
     *
     * @param mixed $data
     * @return Core_Model_Url
     */
    public function setQuery($data)
    {
        if ($this->_getData('query') == $data) {
            return $this;
        }
        $this->unsetData('query_params');

        return $this->setData('query', $data);
    }

    /**
     * Get query params part of url
     *
     * @param bool $escape "&" escape flag
     * @return string
     */
    public function getQuery($escape = false)
    {
        if (!$this->hasData('query')) {
            $query = '';
            $params = $this->getQueryParams();
            if (is_array($params)) {
                ksort($params);
                $query = http_build_query($params, '', $escape ? '&amp;' : '&');
            }
            $this->setData('query', $query);
        }
        return $this->_getData('query');
    }

    /**
     * Set query Params as array
     *
     * @param array $data
     * @return Core_Model_Url
     */
    public function setQueryParams(array $data)
    {
        $this->unsetData('query');

        if ($this->_getData('query_params') == $data) {
            return $this;
        }

        $params = $this->_getData('query_params');
        if (!is_array($params)) {
            $params = array();
        }
        foreach ($data as $param => $value) {
            $params[$param] = $value;
        }
        $this->setData('query_params', $params);

        return $this;
    }

    /**
     * Purge Query params array
     *
     * @return Core_Model_Url
     */
    public function purgeQueryParams()
    {
        $this->setData('query_params', array());
        return $this;
    }

    /**
     * Return Query Params
     *
     * @return array
     */
    public function getQueryParams()
    {
        if (!$this->hasData('query_params')) {
            $params = array();
            if ($this->_getData('query')) {
                foreach (explode('&', $this->_getData('query')) as $param) {
                    $paramArr = explode('=', $param);
                    $params[$paramArr[0]] = urldecode($paramArr[1]);
                }
            }
            $this->setData('query_params', $params);
        }
        return $this->_getData('query_params');
    }

    /**
     * Set query param
     *
     * @param string $key
     * @param mixed $data
     * @return Core_Model_Url
     */
    public function setQueryParam($key, $data)
    {
        $params = $this->getQueryParams();
        if (isset($params[$key]) && $params[$key] == $data) {
            return $this;
        }
        $params[$key] = $data;
        $this->unsetData('query');
        return $this->setData('query_params', $params);
    }



    /**
     * Retrieve controller name
     *
     * @return string|null
     */
    public function getControllerName()
    {
        return $this->_getData('controller_name');
    }


    /**
     * Set fragment to URL
     *
     * @param string $data
     * @return Core_Model_Url
     */
    public function setFragment($data)
    {
        return $this->setData('fragment', $data);
    }

    /**
     * Retrieve URL fragment
     *
     * @return string|null
     */
    public function getFragment()
    {
        return $this->_getData('fragment');
    }



    /**
     * Retrieve route name
     *
     * @return string|null
     */
    public function getRouteName()
    {
        return $this->_getData('route_name');
    }

    /**
     * @return Core_Controller_Request_Http|Zend_Controller_Request_Http
     * @throws Zend_Controller_Request_Exception
     * @throws Zend_Controller_Response_Exception
     */
    public function getRequest()
    {
        if (!$this->_request) {
            $this->_request = Virtual::app()->getRequest();
        }
        return $this->_request;
    }

    /**
     * Retrieve Base URL
     *
     * @param array $params
     * @return string
     */
    public function getBaseUrl($params = array())
    {
        return $this->getStore()->getBaseUrl($this->getType(), IS_SECURE);
    }

    /**
     * Retrieve URL type
     *
     * @return string
     */
    public function getType()
    {
        if (!$this->hasData('type')) {
            $this->setData('type', Core_Model_Store::URL_TYPE_LINK);
        }

        return $this->_getData('type');
    }

    /**
     * Return singleton model instance
     *
     * @param string $name
     * @param array $arguments
     * @return Core_Model_Abstract
     */
    protected function _getSingletonModel($name, $arguments = array())
    {
        return Virtual::getSingleton($name, $arguments);
    }


}//End of class