<?php
class Core_Page_Block_Html_CookieNotice extends Core_Block_Template
{
    /**
     * Get content for cookie restriction block
     *
     * @return string
     */
    public function getCookieRestrictionBlockContent()
    {
        $blockIdentifier = Virtual::helper('core/cookie')->getCookieRestrictionNoticeCmsBlockIdentifier();
        $block = Virtual::getModel('core_cms/block')->load($blockIdentifier, 'identifier');

        $html = '';
        if ($block->getIsActive()) {
            $helper = Virtual::helper('core_cms');
            $processor = $helper->getBlockTemplateProcessor();
            $html = $processor->filter($block->getContent());
        }

        return $html;
    }

}//End of class