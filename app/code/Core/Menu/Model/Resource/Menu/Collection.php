<?php

class Core_Menu_Model_Resource_Menu_Collection extends Core_Model_Resource_Db_Collection_Abstract
{
    /**
     * Define resource model
     *
     */
    protected function _construct()
    {
        $this->_init('menu/menu');
    }
}