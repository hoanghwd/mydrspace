<?php

class Login_Block_Form_Resetpassword extends Core_Block_Template
{
    /**
     * @return string
     * @throws Zend_Controller_Request_Exception
     * @throws Zend_Controller_Response_Exception
     */
    public function getAuthUrl()
    {
        return $this->getBaseUrl() . DS . 'login' . DS . 'resetpasswordajax';
    }

    /**
     * @return string
     * @throws Zend_Controller_Request_Exception
     * @throws Zend_Controller_Response_Exception
     */
    public function getTooltipsUrl()
    {
        return Virtual::getTooltipsUrl('login');
    }

}//End of class