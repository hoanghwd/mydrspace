<?php
/**
 * Created by PhpStorm.
 * User: hdo
 * Date: 1/17/2019
 * Time: 3:08 PM
 */

class Core_Model_Session_Abstract extends Core_Model_Session_Abstract_Varien
{
    const VALIDATOR_KEY                         = '_session_validator_data';
    const VALIDATOR_HTTP_USER_AGENT_KEY         = 'http_user_agent';
    const VALIDATOR_HTTP_X_FORVARDED_FOR_KEY    = 'http_x_forwarded_for';
    const VALIDATOR_HTTP_VIA_KEY                = 'http_via';
    const VALIDATOR_REMOTE_ADDR_KEY             = 'remote_addr';
    const VALIDATOR_SESSION_EXPIRE_TIMESTAMP    = 'session_expire_timestamp';
    const VALIDATOR_PASSWORD_CREATE_TIMESTAMP   = 'password_create_timestamp';
    const SECURE_COOKIE_CHECK_KEY               = '_secure_cookie_check';
    const COOKIE_FRONT_END                      = 'frontend';

    /**
     * Map of session enabled hosts
     * @example array('host.name' => true)
     * @var array
     */
    protected $_sessionHosts = array();

    /**
     * @param null $sessionName
     * @return Core_Model_Session_Abstract
     * @throws Zend_Controller_Request_Exception
     * @throws Zend_Controller_Response_Exception
     */
    public function start($sessionName=null)
    {
        $cookie = $this->getCookie();

        // session cookie params
        $cookieParams = array(
            'lifetime' => $cookie->getLifetime(),
            'path'     => $cookie->getPath(),
            'domain'   => $cookie->getDomain()
        );

        //call_user_func_array('session_set_cookie_params', $cookieParams);

        if (!empty($sessionName)) {
            $this->setSessionName($sessionName);
        }

        // potential custom logic for session id (ex. switching between hosts)
        $this->setSessionId();


        if(!isset($_SESSION)) {
            session_start();
        }

        if (!empty($sessionName)) {
            $_SESSION[$sessionName] = $this->getSessionId();
        }

        /**
         * Renew cookie expiration time if session id did not change
         */
        if ($cookie->get(session_name()) == $this->getSessionId()) {
            $cookie->renew(session_name());
        }

        return $this;
    }

    /**
     * Init session
     *
     * @param string $namespace
     * @param string $sessionName
     * @return Core_Model_Session_Abstract
     */
    public function init($namespace, $sessionName=null)
    {
        parent::init($namespace, $sessionName);
        $this->addHost(true);

        return $this;
    }


    /**
     * Add hostname to session
     *
     * @param string $host
     * @return Core_Model_Session_Abstract
     */
    public function addHost($host)
    {
        if ($host === true) {
            if (!$host = Virtual::app()->getFrontController()->getRequest()->getHttpHost()) {
                return $this;
            }
        }

        if (!$host) {
            return $this;
        }

        $hosts = $this->getSessionHosts();
        $hosts[$host] = true;
        $this->setSessionHosts($hosts);

        return $this;
    }


    /**
     * Retrieve cookie object
     *
     * @return Core_Model_Cookie
     */
    public function getCookie()
    {
        return new Core_Model_Cookie();
    }

    /**
     * Set session name
     *
     * @param string $name
     * @return Core_Model_Session_Abstract
     */
    public function setSessionName($name)
    {
        //session_name($name);

        return $this;
    }

    /**
     * Set custom session id
     *
     * @param string $id
     * @return Core_Model_Session_Abstract
     */
    public function setSessionId($id=null)
    {
        if (!is_null($id) && preg_match('#^[0-9a-zA-Z,-]+$#', $id)) {
            session_id($id);
        }

        return $this;
    }

    /**
     * Retrieve session Id
     *
     * @return string
     */
    public function getSessionId()
    {
        return session_id();
    }

    /**
     * Unset all data
     *
     * @return Core_Model_Session_Abstract
     */
    public function unsetAll()
    {
        $this->unsetData();

        return $this;
    }

    /**
     * @return Core_Model_Session_Abstract
     */
    public function clear()
    {
        return $this->unsetAll();
    }


    /**
     * Retrieve messages from session
     *
     * @param   bool $clear
     * @return  Core_Model_Message_Collection
     */
    public function getMessages($clear=false)
    {
        if (!$this->getData('messages')) {
            $this->setMessages(Virtual::getModel('core/message_collection'));
        }

        if ($clear) {
            $messages = clone $this->getData('messages');
            $this->getData('messages')->clear();

            return $messages;
        }
        return $this->getData('messages');
    }

}//End of class