<?php
class Core_Cms_Model_Resource_Page extends Core_Model_Resource_Db_Abstract
{
    /**
     * Initialize resource model
     *
     */
    protected function _construct()
    {
        $this->_init('cms/page', 'page_id');
    }

    /**
     * Load an object using 'identifier' field if there's no field specified and value is not numeric
     *
     * @param Core_Model_Abstract $object
     * @param mixed $value
     * @param string $field
     * @return Core_Cms_Model_Resource_Block
     */
    public function load(Core_Model_Abstract $object, $value, $field = null)
    {
        if (!is_numeric($value) && is_null($field)) {
            $field = 'identifier';
        }

        return parent::load($object, $value, $field);
    }

    /**
     * Retrieves cms page title from DB by passed id.
     *
     * @param $id
     * @return string
     * @throws Zend_Db_Adapter_Exception
     */
    public function getCmsPageTitleById($id)
    {
        $adapter = $this->_getReadAdapter();

        $select  = $adapter->select()
                           ->from($this->getMainTable(), 'title')
                           ->where('page_id = :page_id');

        $binds = array(
            'page_id' => (int) $id
        );

        return $adapter->fetchOne($select, $binds);
    }

    /**
     * Retrieves cms page identifier from DB by passed id.
     * @param $id
     * @return string
     * @throws Zend_Db_Adapter_Exception
     */
    public function getCmsPageIdentifierById($id)
    {
        $adapter = $this->_getReadAdapter();
        $select  = $adapter->select()
                           ->from($this->getMainTable(), 'identifier')
                           ->where('page_id = :page_id');

        $binds = array(
            'page_id' => (int) $id
        );

        return $adapter->fetchOne($select, $binds);
    }

    /**
     *  Check whether page identifier is valid
     *
     *  @param    Core_Model_Abstract $object
     *  @return   bool
     */
    protected function isValidPageIdentifier(Core_Model_Abstract $object)
    {
        return preg_match('/^[a-z0-9][a-z0-9_\/-]+(\.[a-z0-9_-]+)?$/', $object->getData('identifier'));
    }

    /**
     *  Check whether page identifier is numeric
     *
     * @date Wed Mar 26 18:12:28 EET 2008
     *
     * @param Core_Model_Abstract $object
     * @return bool
     */
    protected function isNumericPageIdentifier(Core_Model_Abstract $object)
    {
        return preg_match('/^[0-9]+$/', $object->getData('identifier'));
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
        $stores = array(Core_Model_App::ADMIN_STORE_ID, 1);
        $select = $this->_getLoadByIdentifierSelect($identifier, $stores, Core_Model_App::DEFAULT_STORE_ID);
        $select->reset(Zend_Db_Select::COLUMNS)
               ->columns('cp.page_id')
               ->limit(1);

        return $this->_getReadAdapter()->fetchOne($select);
    }

    /**
     * Retrieve load select with filter by identifier, store and activity
     *
     * @param string $identifier
     * @param int|array $store
     * @param int $isActive
     * @return Varien_Db_Select
     */
    protected function _getLoadByIdentifierSelect($identifier, $store, $isActive = null)
    {
        $select = $this->_getReadAdapter()->select()
            ->from(array('cp' => $this->getMainTable()))
            ->join(
                array('cps' => $this->getTable('cms/page_store')),
                'cp.page_id = cps.page_id',
                array())
            ->where('cp.identifier = ?', $identifier)
            ->where('cps.store_id IN (?)', $store);

        if (!is_null($isActive)) {
            $select->where('cp.is_active = ?', $isActive);
        }

        return $select;
    }


}//End of class