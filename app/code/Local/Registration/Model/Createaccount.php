<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

class Registration_Model_Createaccount extends Core_Model_Abstract
{
    const BUSINESS_TYPE = 1;

    const PATIENT_TYPE = 2;

    const NUMBER_SEQ = '1234567890';

    const RECORD = 'MX';

    const MAX_USER_SUG = 4;

    const FIELD_ARRAY = array(
        'tuserName'    => 'username',
        'tPassword'    => 'password',
        'tfName'       => 'First name',
        'tlName'       => "Last name",
        'temail'       => 'Email',
        'temailRetype' => "Email confirm",
        'tphone'       => "Phone number",
        'tLanguageSpoken' => "Spoken language"
    );

    private $_activationHash;

    private $_isSuccess;

    private $_mailSent;

    /**
     * Initialize user model
     */
    protected function _construct()
    {
        $this->_errorMessage = '';
        $this->_failedLoginNums = 0;
        $this->_isSuccess = false;
        $this->_mailSent = false;

        $this->_init('registration/createaccount');
    }

    /**
     * @param $username
     * @return Registration_Model_Createaccount
     * @throws Exception
     */
    public function loadByUsername($username)
    {
        $this->setData($this->getResource()->loadByUsername($username));

        return $this;
    }

    /**
     * @param $username
     * @return bool
     * @throws Exception
     */
    public function isUserNameExist($username)
    {
        $this->loadByUsername($username);

        return  ($this->getUserId() > 0);
    }

    /**
     * @param $username
     * @return array
     * @throws Exception
     */
    public function generateUsernameSuggestList($username)
    {
        $suggestionList = array();
        $i = 0;
        while( $i < self::MAX_USER_SUG ) {
            $suggestUserName = $username.($this->_getHelper()->getRandomString(3, self::NUMBER_SEQ));
            if( !$this->isUserNameExist($suggestUserName) ) {
                array_push($suggestionList, $suggestUserName);
                $i++;
            }
        }//while

        return $suggestionList;
    }

    /**
     * @param $params
     * @return Registration_Model_Createaccount
     * @throws Exception
     */
    public function createProfile($params)
    {
        $params['passwordHash'] = $this->_generatePasswordHash(trim($params['tPassword']));
        $params['roleId'] = ( strtolower($params['raccount']) === 'business') ? self::BUSINESS_TYPE : self::PATIENT_TYPE;

        $randomString = $this->_getHelper()->getRandomString();
        $this->_activationHash = $this->_getHelper()->getEncryptor()->hash($randomString);
        $params['activationHash'] = $this->_activationHash;

        $params['userIp'] = Virtual::helper('core/http')->getRemoteAddr();

        if( $this->_validateFields($params) ) {
            $userId = $this->getResource()->recordLoginUser($params);
            if( $userId > 0 ) {
                $params['userId'] = $userId;
                $profileId = $this->getResource()->recordUserProfile($params);
                $passwordId = $this->getResource()->recordLoginPassword($params);

                //If successfully created account
                if ( $profileId > 0 && $passwordId > 0 ) {
                    $params['activationLink'] = (Virtual::getBaseUrl()).DS.'registration'.DS.'activate?s='.$randomString.'&u='.$params['tuserName'];
                    $this->_doSendActivationLink($params);
                }//if
            }//if userId
        }//if

        return $this;
    }

    /**
     * @param $params
     * @return Registration_Model_Createaccount
     * @throws Zend_Controller_Request_Exception
     * @throws Zend_Controller_Response_Exception
     */
    private function _doSendActivationLink($params)
    {
        $activateLinkTemplate = ($this->_getAbsEmailTemplateDir()).DS.'accountcreated.html';
        if( file_exists($activateLinkTemplate) ) {
            $fileContent = $this->_prepareNewAccountContent($activateLinkTemplate, $params);
            $this->_mailSent = $this->_getMailModel()
                                    ->sendMail($params['temail'],'Your new account at '.APP_NAME, $fileContent)
                                    ->isSuccess();
        }

        return $this;
    }

    /**
     * @param $activateLinkTemplate
     * @param $params
     * @return false|string|string[]
     * @throws Zend_Controller_Request_Exception
     * @throws Zend_Controller_Response_Exception
     */
    private function _prepareNewAccountContent($activateLinkTemplate, $params)
    {
        $fileContent = file_get_contents($activateLinkTemplate);
        $target = array(
            "{{name}}",
            '{{base-url}}',
            "{{img-url}}",
            "{{activatelink}}"
        );

        $replaceBy = array(
            (trim($params['tfName'])).' '.trim($params['tlName']),
            Virtual::getBaseUrl(),
            Virtual::getImgUrl(),
            $params['activationLink']
        );

        return str_replace($target, $replaceBy, $fileContent);
    }

    /**
     * @param $password
     * @return string
     * @throws Zend_Controller_Request_Exception
     * @throws Zend_Controller_Response_Exception
     */
    public function evaluatePassword($password)
    {
        $errorMsg = '';
        $passwordModel = $this->_getPasswordModel()->setPassword($password);

        if( !$passwordModel->isPasswordComplied() ) {
            $errorMsg = $passwordModel->passwordRules();
        }
        elseif( $passwordModel->checkPasswordSequence(Login_Model_Password::MAX_SEQUENCE) ) {
            $errorMsg = "Sequence is not allowed!";
        }

        return $errorMsg;
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
     * @return string
     */
    private function _getAbsEmailTemplateDir()
    {
        return (Virtual::getAbsModuleTemplate('registration')).DS.'email';
    }

    /**
     * @param $params
     * @return bool
     */
    private function _validateFields($params)
    {
        return true;
    }

    /**
     * @return bool
     */
    public function isSuccess()
    {
        return $this->_isSuccess;
    }

    /**
     * @return mixed
     */
    public function isMailSent()
    {
        return $this->_mailSent;
    }

    /**
     * @param $password
     * @return string
     */
    private function _generatePasswordHash($password)
    {
        return $this->_getHelper()->getEncryptor()->generatePassword($password);
    }

    /**
     * @return Core_Helper_Data|null
     */
    private function _getHelper()
    {
        return Virtual::helper('core');
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
     * @param $email
     * @return string
     * @throws Exception
     */
    public function isValidEmail($email)
    {
        $errorMsg = '';

        if( !filter_var($email, FILTER_VALIDATE_EMAIL) ) {
            $errorMsg = "Invalid email format!";
        }
        else if( !$this->_isValidDomain($email) ) {
            $errorMsg = "Invalid email domain!";
        }
        else if( $this->_isEmailExist($email) ) {
            $errorMsg = "It looks like someone already registered this email!";
        }

        return $errorMsg;
    }

    /**
     * @param $email
     * @return bool
     */
    private function _isValidDomain($email)
    {
        list($user, $domain) = explode('@', $email);

        return checkdnsrr($domain, self::RECORD);
    }

    /**
     * @param $email
     * @return mixed
     * @throws Exception
     */
    private function _isEmailExist($email)
    {
        return $this->_getUserProfileModel()->isEmailExist($email);
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
     * @return mixed
     * @throws Exception
     */
    public function getSecurityQuestions()
    {
        return $this->getResource()->getSecurityQuestions();
    }

}//End of class