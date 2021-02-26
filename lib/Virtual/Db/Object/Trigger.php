<?php

class Virtual_Db_Object_Trigger extends Virtual_Db_Object implements Virtual_Db_Object_Interface
{
    /**
     * @var string
     */
    protected $_dbType  = 'TRIGGER';

    /**
     * @var array
     */
    protected $_data = array();

    /**
     * @return bool
     */
    public function isExists()
    {
        if (!isset($this->_data['triggers'][$this->_schemaName])) {
            $this->describe();
        }

        if (isset($this->_data['triggers'][$this->_schemaName][$this->_objectName])) {
            return true;
        }

        return false;
    }

    public function describe()
    {
        $columns = array(
            'TRIGGER_NAME',
            'EVENT_MANIPULATION',
            'EVENT_OBJECT_CATALOG',
            'EVENT_OBJECT_SCHEMA',
            'EVENT_OBJECT_TABLE',
            'ACTION_ORDER',
            'ACTION_CONDITION',
            'ACTION_STATEMENT',
            'ACTION_ORIENTATION',
            'ACTION_TIMING',
            'ACTION_REFERENCE_OLD_TABLE',
            'ACTION_REFERENCE_NEW_TABLE',
            'ACTION_REFERENCE_OLD_ROW',
            'ACTION_REFERENCE_NEW_ROW',
            'CREATED',
        );
        $sql = 'SELECT ' . implode(', ', $columns)
            . ' FROM ' . $this->_adapter->quoteIdentifier(array('INFORMATION_SCHEMA','TRIGGERS'))
            . ' WHERE ';

        $schema = $this->getSchemaName();
        if ($schema) {
            $sql .= $this->_adapter->quoteIdentifier('EVENT_OBJECT_SCHEMA')
                . ' = ' . $this->_adapter->quote($schema);
        } else {
            $sql .= $this->_adapter->quoteIdentifier('EVENT_OBJECT_SCHEMA')
                . ' != ' . $this->_adapter->quote('INFORMATION_SCHEMA');
        }

        $results = $this->_adapter->query($sql);

        $data = array();
        foreach ($results as $row) {
            $row = array_change_key_case($row, CASE_LOWER);
            if (null !== $row['created']) {
                $row['created'] = new DateTime($row['created']);
            }
            $data[$row['trigger_name']] = $row;
        }
        $this->_data['triggers'][$schema] = $data;

        return $data;
    }
}

