<?php


/**
 * CMS block model
 *
 * @category    Core
 * @package     Core_Cms
 */
class Core_Cms_Model_Resource_Block extends Core_Model_Resource_Db_Abstract
{
    /**
     * Initialize resource model
     *
     */
    protected function _construct()
    {
        $this->_init('cms/block', 'block_id');
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
     * @param $blockIdentifier
     * @return array
     * @throws Zend_Db_Adapter_Exception
     */
    public function loadBlockByIdentifier($blockIdentifier)
    {
        $adapter = $this->_getReadAdapter();
        $select = $adapter->select()
                          ->from($this->getMainTable())
                          ->where('identifier=:identifier');

        $binds = array('identifier' => $blockIdentifier);

        return $adapter->fetchRow($select, $binds);
    }



}//End of class