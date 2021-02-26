<?php
class Login_Model_Password extends Core_Model_Abstract
{
    const TEMP_PASSWORD_LEN = 12;

    const MAX_SEQUENCE = 2;

    protected $_username;

    protected $_mailSent;

    protected $_isSuccess;

    protected $_errorMsg;

    /**
     * Initialize user model
     */
    protected function _construct()
    {
        $this->_init('login/password');
    }

    /**
     * @param $username
     * @return Login_Model_Password
     */
    public function init($username)
    {
        $this->_username = $username;
        $this->_mailSent = false;

        return $this;
    }

    /**
     * @return Login_Model_Password
     * @throws Exception
     */
    public function forgotPassword()
    {
        $userModel = $this->_getUserModel()->loadByUsername($this->_username);
        $userProfile = $this->_getProfileModel()->getFullProfile($userModel->getId());

        $randomNumber = $this->_getHelper()->getRandomString(Login_Model_Password::TEMP_PASSWORD_LEN);
        $generatePasswordHash = $this->_generateRandomPassword($randomNumber);
        $this->_getUserModel()->forgotPassword($this->_username, $generatePasswordHash);

        $forgotPasswordTemplate = ($this->_getAbsEmailTemplateDir()).DS.'forgotpassword.html';
        if( file_exists($forgotPasswordTemplate) ) {
            $fileContent = $this->_prepareForgotEmailContent($forgotPasswordTemplate, $randomNumber, $userProfile);
            $this->_mailSent = $this->_getMailModel()
                                    ->sendMail($userProfile['email'],'Password reset', $fileContent)
                                    ->isSuccess();
        }

        return $this;
    }

    /**
     * @param $username
     * @return mixed
     * @throws Exception
     */
    public function maskEmail($username)
    {
        $userModel = $this->_getUserModel()->loadByUsername($username);
        $userProfile = $this->_getProfileModel()->getFullProfile($userModel->getId());

        return  Virtual::helper('core/string')->maskEmail($userProfile['email']);
    }

    /**
     * @param $forgotPasswordTemplate
     * @param $randomNumber
     * @param $userProfile
     * @return false|string|string[]
     * @throws Zend_Controller_Request_Exception
     * @throws Zend_Controller_Response_Exception
     */
    private function _prepareForgotEmailContent($forgotPasswordTemplate, $randomNumber, $userProfile)
    {
        $fileContent = file_get_contents($forgotPasswordTemplate);
        $target = array(
            "{{name}}",
            '{{base-url}}',
            "{{img-url}}",
            "{{temporarypassword}}"
        );
        $replaceBy = array(
            ($userProfile['first_name']).' '.$userProfile['last_name'],
            Virtual::getBaseUrl(),
            Virtual::getImgUrl(),
            $randomNumber
        );

        return str_replace($target, $replaceBy, $fileContent);
    }

    /**
     * @return string
     */
    private function _getAbsEmailTemplateDir()
    {
        return (Virtual::getAbsModuleTemplate('login')).DS.'email';
    }

    /**
     * @return mixed
     */
    public function isMailSent()
    {
        return $this->_mailSent;
    }

    /**
     * Generate random hash password
     * @param $randomNumber
     * @return string
     */
    private function _generateRandomPassword($randomNumber)
    {
        return $this->_getHelper()->getEncryptor()->generatePassword($randomNumber);
    }

