<?php
class Login_Block_Form_Forgotpassword extends Core_Block_Template
{
    /**
     * @return string
     * @throws Zend_Controller_Request_Exception
     * @throws Zend_Controller_Response_Exception
     */
    public function getAuthUrl()
    {
        return $this->getBaseUrl().DS.'login'.DS.'forgotpasswordajax';
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