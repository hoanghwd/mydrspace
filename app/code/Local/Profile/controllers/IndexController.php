<?php
class Profile_IndexController extends Core_Controller_Front_Action
{
    /**
     * Index action
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

}//End of class