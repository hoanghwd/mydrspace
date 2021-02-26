<?php
class Login_Model_Email extends Core_Model_Abstract
{
    private $_email;

    private $_errorMsg;

    private $_mailSent;

    const RECORD = 'MX';


    /**
     * @param $email
     * @return Login_Model_Email
     */
    public function init($email)
    {
        $this->_email = $email;
        $this->_errorMsg = '';
        $this->_mailSent = false;

        return $this;
    }

    /**
     * @return Login_Model_Email
     * @throws Exception
     */
    public function sendEmailReminder()
    {
        if( $this->_isValidEmail() ) {
            $userProfileModel = $this->_getUserProfileModel()->loadByEmail($this->_email);
            $userProfile = $this->_getProfileModel()->getFullProfile($userProfileModel->getId());

            if( is_array($userProfile) && isset($userProfile['username']) ) {
                $this->_doSendEmailReminder($userProfile);
            }
            else {
                $this->_errorMsg = "Sorry, we have some difficulties, please try again!";
            }
        }

        return $this;
    }

    /**
     * @param $userProfile
     * @return Login_Model_Email
     */
    private function _doSendEmailReminder($userProfile)
    {
        $forgotUsernameTemplate = ($this->_getAbsEmailTemplateDir()).DS.'forgotusername.html';
        if( file_exists($forgotUsernameTemplate) ) {
            $fileContent = $this->_prepareForgotUserContent($forgotUsernameTemplate, $userProfile);
            $this->_mailSent = $this->_getMailModel()
                                    ->sendMail($userProfile['email'],'Your username', $fileContent)
                                    ->isSuccess();
        }

        return $this;
    }

    /**
     * @param $forgotUsernameTemplate
     * @param $userProfile
     * @return false|string|string[]
     * @throws Zend_Controller_Request_Exception
     * @throws Zend_Controller_Response_Exception
     */
    private function _prepareForgotUserContent($forgotUsernameTemplate, $userProfile)
    {
        $fileContent = file_get_contents($forgotUsernameTemplate);
        $target = array(
            "{{name}}",
            '{{base-url}}',
            "{{img-url}}",
            "{{username}}"
        );
        $replaceBy = array(
            ($userProfile['first_name']).' '.$userProfile['last_name'],
            Virtual::getBaseUrl(),
            Virtual::getImgUrl(),
            $userProfile['username']
        );

        return str_replace($target, $replaceBy, $fileContent);
    }

    /**
     * @return mixed
     */
    public function isMailSent()
    {
        return $this->_mailSent;
    }

    /**
     * @return mixed
     */
    public function getErrorMsg()
    {
        return $this->_errorMsg;
    }

    /**
     * @return bool
     */
    private function _isValidEmail()
    {
        if( !$this->_isValidDomain() ) {
            $this->_errorMsg = "Invalid email domain!";
        }
        else if( !filter_var($this->_email, FILTER_VALIDATE_EMAIL) ) {
            $this->_errorMsg = "Invalid email format!";
        }
        else if( !$this->_isEmailExist() ) {
            $this->_errorMsg = "Email address not match!";
        }

        return $this->_errorMsg == '';
    }

    /**
     * @return bool
     */
    private function _isValidDomain()
    {
        list($user, $domain) = explode('@', $this->_email);

        return checkdnsrr($domain, self::RECORD);
    }

    /**
     * @return mixed
     * @throws Exception
     */
    private function _isEmailExist()
    {
        return $this->_getUserProfileModel()->isEmailExist($this->_email);
    }

    /**
     * @return User_Model_Profile
     * @throws Exception
     */
    private function _getUserProfileModel()
    {
        return Virtual::getModel('user/profile');
    }

    /**
     * @return string
     */
    private function _getAbsEmailTemplateDir()
    {
        return (Virtual::getAbsModuleTemplate('login')).DS.'email';
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
     * @return Login_Model_User
     * @throws Exception
     */
    private function _getUserModel()
    {
        return Virtual::getModel('login/user');
    }

    /**
     * @return Profile_Model_User
     * @throws Exception
     */
    private function _getProfileModel()
    {
        return Virtual::getModel('profile/user');
    }

}//End of class