<?php
class Profile_Block_Form_Profile extends Core_Block_Template
{
    /**
     * @return string
     * @throws Zend_Controller_Request_Exception
     * @throws Zend_Controller_Response_Exception
     */
    public function getAuthUrl()
    {
        return $this->getBaseUrl().DS.'profile'.DS.'ajax';
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