<?php


/**
 * Core Website model
 *
 * @method Core_Model_Resource_Website _getResource()
 * @method Core_Model_Resource_Website getResource()
 * @method Core_Model_Website setCode(string $value)
 * @method string getName()
 * @method Core_Model_Website setName(string $value)
 * @method int getSortOrder()
 * @method Core_Model_Website setSortOrder(int $value)
 * @method Core_Model_Website setDefaultGroupId(int $value)
 * @method int getIsDefault()
 * @method Core_Model_Website setIsDefault(int $value)
 *
 * @package     Core
 */

class Core_Model_Website extends Core_Model_Abstract
{
    const ENTITY    = 'core_website';
    const CACHE_TAG = 'website';
    protected $_cacheTag = true;

    /**
     * @var string
     */
    protected $_eventPrefix = 'website';

    /**
     * @var string
     */
    protected $_eventObject = 'website';

    /**
     * Cache configuration array
     *
     * @var array
     */
    protected $_configCache = array();

    /**
     * Website Group Coleection array
     *
     * @var array
     */
    protected $_groups;

    /**
     * Website group ids array
     *
     * @var array
     */
    protected $_groupIds = array();

    /**
     * The number of groups in a website
     *
     * @var int
     */
    protected $_groupsCount;

    /**
     * Website Store collection array
     *
     * @var array
     */
    protected $_stores;

    /**
     * Website store ids array
     *
     * @var array
     */
    protected $_storeIds = array();

    /**
     * Website store codes array
     *
     * @var array
     */
    protected $_storeCodes = array();

    /**
     * The number of stores in a website
     *
     * @var int
     */
    protected $_storesCount = 0;

    /**
     * Website default group
     *
     * @var Core_Model_Store_Group
     */
    protected $_defaultGroup;

    /**
     * Website default store
     *
     * @var Core_Model_Store
     */
    protected $_defaultStore;

    /**
     * is can delete website
     *
     * @var bool
     */
    protected $_isCanDelete;

    /**
     * @var bool
     */
    private $_isReadOnly = false;

    /**
     * init model
     *
     */
    protected function _construct()
    {
        $this->_init('core/website');
    }

    /**
     * @param $id
     * @param null $field
     * @return Core_Model_Website
     * @throws Exception
     */
    public function load($id, $field = null)
    {
        if (!is_numeric($id) && is_null($field)) {
            $this->_getResource()->load($this, $id, 'code');

            return $this;
        }

        return parent::load($id, $field);
    }


}//End of class