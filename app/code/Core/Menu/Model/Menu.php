<?php

class Core_Menu_Model_Menu extends Core_Model_Abstract
{
    /**
     *
     */
    protected function _construct()
    {
        $this->_init('menu/menu');
    }

    /**
     * @param $menuId
     * @return Core_Menu_Model_Menu
     * @throws Exception
     */
    public function loadById($menuId)
    {
        $this->setData($this->getResource()->loadById($menuId));
        
        return $this;
    }

    /**
     * @return Core_Menu_Model_Resource_Menu_Collection
     * @throws Exception
     */
    public function getCollection()
    {
        return Virtual::getResourceModel('menu/menu_collection');
    }

    /**
     * Get custom collection
     * @return mixed
     * @throws Exception
     */
    public function getCustomCollection()
    {
        return $this->getResource()->getCustomCollection();
    }

}//End of class