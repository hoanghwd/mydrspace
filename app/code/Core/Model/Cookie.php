<?php
/**
 * Created by PhpStorm.
 * User: hdo
 * Date: 1/17/2019
 * Time: 3:19 PM
 */

class Core_Model_Cookie
{
    protected $_lifetime;

    /**
     * Retrieve Request object
     * @return Core_Controller_Request_Http|Zend_Controller_Request_Http
     * @throws Zend_Controller_Request_Exception
     */
    protected function _getRequest()
    {
        return Virtual::app()->getRequest();
    }

    /**
     * @return Zend_Controller_Response_Http
     * @throws Zend_Controller_Request_Exception
     */
    protected function _getResponse()
    {
        return Virtual::app()->getResponse();
    }

    /**
     * Retrieve Domain for cookie
     * @return bool|mixed|string
     * @throws Zend_Controller_Request_Exception
     * @throws Zend_Controller_Response_Exception
     */
    public function getDomain()
    {
        return $this->_getRequest()->getHttpHost();
    }

    /**
     * Retrieve Path for cookie
     * @return mixed|string
     * @throws Zend_Controller_Request_Exception
     */
    public function getPath()
    {
        return $this->_getRequest()->getBasePath();
    }

    /**
     * Retrieve cookie lifetime
     *
     * @return int
     */
    public function getLifetime()
    {
        $path = (Core_Model_Virtual::XML_ROOT.DS).Core_Model_Virtual::XML_PATH_COOKIE_LIFE_TIME;

        return Virtual::getConfig()->getNode($path)->asArray();
    }

    /**
     * Set cookie lifetime
     *
     * @param int $lifetime
     * @return Core_Model_Cookie
     */
    public function setLifetime($lifetime)
    {
        $this->_lifetime = (int)$lifetime;

        return $this;
    }

    /**
     * @param $name
     * @param $value
     * @param null $period
     * @param null $path
     * @param null $domain
     * @return Core_Model_Cookie
     * @throws Zend_Controller_Request_Exception
     * @throws Zend_Controller_Response_Exception
     */
    public function set($name, $value, $period = null, $path = null, $domain = null)
    {
        /**
         * Check headers sent
         */
        if (!$this->_getResponse()->canSendHeaders(false)) {
            return $this;
        }

        if ($period === true) {
            $period = 3600 * 24 * 365;
        }
        elseif (is_null($period)) {
            $period = $this->getLifetime();
        }

        if ($period == 0) {
            $expire = 0;
        }
        else {
            $expire = time() + $period;
        }
        if (is_null($path)) {
            $path = $this->getPath();
        }
        if (is_null($domain)) {
            $domain = $this->getDomain();
        }

        setcookie($name, $value, $expire, $path, $domain);

        return $this;
    }

    /**
     * Postpone cookie expiration time if cookie value defined
     * @param $name
     * @param null $period
     * @param null $path
     * @param null $domain
     * @return Core_Model_Cookie
     * @throws Zend_Controller_Request_Exception
     * @throws Zend_Controller_Response_Exception
     */
    public function renew($name, $period = null, $path = null, $domain = null)
    {
        if (($period === null) && !$this->getLifetime()) {
            return $this;
        }
        $value = $this->_getRequest()->getCookie($name, false);
        if ($value !== false) {
            $this->set($name, $value, $period, $path, $domain);
        }

        return $this;
    }

    /**
     * Retrieve cookie or false if not exists
     * @param null $name
     * @return mixed
     * @throws Zend_Controller_Request_Exception
     */
    public function get($name = null)
    {
        return $this->_getRequest()->getCookie($name, false);
    }

    /**
     *  Delete cookie
     * @param $name
     * @param null $path
     * @param null $domain
     * @return Core_Model_Cookie
     * @throws Zend_Controller_Request_Exception
     * @throws Zend_Controller_Response_Exception
     */
    public function delete($name, $path = null, $domain = null)
    {
        //Destroy cookie
        if( isset($_COOKIE[$name]) ) {
            unset($_COOKIE[$name]);
        }

        /**
         * Check headers sent
         */
        if (!$this->_getResponse()->canSendHeaders(false)) {
            return $this;
        }

        if (is_null($path)) {
            $path = $this->getPath();
        }
        if (is_null($domain)) {
            $domain = $this->getDomain();
        }

        setcookie($name, null, null, $path, $domain);

        return $this;
    }

}//End of class