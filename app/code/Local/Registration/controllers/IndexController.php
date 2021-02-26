<?php
class Registration_IndexController extends Core_Controller_Front_Action
{
    /**
     * Index action
     */
    public function indexAction()
    {
        $this->loadLayout();
        $this->renderLayout();
    }

}//End of class