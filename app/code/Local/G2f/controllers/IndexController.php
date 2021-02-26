<?php
class G2f_IndexController extends Core_Controller_Front_Action
{
    /**
     * Index action
     */
    public function indexAction()
    {
        if( $this->_getLoginSessionModel()->isUserLoggedIn(true) ) {
            $this->loadLayout();
            $this->renderLayout();
        }
        else {
            $this->_redirectUrl(Virtual::getBaseUrl());
        }
    }

    /**
     * @return Login_Model_Session
     * @throws Exception
     */
    protected function _getLoginSessionModel()
    {
        return Virtual::getModel('login/session');
    }

}//End of class