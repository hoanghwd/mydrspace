<?php

class Core_Menu_Model_Resource_Menu extends Core_Model_Resource_Db_Abstract
{
    /**
     * @throws Exception
     */
    protected function _construct()
    {
        $this->_init('menu/menu', 'menu_id');
    }

    /**
     * @param $menuId
     * @return array
     * @throws Zend_Db_Adapter_Exception
     */

    public function loadById($menuId)
    {
        $adapter = $this->_getReadAdapter();
        $select = $adapter->select()
                          ->from($this->getMainTable())
                          ->where('menu_id=:menu_id');

        $binds = array('menu_id' => $menuId);

        return $adapter->fetchRow($select, $binds);
    }

    /**
     * @return array
     * @throws Zend_Db_Adapter_Exception
     */
    public function getCustomCollection()
    {
        $adapter = $this->_getReadAdapter();
        $select = $adapter->select()
                          ->from( array('MENU' => $this->getMainTable()) )
                          ->join(array('CMS' => 'cms_block'), 'MENU.content_id = CMS.block_id');

        return $adapter->fetchAll($select);
    }

}//End of class