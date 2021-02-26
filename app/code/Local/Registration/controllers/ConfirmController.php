<?php
class Registration_ConfirmController extends Core_Controller_Front_Action
{
    /**
     *
     */
    public function indexAction()
    {
        $this->loadLayout();
        $this->renderLayout();
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
