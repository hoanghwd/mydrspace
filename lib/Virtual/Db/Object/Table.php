<?php

class Virtual_Db_Object_Table extends Virtual_Db_Object implements Virtual_Db_Object_Interface
{
    /**
     * @var string
     */
    protected $_dbType  = 'TABLE';

    /**
     * Check is object exists
     *
     * @return bool
     */
    public function isExists()
    {
        return $this->_adapter->isTableExists($this->_objectName, $this->_schemaName);
    }

    /**
     * Create a new table from source
     *
     * @param $source Zend_Db_Select
     * @return Virtual_Db_Object_Table
     */
    public function createFromSource(Zend_Db_Select $source)
    {
        $this->_adapter->query(
            'CREATE ' . $this->getDbType() . ' ' . $this->_objectName . ' AS ' . $source
        );
        return $this;
    }

    /**
     * Describe Table
     *
     * @return array
     */
    public function describe()
    {
        return $this->_adapter->describeTable($this->_objectName, $this->_schemaName);
    }
}
