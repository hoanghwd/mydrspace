<?php

/**
 * Cms Page Model
 *
 * @method Core_Cms_Model_Resource_Page _getResource()
 * @method Core_Cms_Model_Resource_Page getResource()
 * @method string getTitle()
 * @method Core_Cms_Model_Page setTitle(string $value)
 * @method string getRootTemplate()
 * @method Core_Cms_Model_Page setRootTemplate(string $value)
 * @method string getMetaKeywords()
 * @method Core_Cms_Model_Page setMetaKeywords(string $value)
 * @method string getMetaDescription()
 * @method Core_Cms_Model_Page setMetaDescription(string $value)
 * @method string getIdentifier()
 * @method Core_Cms_Model_Page setIdentifier(string $value)
 * @method string getContentHeading()
 * @method Core_Cms_Model_Page setContentHeading(string $value)
 * @method string getContent()
 * @method Core_Cms_Model_Page setContent(string $value)
 * @method string getCreationTime()
 * @method Core_Cms_Model_Page setCreationTime(string $value)
 * @method string getUpdateTime()
 * @method Core_Cms_Model_Page setUpdateTime(string $value)
 * @method int getIsActive()
 * @method Core_Cms_Model_Page setIsActive(int $value)
 * @method int getSortOrder()
 * @method Core_Cms_Model_Page setSortOrder(int $value)
 * @method string getLayoutUpdateXml()
 * @method Core_Cms_Model_Page setLayoutUpdateXml(string $value)
 * @method string getCustomTheme()
 * @method Core_Cms_Model_Page setCustomTheme(string $value)
 * @method string getCustomRootTemplate()
 * @method Core_Cms_Model_Page setCustomRootTemplate(string $value)
 * @method string getCustomLayoutUpdateXml()
 * @method Core_Cms_Model_Page setCustomLayoutUpdateXml(string $value)
 * @method string getCustomThemeFrom()
 * @method Core_Cms_Model_Page setCustomThemeFrom(string $value)
 * @method string getCustomThemeTo()
 * @method Core_Cms_Model_Page setCustomThemeTo(string $value)
 *
 * @category    Core
 * @package     Core_Cms
 */

class Core_Cms_Model_Page extends Core_Model_Abstract
{
    const NOROUTE_PAGE_ID = 'no-route';

    /**
     * Page's Statuses
     */
    const STATUS_ENABLED = 1;
    const STATUS_DISABLED = 0;

    /**
     * Initialize resource model
     *
     */
    protected function _construct()
    {
        $this->_init('cms/page');
    }

    /**
     * @param $id
     * @param null $field
     * @return Core_Cms_Model_Page
     * @throws Core_Cms_Model_Page
     */
    public function load($id, $field=null)
    {
        if (is_null($id)) {
            return $this->noRoutePage();
        }
        return parent::load($id, $field);
    }

    /**
     * Load No-Route Page
     *
     * @return Core_Cms_Model_Page
     */
    public function noRoutePage()
    {
        return $this->load(self::NOROUTE_PAGE_ID, $this->getIdFieldName());
    }

    /**
     * Check if page identifier exist for specific store
     * return page id if page exists
     *
     * @param string $identifier
     * @return int
     */
    public function checkIdentifier($identifier)
    {
        return $this->_getResource()->checkIdentifier($identifier);
    }


}//End of class