<?php

class Cmp_Block_Cmp extends Core_Block_Template
{
    protected $_pageId;

    /**
     * Cmp_Block_Cmp constructor.
     * @param array $args
     */
    function __construct(array $args = array())
    {
        parent::__construct($args);
        $this->_pageId = '';
    }

    /**
     * @return false
     * @throws Zend_Controller_Request_Exception
     * @throws Zend_Controller_Response_Exception
     */
    protected function render()
    {
        $requestPage = $this->_getRequestPage();

        if( $this->_verifyPage($requestPage) ) {
            $page = $this->_getCmsPageModel()->load($this->_pageId);
            echo $page->getContent();

            return true;
        }

        return false;
    }

    /**
     * Get page identifier
     *
     * @param $identifier
     * @throws Exception
     */
    private function _verifyPage($identifier)
    {
        $pageId = $this->_getCmsPageModel()->checkIdentifier($identifier);
        if( is_numeric($pageId) && $pageId > 0 ) {
            $this->_pageId = $pageId;
        }

        return is_numeric($pageId) && $pageId > 0;
    }

    /**
     * @param $pageId
     */
    protected function _setPageId($pageId)
    {
        $this->_pageId = $pageId;
    }

    /**
     * @return string|null
     * @throws Zend_Controller_Request_Exception
     * @throws Zend_Controller_Response_Exception
     */
    private function _getRequestPage()
    {
        $pathInfoArray = $this->_getPathInfoArray();
        $requestedPage = $pathInfoArray[sizeof($pathInfoArray) - 1];
        $requestedPage = $this->_getRealPage($requestedPage);
        if($requestedPage == '') {
            $requestedPage = Virtual::getStoreConfig(Core_Cms_Helper_Page::XML_PATH_HOME_PAGE);
        }

        $requestedPage = $this->_getHelper()->cleanString($requestedPage);

        return strtolower(trim($requestedPage));
    }

    /**
     * @param $page
     * @return mixed
     */
    private function _getRealPage($page)
    {
        $pageParts = explode('.', $page);
        if( is_array($pageParts) && sizeof($pageParts) > 1 ) {
            return $pageParts[0];
        }

        return $page;
    }

    /**
     * @return string
     * @throws Exception
     */
    private function _getPathInfoArray()
    {
        return  explode('/', $this->getRequest()->getPathInfo());
    }

    /**
     * @return Core_Cms_Model_Page
     * @throws Exception
     */
    private function _getCmsPageModel()
    {
        return Virtual::getModel('core_cms/page');
    }

    /**
     * @return Core_Helper_String
     * @throws Exception
     */
    private function _getHelper()
    {
        return Virtual::helper('core/string');
    }

}//End of class