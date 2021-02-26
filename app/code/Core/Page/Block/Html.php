<?php

class Core_Page_Block_Html extends Core_Block_Template
{
    protected $_urls = array();

    protected $_title = '';

    /**
     * Core_Page_Block_Html constructor.
     * @throws Zend_Controller_Request_Exception
     */
    public function __construct()
    {
        parent::__construct();

        $this->_urls = array(
            'base'      => Virtual::getBaseUrl('web'),
            'baseSecure'=> Virtual::getBaseUrl('web', true),
            'current'   => $this->getRequest()->getRequestUri()
        );

        $action = Virtual::app()->getFrontController()->getAction();
        if ($action) {
            $this->addBodyClass($action->getFullActionName('-'));
        }
    }

    /**
     * @return mixed|string
     */
    public function getBaseUrl()
    {
        return $this->_urls['base'];
    }

    /**
     * @return mixed
     */
    public function getBaseSecureUrl()
    {
        return $this->_urls['baseSecure'];
    }

    /**
     * @return mixed
     */
    public function getCurrentUrl()
    {
        return $this->_urls['current'];
    }

    /**
     * @param $title
     * @return Core_Page_Block_Html
     */
    public function setHeaderTitle($title)
    {
        $this->_title = $title;

        return $this;
    }

    /**
     * @return string
     */
    public function getHeaderTitle()
    {
        return $this->_title;
    }

    /**
     * Add CSS class to page body tag
     *
     * @param string $className
     * @return Core_Page_Block_Html
     */
    public function addBodyClass($className)
    {
        $className = preg_replace('#[^a-z0-9]+#', '-', strtolower($className));
        $this->setBodyClass($this->getBodyClass() . ' ' . $className);

        return $this;
    }

    /**
     * @param $theme
     * @return Core_Page_Block_Html
     * @throws Exception
     */
    public function setTheme($theme)
    {
        $arr = explode('/', $theme);
        if (isset($arr[1])) {
            Virtual::getDesign()->setPackageName($arr[0])->setTheme($arr[1]);
        }
        else {
            Virtual::getDesign()->setTheme($theme);
        }

        return $this;
    }

    /**
     * @return mixed
     */
    public function getBodyClass()
    {
        return $this->_getData('body_class');
    }

}//End of class