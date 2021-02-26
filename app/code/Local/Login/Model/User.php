<?php
/**
 * Created by PhpStorm.
 * User: hdo
 * Date: 4/4/2019
 * Time: 5:02 PM
 */

class Login_Model_User extends Core_Model_Abstract
{
    const MAX_FAILED_LOGIN_PATH = 'web/login/failed_login';

    const FAILED_LOGIN_WAIT_PATH = 'web/login/failed_login_seconds';

    const PASSWORD_RESET_EXPIRE_MIN = 'web/password_reset/expire_in_minutes';

    private $_errorMessage;

    private $_failedLoginNums;


    /**
     * Initialize user model
     */
    protected function _construct()
    {
        $this->_errorMessage = '';
        $this->_failedLoginNums = 0;

        $this->_init('login/user');
    }

    /**
     * Load user by its username
     * @param $username
     * @return Login_Model_User
     * @throws Exception
     */
    public function loadByUsername($username)
    {
        $this->setData($this->getResource()->loadByUsername($username));

        return $this;
    }

    /**
     * Retrieve admin user collection
     * @return Login_Model_Resource_User_Collection
     * @throws Exception
     */
    public function getCollection()
    {
        return Virtual::getResourceModel('login/user_collection');
    }

    /**
     * Authenticate user name and password and save loaded record
     *
     * @param $username
     * @param $password
     * @param string $captcha
     * @return bool
     * @throws Zend_Controller_Request_Exception
     * @throws Zend_Controller_Response_Exception
     */
    private function _validateCredentials($username, $password, $captcha = "NA")
    {
        $isSuccess = false;
        $this->_errorMessage = 'The account sign-in was incorrect.';
        
        try {
            $this->loadByUsername($username);

            if( $this->getId() ) {
                $maxFailedLogin = $this->_getMaxLoginFailed();
                $logonWait = $this->_getLoginFailedWait();
                $secureImage = new Securimage();

                if ( $this->getIsActive() != 1 ) {
                    $this->_errorMessage = 'This account is inactive.';
                }
                else if( $this->getFailedLoginNums() > $maxFailedLogin && ($this->getUserLastFailed() > (time() - $logonWait)) ) {
                    $this->_failedLoginNums = $this->getFailedLoginNums();
                    $this->_errorMessage = 'You have entered an incorrect password '.$maxFailedLogin.' or more times already. Please wait '.$logonWait.' seconds to try again.';
                }
                else if( !$this->_validateHash($password) ) {
                    $this->getResource()->recordFailedLogin($this);
                }
                else if( $this->_validateHash($password) && $this->_isCurrentPasswordExpired($username) ) {
                    $this->_errorMessage = 'Sorry, password already expired!';
                }
                else if ($captcha != "NA" && $secureImage->check($captcha) == false) {
                    $this->_errorMessage = 'Incorrect security code entered.';
                }
                else {
                    $this->_errorMessage = '';
                    $isSuccess = true;
                }
            }//if get found userId
        }
        catch (Exception $e) {
            $this->unsetData();
            Virtual::throwException($e->getMessage());
            $this->_errorMessage = 'We encountered technical difficulties, please try later.';
        }
        
        if( $isSuccess && $this->_errorMessage == '' ) {
            $this->_getCoreSession()->start();
            $this->_setCustomerAsLoggedIn($username);
            $this->getResource()->resetFailedLogin($this);
        }
        else {
           $this->unsetData();
           Virtual::getModel('login/session')->destroyUserSession();  
        }

        return $isSuccess && ($this->_errorMessage == '');
    }

    /**
     * @param $username
     * @return bool
     * @throws Zend_Controller_Request_Exception
     * @throws Zend_Controller_Response_Exception
     */
    private function _isCurrentPasswordExpired($username)
    {
        $isExpired = true;
        $this->loadByUsername($username);

        $passwordResetRequire = $this->getPasswordResetRequire();
        if( $passwordResetRequire == 0 ) {
            $isExpired = false;
        }
        else {
            $passwordResetTime = $this->getPasswordResetTime();
            $resetTime = strtotime($passwordResetTime);
            $nowTime = strtotime(now());
            $diff = abs($nowTime - $resetTime);
            $minutesDiff = floor($diff / 60);
            $minutesThreshold = $this->_getPasswordExpireInMin();
            $isExpired = ($minutesDiff > $minutesThreshold);
        }

        return $isExpired;
    }

    /**
     * @param $username
     * @return Login_Model_User
     * @throws Exception
     */
    public function recordFailedG2f($username)
    {
        $this->loadByUsername($username);
        $this->getResource()->recordFailedG2f($this);

        return $this;
    }

    /**
     * @param $username
     * @param $passwordHash
     * @return Login_Model_User
     * @throws Exception
     */
    public function forgotPassword($username, $passwordHash)
    {
        $this->loadByUsername($username);
        $this->getResource()->forgotPassword($this, $passwordHash);

        return $this;
    }

