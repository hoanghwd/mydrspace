<?php
/**
 * Created by PhpStorm.
 * User: hdo
 * Date: 4/10/2019
 * Time: 3:57 PM
 */

class Logout_IndexController extends Core_Controller_Front_Action
{
    /**
     * Index action
     */
    public function indexAction()
    {
        Virtual::getModel('login/session')->destroyUserSession();

        $this->_redirectUrl(Virtual::getBaseUrl());
    }

}//End of class