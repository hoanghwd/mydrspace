<?php
class Cmp_IndexController extends Core_Controller_Front_Action
{
    /**
     * Index action
     */
    public function indexAction()
    {
        $pageId = Virtual::getStoreConfig(Core_Cms_Helper_Page::XML_PATH_HOME_PAGE);

        if (!Virtual::helper('core_cms/page')->renderPage($this, $pageId)) {
            $this->_forward('defaultIndex');
        }
    }

}//End of class