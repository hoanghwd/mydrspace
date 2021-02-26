<?php
/**
 * Created by PhpStorm.
 * User: hdo
 * Date: 3/28/2019
 * Time: 11:44 AM
 */

abstract class Core_Model_Resource_Db_Abstract extends Core_Model_Resource_Abstract
{
    /**
     * Cached resources singleton
     *
     * @var Core_Model_Resource
     */
    protected $_resources;

    /**
     * Resource model name that contains entities (names of tables)
     *
     * @var string
     */
    protected $_resourceModel;

    /**
     * Prefix for resources that will be used in this resource model
     *
     * @var string
     */
    protected $_resourcePrefix;

    /**
     * Main table name
     *
     * @var string
     */
    protected $_mainTable;

    /**
     * Main table primary key field name
     *
     * @var string
     */
    protected $_idFieldName;

    /**
     * Fields of main table
     *
     * @var array
     */
    protected $_mainTableFields;

    /**
     * Serializable fields declaration
     * Structure: array(
     *     <field_name> => array(
     *         <default_value_for_serialization>,
     *         <default_for_unserialization>,
     *         <whether_to_unset_empty_when serializing> // optional parameter
     *     ),
     * )
     *
     * @var array
     */
    protected $_serializableFields   = array();

    /**
     * Standard resource model initialization
     * @param $mainTable
     * @param $idFieldName
     * @throws Exception
     */
    protected function _init($mainTable, $idFieldName)
    {
        $this->_setMainTable($mainTable, $idFieldName);
    }

    /**
     * Set main entity table name and primary key field name
     * If field name is ommited {table_name}_id will be used
     *
     * @param $mainTable
     * @param null $idFieldName
     * @return Core_Model_Resource_Db_Abstract
     * @throws Exception
     */
    protected function _setMainTable($mainTable, $idFieldName = null)
    {
        $mainTableArr = explode('/', $mainTable);
        //Virtual::dump($mainTableArr);

        if (!empty($mainTableArr[1])) {
            if (empty($this->_resourceModel)) {
                $this->_setResource($mainTableArr[0]);
            }
            $this->_setMainTable($mainTableArr[1], $idFieldName);
        }
        else {
            $this->_mainTable = $mainTable;
            if (is_null($idFieldName)) {
                $idFieldName = $mainTable . '_id';
            }
            $this->_idFieldName = $idFieldName;
        }

        //echo $this->_mainTable.'<br/>';

        return $this;
    }

    /**
     * Get primary key field name
     *
     * @return string
     * @throws Exception
     */
    public function getIdFieldName()
    {
        if (empty($this->_idFieldName)) {
            Virtual::throwException('Empty identifier field name');
        }

        return $this->_idFieldName;
    }

    /**
     * @param $connections
     * @param null $tables
     * @return Core_Model_Resource_Db_Abstract
     * @throws Exception
     */
    protected function _setResource($connections, $tables = null)
    {
        $this->_resources = Virtual::getSingleton('core/resource');
        $this->_resourcePrefix = $connections;

        if (is_null($tables) && is_string($connections)) {
            $this->_resourceModel = $this->_resourcePrefix;
        }
        else if (is_string($tables)) {
            $this->_resourceModel = $tables;
        }

        return $this;
    }

    /**
     * Retrieve connection for read data
     * @return Virtual_Db_Adapter_Pdo_Mysql
     * @throws Zend_Db_Adapter_Exception
     */
    protected function _getReadAdapter()
    {
        return $this->_getConnection();
    }

    /**
     * Temporary resolving collection compatibility
     * @return Virtual_Db_Adapter_Pdo_Mysql
     * @throws Zend_Db_Adapter_Exception
     */
    public function getReadConnection()
    {
        return $this->_getReadAdapter();
    }

    /**
     * Get connection resource
     * @return Virtual_Db_Adapter_Pdo_Mysql
     * @throws Zend_Db_Adapter_Exception
     */
    protected function _getConnection()
    {
        return $this->_resources->getConnection();
    }

    /**
     * Returns main table name - extracted from "module/table" style and
     * @return string
     * @throws Zend_Db_Adapter_Exception
     */
    public function getMainTable()
    {
        if (empty($this->_mainTable)) {
            Virtual::throwException('Empty main table name');
        }

        return $this->getTable($this->_mainTable);
    }

    /**
     * Get table name for the entity, validated by db adapter
     * @param $entityName
     * @return string
     * @throws Zend_Db_Adapter_Exception
     */
    public function getTable($entityName)
    {
        $tableName = '';
        //echo $entityName. '<br/>';

        if (strpos($entityName, '/')) {
            $modelEntity = $entityName;
            $tableName = $this->_resources->getTableName($modelEntity);
        }
        else if( !empty($this->_resourceModel) ) {
            $entityName = sprintf('%s/%s', $this->_resourceModel, $entityName);
            $tableName = $this->_resources->getTableName($entityName);
        }
        else {
            $tableName = $entityName;
        }

        return $tableName;
    }

    /**
     * Load an object
     *
     * @param Core_Model_Abstract $object
     * @param mixed $value
     * @param string $field field to load by (defaults to model id)
     * @return Core_Model_Resource_Db_Abstract
     */
    public function load(Core_Model_Abstract $object, $value, $field = null)
    {
        if (is_null($field)) {
            $field = $this->getIdFieldName();
        }

        $read = $this->_getReadAdapter();
        if ($read && !is_null($value)) {
            $select = $this->_getLoadSelect($field, $value, $object);
            $data = $read->fetchRow($select);

            if ($data) {
                $object->setData($data);
            }
        }

        $this->unserializeFields($object);
        $this->_afterLoad($object);

        return $this;
    }

    /**
     * Retrieve select object for load object data
     *
     * @param string $field
     * @param mixed $value
     * @param Core_Model_Abstract $object
     * @return Zend_Db_Select
     */
    protected function _getLoadSelect($field, $value, $object)
    {
        $field  = $this->_getReadAdapter()->quoteIdentifier(sprintf('%s.%s', $this->getMainTable(), $field));
        $select = $this->_getReadAdapter()->select()
                       ->from($this->getMainTable())
                       ->where($field . '=?', $value);

        return $select;
    }

    /**
     * Unserialize serializeable object fields
     *
     * @param Core_Model_Abstract $object
     */
    public function unserializeFields(Core_Model_Abstract $object)
    {
        foreach ($this->_serializableFields as $field => $parameters) {
            list($serializeDefault, $unserializeDefault) = $parameters;
            $this->_unserializeField($object, $field, $unserializeDefault);
        }
    }

    /**
     * Perform actions after object load
     *
     * @param Varien_Object $object
     * @return Core_Model_Resource_Db_Abstract
     */
    protected function _afterLoad(Core_Model_Abstract $object)
    {
        return $this;
    }

    /**
     * Perform actions before object save
     *
     * @param Varien_Object $object
     * @return Core_Model_Resource_Db_Abstract
     */
    protected function _beforeSave(Core_Model_Abstract $object)
    {
        return $this;
    }

}//End of class