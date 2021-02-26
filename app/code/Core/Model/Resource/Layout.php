<?php

class Core_Model_Resource_Layout extends Core_Model_Resource_Db_Abstract
{
    /**
     * Define main table
     *
     */
    protected function _construct()
    {
        $this->_init('core/layout_update', 'layout_update_id');
    }

    /**
     * Retrieve layout updates by handle
     * @param $handle
     * @param array $params
     * @return string
     * @throws Exception
     */
    public function fetchUpdatesByHandle($handle, $params = array())
    {

        $bind = array(
            'area'      => Virtual::getSingleton('core/design_package')->getArea(),
            'package'   => Virtual::getSingleton('core/design_package')->getPackageName(),
            'theme'     => Virtual::getSingleton('core/design_package')->getTheme('layout')
        );

        foreach ($params as $key => $value) {
            if (isset($bind[$key])) {
                $bind[$key] = $value;
            }
        }
        $bind['layout_update_handle'] = $handle;
        $result = '';

        $readAdapter = $this->_getReadAdapter();
        if ($readAdapter) {
            $select = $readAdapter->select()
                        ->from(array('layout_update' => $this->getMainTable()), array('xml'))
                        ->join(array('link'=>$this->getTable('core/layout_link')),
                            'link.layout_update_id=layout_update.layout_update_id',
                            '')
                        ->where('link.area = :area')
                        ->where('link.package = :package')
                        ->where('link.theme = :theme')
                        ->where('layout_update.handle = :layout_update_handle')
                        ->order('layout_update.sort_order ' . Varien_Db_Select::SQL_ASC);

            $result = join('', $readAdapter->fetchCol($select, $bind));
        }

        return $result;
    }

}//End of class