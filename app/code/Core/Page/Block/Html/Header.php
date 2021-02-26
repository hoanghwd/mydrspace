<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

class Core_Page_Block_Html_Header extends Core_Block_Template 
{
    private $_isLoggedIn;
    
    
    /**
     *
     */
    public function _construct() 
    {
        $this->setTemplate('page/html/header.phtml');
        $this->_isLoggedIn = $this->_setIsLoggedIn();
    }

    public function getLogoSrc()
    {
        if (empty($this->_data['logo_src'])) {
            $this->_data['logo_src'] = Virtual::getStoreConfig('design/header/logo_src');
        }
        return $this->getSkinUrl($this->_data['logo_src']);
    }

    /**
     * @return mixed|string|null
     * @throws Zend_Controller_Request_Exception
     * @throws Zend_Controller_Response_Exception
     */
    public function getLogoAlt()
    {
        if (empty($this->_data['logo_alt'])) {
            $this->_data['logo_alt'] = Virtual::getStoreConfig('design/header/logo_alt');
        }
        return $this->_data['logo_alt'];
    }

    /**
     * Check if current url is url for home page
     *
     * @return true
     */
    public function getIsHomePage()
    {
        return $this->getUrl('') == $this->getUrl('*/*/*', array('_current'=>true, '_use_rewrite'=>true));
    }

    /**
     * Generate url by route and parameters
     *
     * @param   string $route
     * @param   array $params
     * @return  string
     */
    public function getUrl($route = '', $params = array())
    {
        return $this->_getUrlModel()->getUrl($route, $params);
    }

    /**
     * Create and return url object
     * @return bool
     * @throws Exception
     */
    protected function _getUrlModel()
    {
        return Virtual::getModel($this->_getUrlModelClass());
    }

    /**
     * Returns url model class name
     *
     * @return string
     */
    protected function _getUrlModelClass()
    {
        return 'core/url';
    }    
    
    /**
     * @return Login_Model_Session
     * @throws Exception
     */
    protected function _getLoginSession()
    {
        return Virtual::getSingleton('login/session');
    }

    /**
     * @return bool
     * @throws Zend_Controller_Request_Exception
     */
    protected function _setIsLoggedIn()
    {
        return $this->_getLoginSession()->isUserLoggedIn();
    }
    
    /**
     * 
     * @return type
     */
    public function isLoggedIn()
    {
        return $this->_isLoggedIn;
    }    
    
    /**
     * Get location URL
     * @return type
     */
    public function getLocationUrl()
    {
        return ($this->getBaseUrl()).DS. 'location';
    }
    
    /**
     * Get contact us url
     * @return type
     */
    public function getContactUsUrl()
    {
        return ($this->getBaseUrl()).DS. 'contactus';
    }
    
    /**
     * Get sign in URL
     * @return type
     */
    public function getSignInUrl()
    {
        return ($this->getBaseUrl()).DS. 'login';
    }

    /**
     * @return bool|null
     * @throws Exception
     */
    private function _getUserProfile()
    {
        if( $this->_isLoggedIn ) {
            return Virtual::getModel('profile/user');
        }

        return NULL;        
    }

    /**
     * @return array|mixed|null
     * @throws Exception
     */
    public function getName()
    {
        if( $this->_isLoggedIn ) {
            $user = $this->_getUserProfile()->load($_SESSION['userId']);
            
            return $this->_helper('core')->escapeHtml($user->getFirstName());
        }
        
        return NULL;
    }

    /**
     * @return string
     * @throws Zend_Controller_Request_Exception
     * @throws Zend_Controller_Response_Exception
     */
    public function getProfileUrl()
    {
        return ($this->getBaseUrl()).DS.'profile';
    }

    /**
     * @return string
     * @throws Zend_Controller_Request_Exception
     * @throws Zend_Controller_Response_Exception
     */
    public function getLogOutUrl()
    {
        return ($this->getBaseUrl()).DS.'logout';
    }
    
}//End of class