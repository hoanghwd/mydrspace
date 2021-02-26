<?php
/**
 * Created by PhpStorm.
 * User: hdo
 * Date: 4/12/2019
 * Time: 4:04 PM
 */

class Login_Model_Session extends Core_Model_Abstract
{
    protected $_sessionId;

    protected $_frontEnd;


    /**
     * Login_Model_Session constructor.
     */
    function __construct()
    {
        $this->_sessionId = $this->_getSessionId();
        $this->_frontEnd = Core_Model_Session_Abstract::COOKIE_FRONT_END;

        parent::_construct();
    }

    /**
     * Get session Id
     * @return string
     */
    protected function _getSessionId()
    {
        return $this->_getSession()->getSessionId();
    }

    /**
     * @return Core_Model_Session
     */
    private function _getSession()
    {
        return new Core_Model_Session();
    }    

    /**
     * @return Core_Model_Cookie
     */
    private function _getCookie()
    {
        return new Core_Model_Cookie();
    }

    /**
     * Is user logged in
     * @param false $conditional
     * @return bool
     * @throws Zend_Controller_Request_Exception
     */
    public function isUserLoggedIn($conditional = false)
    {
        $isConditionalLoggedIn =
            isset($_SESSION['userId']) &&
            isset( $_SESSION['username']) &&
            $this->_isFrontEndUserSessionExist() &&
            $this->_isCookieFrontEndMatched() &&
            $this->_isLoggedInSessionMatched();

        if( !$conditional ) {
            return $isConditionalLoggedIn && ( $this->_getUserModel()->getGoogleAuthCode($_SESSION['username']) != '' );
        }

        return $isConditionalLoggedIn;
    }



    /**
     * @return Login_Model_User
     * @throws Exception
     */
    private function _getUserModel()
    {
        return Virtual::getModel('login/user');
    }

    /**
     * Front end user session
     * @return bool
     */
    private function _isFrontEndUserSessionExist()
    {
        return isset($_SESSION[$this->_frontEnd]);
    }

    /**
     * @return bool
     * @throws Zend_Controller_Request_Exception
     */
    private function _isCookieFrontEndMatched()
    {
        if( isset($_COOKIE[$this->_frontEnd]) ) {
            $cookieValue = $this->_getCookie()->get($this->_frontEnd);

            return strcmp($cookieValue, $this->_sessionId) == 0;
        }

        return false;
    }

    /**
     * Is logged in session exist
     */
    private function _isLoggedInSessionMatched()
    {
        if( isset($_SESSION[$this->_frontEnd]) ) {
            return strcmp($_SESSION[$this->_frontEnd], $this->_sessionId) == 0;
        }

        return false;
    }

    /**
     * @return mixed|string
     * @throws Zend_Controller_Request_Exception
     */
    public function getLoggedInUserName()
    {
        if( $this->isUserLoggedIn() ) {
            return $_SESSION['username'] ;
        }

        return '';
    }

    /**
     * @return Login_Model_Session
     * @throws Zend_Controller_Request_Exception
     * @throws Zend_Controller_Response_Exception
     */
    public function destroyUserSession()
    {
        $this->_getCookie()->delete($this->_frontEnd);

        if( isset($_SESSION[$this->_frontEnd]) ) {
            unset($_SESSION[$this->_frontEnd]);
        }

        if(isset($_SESSION['core'])) {
            unset($_SESSION['core']);
        }

        if(isset($_SESSION['username'])) {
            unset($_SESSION['username']);
        }

        if(isset($_SESSION['userId'])) {
            unset($_SESSION['userId']);
        }

        session_destroy();

        return $this;
    }

}//End of class