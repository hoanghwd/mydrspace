<?php
/**
 * Core Store Resource Model
 *
 * @package     Core 
 */
class Core_Model_Resource_Store extends Core_Model_Resource_Db_Abstract
{
    /**
     * Define main table and primary key
     *
     */
    protected function _construct()
    {
        $this->_init('core/store', 'store_id');
    }

    /**
     * Retrieve select object for load object data
     *
     * @param string $field
     * @param mixed $value
     * @param Core_Model_Abstract $object
     * @return Varien_Db_Select
     */
    protected function _getLoadSelect($field, $value, $object)
    {
        $select = parent::_getLoadSelect($field, $value, $object);
        $select->order('sort_order');

        return $select;
    }

}//End of class