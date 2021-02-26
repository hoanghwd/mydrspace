<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

class Core_Page_Block_Html_Footer extends Core_Block_Template 
{
     protected $_copyright;
    
    /**
     * 
     * @param type $copyright
     * @return Core_Page_Block_Html_Footer
     */
     public function setCopyright($copyright)
    {
        $this->_copyright = $copyright;
        
        return $this;
    }

    /**
     * @return string|null
     * @throws Zend_Controller_Request_Exception
     * @throws Zend_Controller_Response_Exception
     */
    public function getCopyright()
    {
        if (!$this->_copyright) {
            $this->_copyright = Virtual::getStoreConfig('design/footer/copyright');
        }

        return $this->_copyright;
    }

    /**
     * @return bool
     * @throws Zend_Controller_Request_Exception
     */
    public function isLoggedIn()
    {
        return ($this->_getLoginSession()->isUserLoggedIn()) && isset($_SESSION['userId']);
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
