<?php
/**
 * Html page block
 *
 * @package    Core_Page
 */
class Core_Page_Block_Html_Welcome extends Core_Block_Template
{
    
    private $_isLoggedIn;
    
    /**
     * 
     * @param array $args
     */
    public function __construct(array $args = array()) 
    {        
        parent::__construct($args);
        $this->_isLoggedIn = $this->_setIsLoggedIn();
    }

    /**
     * @return mixed|string|void|null
     * @throws Zend_Controller_Request_Exception
     * @throws Zend_Controller_Response_Exception
     */
    protected function _toHtml()
    {
        if (empty($this->_data['welcome'])) {
            if( $this->_isLoggedIn ) {
                $user = Virtual::getModel('profile/user')->load($_SESSION['userId']);
                $this->_data['welcome'] = 'Hi ' . $this->_helper('core')->escapeHtml($user->getFirstName());
            }
            else {
                $this->_data['welcome'] = Virtual::getStoreConfig('design/header/signin');
            }
        }

        return $this->_data['welcome'];
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
     * Is user logged in
     * @return type
     */
    protected function _setIsLoggedIn()
    {
        return ($this->_getLoginSession()->isUserLoggedIn()) && isset($_SESSION['userId']);
    }
    
    

}//End of class