<?php
class Core_Page_Model_Config
{
    const XML_PATH_PAGE_LAYOUTS = 'global/page/layouts';
    const XML_PATH_CMS_LAYOUTS = 'global/cms/layouts';

    /**
     * Available page layouts
     *
     * @var array
     */
    protected $_pageLayouts = null;


    /**
     * Initialize page layouts list
     *
     * @return Core_Page_Model_Config
     */
    protected function _initPageLayouts()
    {
        if ($this->_pageLayouts === null) {
            $this->_pageLayouts = array();
            $this->_appendPageLayouts(self::XML_PATH_CMS_LAYOUTS);
            $this->_appendPageLayouts(self::XML_PATH_PAGE_LAYOUTS);
        }
        return $this;
    }

    /**
     * Fill in $_pageLayouts by reading layouts from config
     *
     * @param string $xmlPath XML path to layouts root
     * @return Core_Page_Model_Config
     */
    protected function _appendPageLayouts($xmlPath)
    {
        if (!Virtual::getConfig()->getNode($xmlPath)) {
            return $this;
        }
        if (!is_array($this->_pageLayouts)) {
            $this->_pageLayouts = array();
        }
        foreach (Virtual::getConfig()->getNode($xmlPath)->children() as $layoutCode => $layoutConfig) {
            $this->_pageLayouts[$layoutCode] = new Varien_Object(array(
                'label'         => ((string)$layoutConfig->label),
                'code'          => $layoutCode,
                'template'      => (string)$layoutConfig->template,
                'layout_handle' => (string)$layoutConfig->layout_handle,
                'is_default'    => (int)$layoutConfig->is_default,
            ));

            //Virtual::dump( $this->_pageLayouts[$layoutCode]);
        }
        return $this;
    }

    /**
     * Retrieve available page layouts
     *
     * @return array
     */
    public function getPageLayouts()
    {
        $this->_initPageLayouts();
        return $this->_pageLayouts;
    }

    /**
     * Retrieve page layout by code
     *
     * @param string $layoutCode
     * @return Varien_Object|boolean
     */
    public function getPageLayout($layoutCode)
    {
        $this->_initPageLayouts();

        if (isset($this->_pageLayouts[$layoutCode])) {
            return $this->_pageLayouts[$layoutCode];
        }

        return false;
    }


}//End of class