<?php

/**
 * Top menu block
 *
 * @package     Core_Page
 */
class Core_Page_Block_Html_Topmenu extends Core_Block_Template
{
    private $_isLoggedIn;

    /**
     * Top menu data tree
     *
     * @var Varien_Data_Tree_Node
     */
    protected $_menu;

    /**
     * Current entity key
     *
     * @var string|int
     */
    protected $_currentEntityKey;

    /**
     * Init top menu tree structure and cache
     */
    public function _construct()
    {
        $this->_menu = new Varien_Data_Tree_Node(array(), 'root', new Varien_Data_Tree());
        $this->_isLoggedIn = $this->_setIsLoggedIn();
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
     * @return Login_Model_Session
     * @throws Exception
     */
    protected function _getLoginSession()
    {
        return Virtual::getSingleton('login/session');
    }

    /**
     * @return mixed
     */
    public function isLoggedIn()
    {
        return $this->_isLoggedIn;
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
     * Get top menu html
     *
     * @param string $outermostClass
     * @param string $childrenWrapClass
     * @return string
     */
    public function getHtml($outermostClass = '', $childrenWrapClass = '')
    {
        $html = '';
        $this->_menu->setOutermostClass($outermostClass);
        $this->_menu->setChildrenWrapClass($childrenWrapClass);

        if ($renderer = $this->getChild('megamenu.topnav.renderer')) {
            $renderer->setMenuTree($this->_menu)->setChildrenWrapClass($childrenWrapClass);
            $html = $renderer->toHtml();
        }
        else {
            //$html = "top menu";

        }

        return $html;
    }

    /**
     * @return string
     * @throws Zend_Controller_Request_Exception
     * @throws Zend_Controller_Response_Exception
     */
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
     * @return mixed|string|null
     * @throws Zend_Controller_Request_Exception
     * @throws Zend_Controller_Response_Exception
     */
    public function getSearchAlt()
    {
        if (empty($this->_data['search_alt'])) {
            $this->_data['search_alt'] = Virtual::getStoreConfig('design/header/search_alt');
        }
        return $this->_data['search_alt'];
    }


    /**
     * @return mixed|string|null
     * @throws Zend_Controller_Request_Exception
     * @throws Zend_Controller_Response_Exception
     */
    public function getSearchPlaceHolder()
    {
        if (empty($this->_data['search_place_holder'])) {
            $this->_data['search_place_holder'] = Virtual::getStoreConfig('design/header/search_place_holder');
        }
        return $this->_data['search_place_holder'];
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
     * @return string
     * @throws Zend_Controller_Request_Exception
     * @throws Zend_Controller_Response_Exception
     */
    public function getImgUrl()
    {
        return Virtual::getImgUrl();
    }

}//End of class