    /**
     * @return Profile_Model_User
     * @throws Exception
     */
    private function _getProfileModel()
    {
        return Virtual::getModel('profile/user');
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
     * @return Core_Mail_Model_Send
     * @throws Exception
     */
    private function _getMailModel()
    {
        return Virtual::getModel('mail/send');
    }

    /**
     * @return Core_Model_Encryption
     */
    private function _getEncryptor()
    {
        return $this->_getHelper()->getEncryptor();
    }

    /**
     * @return Password_Model_Evaluate
     * @throws Exception
     */
    private function _getPasswordModel()
    {
        return Virtual::getModel('password/evaluate');
    }

    /**
     * @return Core_Helper_Data|null
     */
    private function _getHelper()
    {
        return Virtual::helper('core');
    }

    /**
     * @param $params
     * @return Login_Model_Password
     * @throws Zend_Controller_Request_Exception
     * @throws Zend_Controller_Response_Exception
     */
    public function resetPassword($params)
    {
        $this->_errorMsg = $this->_validatePasswords($params);
        $this->_isSuccess = (sizeof($this->_errorMsg) == 0);

        if( $this->_isSuccess ) {
            $this->_updatePassword($params);
        }

        return $this;
    }

    /**
     * @param $params
     * @return array
     * @throws Zend_Controller_Request_Exception
     * @throws Zend_Controller_Response_Exception
     */
    private function _validatePasswords($params)
    {
        $newPassword = trim($params['password']);
        $passwordRetype = trim($params['passwordRetype']);

        $errorMsg = $this->_evaluatePassword($newPassword, 'error-tPassword');
        if( !$this->_isPasswordsMatched($newPassword, $passwordRetype) ) {
            $errorMsg['error-tPasswordRetype'] = 'Passwords do not match!';
        }

        return $errorMsg;
    }

    /**
     * @param $password
     * @param $key
     * @return array
     * @throws Zend_Controller_Request_Exception
     * @throws Zend_Controller_Response_Exception
     */
    private function _evaluatePassword($password, $key)
    {
        $errorMsg = array();
        $passwordModel = $this->_getPasswordModel()->setPassword($password);

        if( !$passwordModel->isPasswordComplied() ) {
            $errorMsg[$key] = $passwordModel->passwordRules();
        }
        elseif( $passwordModel->checkPasswordSequence(self::MAX_SEQUENCE) ) {
            $errorMsg[$key] = "Sequence is not allowed!";
        }
        elseif( $this->_isPastPassword($password) ) {
            $errorMsg[$key] = "Sorry, you cannot reuse the old password!";
        }

        return $errorMsg;
    }

    /**
     * @param $password
     * @return bool
     * @throws Exception
     */
    private function _isPastPassword($password)
    {
        $passwordHash = $this->_getHelper()->getEncryptor()->generatePassword($password);
        $dbPasswordHash = $this->_getPasswordHashByHash($passwordHash);

        return (strcmp($passwordHash, $dbPasswordHash) == 0);
    }

    /**
     * @param $passwordHash
     * @return mixed
     * @throws Exception
     */
    private function _getPasswordHashByHash($passwordHash)
    {
        $passwordHashArray = $this->getResource()->getPasswordHashByHash($passwordHash);

        if( isset($passwordHashArray['password_hash']) &&
            strcmp($passwordHashArray['user_name'], $_SESSION['username']) == 0
        ) {
            return $passwordHashArray['password_hash'];
        }

        return '';
    }

    /**
     * @param $newPassword
     * @param $passwordRetype
     * @return int|lt
     */
    private function _isPasswordsMatched($newPassword, $passwordRetype)
    {
        return strcmp($newPassword, $passwordRetype) == 0;
    }

    /**
     * @param $params
     * @throws Exception
     */
    private function _updatePassword($params)
    {
        $newPassword = trim($params['password']);
        $this->_updateLoginPasswordHistory($newPassword);
        $this->_updateLoginUserPassword($newPassword);
    }

    /**
     * @param $newPassword
     * @throws Exception
     */
    private function _updateLoginPasswordHistory($newPassword)
    {
        /**
         * Update login_password table
         */
        $newPasswordHash = $this->_getHelper()->getEncryptor()->generatePassword($newPassword);
        $this->getResource()->updatePassword($_SESSION['username'], $newPasswordHash);
    }

    /**
     * @param $newPassword
     * @throws Exception
     */
    private function _updateLoginUserPassword($newPassword)
    {
        $newPasswordHash = $this->_getHelper()->getEncryptor()->generatePassword($newPassword);
        $this->_getUserModel()->updatePassword($_SESSION['username'], $newPasswordHash);
    }

    /**
     * @return mixed
     */
    public function isSuccess()
    {
        return $this->_isSuccess;
    }

    /**
     * @return mixed
     */
    public function getErrorMsg()
    {
        return $this->_errorMsg;
    }

}//End of class