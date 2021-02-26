<?php
/**
 * Created by PhpStorm.
 * User: hdo
 * Date: 3/28/2019
 * Time: 11:42 AM
 */

class Core_Model_Resource_Config extends Core_Model_Resource_Db_Abstract
{
    /**
     * Define main table
     *
     */
    protected function _construct()
    {
        $this->_init('core/config_data', 'config_id');
    }

    /**
     * Load configuration values into xml config object
     * @param Core_Model_Config $xmlConfig
     * @param null $condition
     * @return $this
     * @throws Zend_Db_Adapter_Exception
     */
    public function loadToXml(Core_Model_Config $xmlConfig, $condition = null)
    {
        $read = $this->_getReadAdapter();
        if (!$read) {
            return $this;
        }

        $substFrom = array();
        $substTo   = array();

        // set default config values from database
        $select = $read->select()
                       ->from($this->getMainTable(), array('scope', 'scope_id', 'path', 'value'));
        if (!is_null($condition)) {
            $select->where($condition);
        }
        $rows = $read->fetchAll($select);
        // set default config values from database
        foreach ($rows as $r) {
            $value = str_replace($substFrom, $substTo, $r['value']);
            $xmlConfig->setNode('default/' . $r['path'], $value);
        }

        //Virtual::dump($rows);

        return $this;
    }

}//End of class