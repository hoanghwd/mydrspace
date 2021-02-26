<?php
class Login_ResetpasswordController extends Core_Controller_Front_Action
{
    /**
     * @throws Zend_Controller_Request_Exception
     * @throws Zend_Controller_Response_Exception
     */
    public function indexAction()
    {
        if( $this->_getLoginSession()->isUserLoggedIn() ) {
            $this->loadLayout();
            $this->renderLayout();
        }
        else {
            $this->_getLoginSession()->destroyUserSession();
            $this->_redirectUrl(Virtual::getBaseUrl());
        }
    }

    /**
     * @return Login_Model_Session
     * @throws Exception
     */
    protected function _getLoginSession()
    {
        return Virtual::getSingleton('login/session');
    }

}//End of clas