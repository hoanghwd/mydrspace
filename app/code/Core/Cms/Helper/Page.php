<?php


/**
 * CMS Page Helper
 *
 * @category   Cms
 * @package    Core_Cms

 */
class Core_Cms_Helper_Page extends Core_Helper_Abstract
{
    const XML_PATH_NO_ROUTE_PAGE        = 'web/default/cms_no_route';
    const XML_PATH_NO_COOKIES_PAGE      = 'web/default/cms_no_cookies';
    const XML_PATH_HOME_PAGE            = 'web/default/cms_home_page';

    /**
     * @param Core_Controller_Front_Action $action
     * @param null $pageId
     * @return bool
     */
    public function renderPage(Core_Controller_Front_Action $action, $pageId = null)
    {
        return $this->_renderPage($action, $pageId);
    }

    /**
     * @param Core_Controller_Varien_Action $action
     * @param null $pageId
     * @param bool $renderLayout
     * @return bool
     */
    protected function _renderPage(Core_Controller_Varien_Action $action, $pageId = null, $renderLayout = true)
    {
        $page = Virtual::getSingleton('cms/page');
        if (!$page->load($pageId)) {
            return false;
        }

        if (!$page->getId()) {
            return false;
        }

        $action->getLayout()->getUpdate()
               ->addHandle('default');

        $action->addActionLayoutHandles();

        if ($page->getRootTemplate()) {
            $handle = ($page->getCustomRootTemplate() && $page->getCustomRootTemplate() != 'empty' ) ?
                $page->getCustomRootTemplate() : $page->getRootTemplate();
            $action->getLayout()->helper('core_page/layout')->applyHandle($handle);
        }

        $action->loadLayoutUpdates();
        $layoutUpdate = ($page->getCustomLayoutUpdateXml() ) ?
            $page->getCustomLayoutUpdateXml() : $page->getLayoutUpdateXml();
        $action->getLayout()->getUpdate()->addUpdate($layoutUpdate);
        $action->generateLayoutXml()->generateLayoutBlocks();

        $contentHeadingBlock = $action->getLayout()->getBlock('page_content_heading');
        if ($contentHeadingBlock) {
            $contentHeading = $this->escapeHtml($page->getContentHeading());
            $contentHeadingBlock->setContentHeading($contentHeading);
        }

        if ($page->getRootTemplate()) {
            $action->getLayout()->helper('core_page/layout')
                   ->applyTemplate($page->getRootTemplate());
        }

        if ($renderLayout) {
            $action->renderLayout();
        }

        return true;
    }

}//End of class