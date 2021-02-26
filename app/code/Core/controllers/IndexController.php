<?php
class Core_IndexController extends Core_Controller_Front_Action {

    function indexAction()
    {
        $this->_forward('noRoute');
    }
}