<?php

/**
 * Magento
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the Open Software License (OSL 3.0)
 * that is bundled with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * http://opensource.org/licenses/osl-3.0.php
 * If you did not receive a copy of the license and are unable to
 * obtain it through the world-wide-web, please send an email
 * to license@magento.com so we can send you a copy immediately.
 *
 * DISCLAIMER
 *
 * Do not edit or add to this file if you wish to upgrade Magento to newer
 * versions in the future. If you wish to customize Magento for your
 * needs please refer to http://www.magento.com for more information.
 *
 * @category    Mage
 * @package     Mage_Cms
 * @copyright  Copyright (c) 2006-2018 Magento, Inc. (http://www.magento.com)
 * @license    http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */


/**
 * CMS Data helper
 *
 * @category   Core
 * @package    Core_Cms
 */
class Core_Cms_Helper_Data extends Core_Helper_Abstract
{
    const XML_NODE_PAGE_TEMPLATE_FILTER     = 'global/cms/page/tempate_filter';
    const XML_NODE_BLOCK_TEMPLATE_FILTER    = 'global/cms/block/tempate_filter';

    /**
     * Retrieve Template processor for Page Content
     *
     * @return Varien_Filter_Template
     * @throws Exception
     */
    public function getPageTemplateProcessor()
    {
        $model = (string)Virtual::getConfig()->getNode(self::XML_NODE_PAGE_TEMPLATE_FILTER);

        return Virtual::getModel($model);
    }

    /**
     * Retrieve Template processor for Block Content
     *
     * @return Varien_Filter_Template
     */
    public function getBlockTemplateProcessor()
    {
        $model = (string)Virtual::getConfig()->getNode(self::XML_NODE_BLOCK_TEMPLATE_FILTER);

        return Virtual::getModel($model);
    }

}//End of class