    /**
     * @param $username
     * @param $passwordHash
     * @return Login_Model_User
     * @throws Exception
     */
    public function updatePassword($username, $passwordHash)
    {
        $this->loadByUsername($username);
        $this->getResource()->updatePassword($this, $passwordHash);

        return $this;
    }

    /**
     * @param $username
     * @return Login_Model_User
     * @throws Exception
     */
    public function resetAuthorizationCodeField($username)
    {
        $this->loadByUsername($username);
        $this->getResource()->resetAuthorizationCodeField($this);

        return $this;
    }

    /**
     * @param $username
     * @return mixed
     * @throws Exception
     */
    public function getG2fAuthFailedNum($username)
    {
        $this->loadByUsername($username);

        return $this->getAuthCodeFailedNums();
    }

    /**
     * @param $username
     * @return Login_Model_User
     * @throws Exception
     */
    public function resetG2fAuthNums($username)
    {
        $this->loadByUsername($username);
        $this->getResource()->resetG2fAuthNums($this);

        return $this;
    }

    /**
     * @return mixed
     */
    public function getLoginFailedCounts()
    {
        return $this->_failedLoginNums;
    }

    /**
     * @return string|null
     * @throws Zend_Controller_Request_Exception
     * @throws Zend_Controller_Response_Exception
     */
    protected function _getMaxLoginFailed()
    {
        return Virtual::getStoreConfig(self::MAX_FAILED_LOGIN_PATH);
    }

    /**
     * @return string|null
     * @throws Zend_Controller_Request_Exception
     * @throws Zend_Controller_Response_Exception
     */
    protected function _getLoginFailedWait()
    {
        return Virtual::getStoreConfig(self::FAILED_LOGIN_WAIT_PATH);
    }

    /**
     * @return string|null
     * @throws Zend_Controller_Request_Exception
     * @throws Zend_Controller_Response_Exception
     */
    protected function _getPasswordExpireInMin()
    {
        return Virtual::getStoreConfig(self::PASSWORD_RESET_EXPIRE_MIN);
    }

    /**
     * @param $username
     * @throws Zend_Controller_Request_Exception
     * @throws Zend_Controller_Response_Exception
     */
    private function _setCustomerAsLoggedIn($username)
    {
        $sessionId = $this->_getCoreSession()->getSessionId();
        $_SESSION['userId'] = $this->getId();
        $_SESSION['username'] = $username;
        $this->_getCookie()->set(Core_Model_Session_Abstract::COOKIE_FRONT_END, $sessionId, true);
    }

    /**
     * @return bool
     */
    public function isSuccess()
    {
        return ($this->_errorMessage == '');
    }

    /**
     * Return error message
     *
     * @return mixed
     */
    public function getErrorMessage()
    {
        return $this->_errorMessage;
    }

    /**
     * Validate hash
     *
     * @param $password
     * @return mixed
     */
    private function _validateHash($password)
    {
        return Virtual::helper('core')->validateHash($password, $this->getPassword());
    }

    /**
     * @param $username
     * @return mixed
     * @throws Exception
     */
    public function getLoginInfo($username)
    {
        return $this->getResource()->loadByUsername($username);
    }

    /**
     * @param $username
     * @param $password
     * @param string $captcha
     * @return Login_Model_User
     * @throws Zend_Controller_Request_Exception
     * @throws Zend_Controller_Response_Exception
     */
    public function login($username, $password, $captcha = "NA")
    {
        if ($this->_validateCredentials($username, $password, $captcha)) {
            $this->getResource()->recordLogin($this);
        }

        return $this;
    }

    /**
     * @param $username
     * @return bool
     * @throws Exception
     */
    public function isUsernameExist($username)
    {
        try {
            $this->loadByUsername($username);
            $userId = $this->getId();

            return is_numeric($userId) && $userId > 0;
        }
        catch (Exception $e) {
            $this->unsetData();
            Virtual::throwException($e->getMessage());
        }

        return false;
    }

    /**
     * Retrieve user identifier
     *
     * @return mixed
     */
    public function getId()
    {
        return $this->getUserId();
    }

    /**
     * Is account active
     * @param $username
     * @return mixed
     * @throws Exception
     */
    public function isAccountActive($username)
    {
        $this->loadByUsername($username);

        return $this->getIsActive();
    }

    /**
     * @param $username
     * @return mixed
     * @throws Exception
     */
    public function getGoogleAuthCode($username)
    {
        $this->loadByUsername($username);

        return $this->getAuthorizationCode();
    }

    /**
     * 
     * @return \Core_Model_Session
     */
    private function _getCoreSession()
    {
        return new Core_Model_Session();
    }
   
    /**
     * 
     * @return \Core_Model_Cookie
     */
    private function _getCookie()
    {
        return new Core_Model_Cookie();
    }

}//End of class