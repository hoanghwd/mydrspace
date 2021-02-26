<?php


/**
 * Page layout helper
 *
 * @category   Core
 * @package    Core_Page
 */
class Core_Page_Helper_Layout extends Core_Helper_Abstract
{
    /**
     * Apply page layout handle
     *
     * @param string $pageLayout
     * @return Core_Page_Helper_Layout
     */
    public function applyHandle($pageLayout)
    {
        $pageLayout = $this->_getConfig()->getPageLayout($pageLayout);

        if (!$pageLayout) {
            return $this;
        }

        $this->getLayout()
             ->getUpdate()
             ->addHandle($pageLayout->getLayoutHandle());

        return $this;
    }


    /**
     * Apply page layout template
     * (for old design packages)
     *
     * @param string $pageLayout
     * @return Core_Page_Helper_Layout
     */
    public function applyTemplate($pageLayout = null)
    {
        if ($pageLayout === null) {
            $pageLayout = $this->getCurrentPageLayout();
        } else {
            $pageLayout = $this->_getConfig()->getPageLayout($pageLayout);
        }

        if (!$pageLayout) {
            return $this;
        }

        if ($this->getLayout()->getBlock('root') &&
            !$this->getLayout()->getBlock('root')->getIsHandle()) {
            // If not applied handle
            $this->getLayout()
                ->getBlock('root')
                ->setTemplate($pageLayout->getTemplate());
        }

        return $this;
    }

    /**
     * Retrieve page config
     *
     * @return Core_Page_Model_Config
     */
    protected function _getConfig()
    {
        return Virtual::getSingleton('page/config');
    }

}//End of class