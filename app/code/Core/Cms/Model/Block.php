<?php

/**
 * CMS block model
 *
 * @method Core_Cms_Model_Resource_Block _getResource()
 * @method Core_Cms_Model_Resource_Block getResource()
 * @method string getTitle()
 * @method Core_Cms_Model_Block setTitle(string $value)
 * @method string getIdentifier()
 * @method Core_Cms_Model_Block setIdentifier(string $value)
 * @method string getContent()
 * @method Core_Cms_Model_Block setContent(string $value)
 * @method string getCreationTime()
 * @method Core_Cms_Model_Block setCreationTime(string $value)
 * @method string getUpdateTime()
 * @method Core_Cms_Model_Block setUpdateTime(string $value)
 * @method int getIsActive()
 * @method Core_Cms_Model_Block setIsActive(int $value)
 *
 * @category    Core
 * @package     Core_Cms
 */

class Core_Cms_Model_Block extends Core_Model_Abstract
{
    protected function _construct()
    {
        $this->_init('cms/block');
    }

    /**
     * @param $blockIdentifier
     * @return Core_Cms_Model_Block
     * @throws Zend_Db_Adapter_Exception
     */
    public function loadBlockByIdentifier($blockIdentifier)
    {
        $this->setData($this->getResource()->loadBlockByIdentifier($blockIdentifier));

        return $this;
    }

}//End of class