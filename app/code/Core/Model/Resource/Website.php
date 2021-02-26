<?php
/**
 * Core Website Resource Model
 *
 * @package     Core 
 */
class Core_Model_Resource_Website extends Core_Model_Resource_Db_Abstract
{
    /**
     * Define main table
     *
     */
    protected function _construct()
    {
        $this->_init('core/website', 'website_id');
    }


}//End of class