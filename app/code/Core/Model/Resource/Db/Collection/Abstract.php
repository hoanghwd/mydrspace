<?php
/**
 * Created by PhpStorm.
 * User: hdo
 * Date: 4/4/2019
 * Time: 9:38 PM
 */

Class Core_Model_Resource_Db_Collection_Abstract extends Varien_Data_Collection_Db
{
    /**
     * Model name
     *
     * @var string
     */
    protected $_model;

    /**
     * Resource model name
     *
     * @var string
     */
    protected $_resourceModel;

    /**
     * Resource instance
     *
     * @var Core_Model_Resource_Db_Abstract
     */
    protected $_resource;

    /**
     * Fields to select in query
     *
     * @var array|null
     */
    protected $_fieldsToSelect         = null;

    /**
     * Fields initial fields to select like id_field
     *
     * @var array|null
     */
    protected $_initialFieldsToSelect  = null;

    /**
     * Fields to select changed flag
     *
     * @var booleam
     */
    protected $_fieldsToSelectChanged  = false;

    /**
     * Store joined tables here
     *
     * @var array
     */
    protected $_joinedTables           = array();

    /**
     * Collection main table
     *
     * @var string
     */
    protected $_mainTable              = null;

    /**
     * Reset items data changed flag
     *
     * @var boolean
     */
    protected $_resetItemsDataChanged   = false;

    /**
     * Name prefix of events that are dispatched by model
     *
     * @var string
     */
    protected $_eventPrefix = '';

    /**
     * Name of event parameter
     *
     * @var string
     */
    protected $_eventObject = '';

    /**
     * Use analytic function flag
     * If true - allows to prepare final select with analytic function
     *
     * @var bool
     */
    protected $_useAnalyticFunction         = false;


    /**
     * Core_Model_Resource_Db_Collection_Abstract constructor.
     * @param null $resource
     * @throws Zend_Exception
     */
    public function __construct($resource = null)
    {
        parent::__construct();
        $this->_construct();
        $this->_resource = $resource;
        $this->setConnection($this->getResource()->getReadConnection());
        $this->_initSelect();
    }

    /**
     * Initialization here
     *
     */
    protected function _construct()
    {

    }

    /**
     * Init collection select
     * @return Core_Model_Resource_Db_Collection_Abstract
     * @throws Zend_Db_Adapter_Exception
     * @throws Zend_Db_Select_Exception
     */
    protected function _initSelect()
    {
        $this->getSelect()->from(array('main_table' => $this->getMainTable()));

        return $this;
    }

    /**
     * Retrieve main table
     * @return string|null
     * @throws Zend_Db_Adapter_Exception
     * @throws Zend_Db_Select_Exception
     */
    public function getMainTable()
    {
        if ($this->_mainTable === null) {
            $this->setMainTable($this->getResource()->getMainTable());
        }

        return $this->_mainTable;
    }

    /**
     * @param $table
     * @return Core_Model_Resource_Db_Collection_Abstract
     * @throws Zend_Db_Adapter_Exception
     * @throws Zend_Db_Select_Exception
     */
    public function setMainTable($table)
    {
        if (strpos($table, '/') !== false) {
            $table = $this->getTable($table);
        }

        if ($this->_mainTable !== null && $table !== $this->_mainTable && $this->getSelect() !== null) {
            $from = $this->getSelect()->getPart(Zend_Db_Select::FROM);
            if (isset($from['main_table'])) {
                $from['main_table']['tableName'] = $table;
            }
            $this->getSelect()->setPart(Zend_Db_Select::FROM, $from);
        }

        $this->_mainTable = $table;

        return $this;
    }

    /**
     * Retrieve table name
     * @param $table
     * @return string
     * @throws Zend_Db_Adapter_Exception
     */
    public function getTable($table)
    {
        return $this->getResource()->getTable($table);
    }

    /**
     * @param $entityName
     * @param $valueType
     * @return string
     * @throws Zend_Db_Adapter_Exception
     */
    public function getValueTable($entityName, $valueType)
    {
        return $this->getTable(array($entityName, $valueType));
    }

    /**
     * Set database connection adapter
     * @param Zend_Db_Adapter_Abstract $conn
     * @return Core_Model_Resource_Db_Collection_Abstract
     * @throws Zend_Exception
     */
    public function setConnection($conn)
    {
        if (!$conn instanceof Zend_Db_Adapter_Abstract) {
            throw new Zend_Exception('dbModel read resource does not implement Zend_Db_Adapter_Abstract');
        }

        $this->_conn = $conn;
        $this->_select = $this->_conn->select();
        $this->_isOrdersRendered = false;

        return $this;
    }

    /**
     * Get resource instance
     * @return bool|Core_Model_Resource_Db_Abstract|null
     * @throws Exception
     */
    public function getResource()
    {
        if (empty($this->_resource)) {
            $this->_resource = Virtual::getResourceModel($this->getResourceModelName());
        }

        return $this->_resource;
    }

    /**
     * Get Zend_Db_Select instance
     * @return Varien_Db_Select
     * @throws Zend_Db_Select_Exception
     */
    public function getSelect()
    {
        if ($this->_select && $this->_fieldsToSelectChanged) {
            $this->_fieldsToSelectChanged = false;
            $this->_initSelectFields();
        }

        return parent::getSelect();
    }

    /**
     * Init fields for select
     * @return Core_Model_Resource_Db_Collection_Abstract
     * @throws Zend_Db_Select_Exception
     */
    protected function _initSelectFields()
    {
        $columns = $this->_select->getPart(Zend_Db_Select::COLUMNS);
        $columnsToSelect = array();
        foreach ($columns as $columnEntry) {
            list($correlationName, $column, $alias) = $columnEntry;
            if ($correlationName !== 'main_table') { // Add joined fields to select
                if ($column instanceof Zend_Db_Expr) {
                    $column = $column->__toString();
                }
                $key = ($alias !== null ? $alias : $column);
                $columnsToSelect[$key] = $columnEntry;
            }
        }

        $columns = $columnsToSelect;

        $columnsToSelect = array_keys($columnsToSelect);

        if ($this->_fieldsToSelect !== null) {
            $insertIndex = 0;
            foreach ($this->_fieldsToSelect as $alias => $field) {
                if (!is_string($alias)) {
                    $alias = null;
                }

                if ($field instanceof Zend_Db_Expr) {
                    $column = $field->__toString();
                } else {
                    $column = $field;
                }

                if (($alias !== null && in_array($alias, $columnsToSelect)) ||
                    // If field already joined from another table
                    ($alias === null && isset($alias, $columnsToSelect))) {
                    continue;
                }

                $columnEntry = array('main_table', $field, $alias);
                array_splice($columns, $insertIndex, 0, array($columnEntry)); // Insert column
                $insertIndex ++;

            }
        } else {
            array_unshift($columns, array('main_table', '*', null));
        }

        $this->_select->setPart(Zend_Db_Select::COLUMNS, $columns);

        return $this;
    }

    /**
     *  Retrieve resource model name
     *
     * @return string
     */
    public function getResourceModelName()
    {
        return $this->_resourceModel;
    }

    /**
     * Retrieve connection object
     * @return Varien_Db_Adapter_Interface|Zend_Db_Adapter_Abstract
     */
    public function getConnection()
    {
        return $this->_conn;
    }

    /**
     * Get collection size
     *
     * @return int
     */
    public function getSize()
    {
        if (is_null($this->_totalRecords)) {
            $sql = $this->getSelectCountSql();
            $this->_totalRecords = $this->getConnection()->fetchOne($sql, $this->_bindParams);
        }

        return intval($this->_totalRecords);
    }

    /**
     * Standard resource collection initialization
     *
     * @param string $model
     * @param Core_Model_Resource_Db_Abstract $resourceModel
     * @return Core_Model_Resource_Db_Collection_Abstract
     */
    protected function _init($model, $resourceModel = null)
    {
        $this->setModel($model);
        if (is_null($resourceModel)) {
            $resourceModel = $model;
        }
        $this->setResourceModel($resourceModel);

        return $this;
    }

    /**
     * Get sql select string or object
     *
     * @param   bool $stringMode
     * @return  string || Zend_Db_Select
     */
    function getSelectSql($stringMode = false)
    {
        if ($stringMode) {
            return $this->_select->__toString();
        }
        return $this->_select;
    }

    /**
     * Returns a collection item that corresponds to the fetched row
     * and moves the internal data pointer ahead
     * @return bool|Varien_Object
     * @throws Zend_Db_Select_Exception
     * @throws Zend_Db_Statement_Exception
     */
    public function fetchItem()
    {
        if (null === $this->_fetchStmt) {
            $this->_fetchStmt = $this->getConnection()
                ->query($this->getSelect());
        }
        $data = $this->_fetchStmt->fetch();
        if (!empty($data) && is_array($data)) {
            $item = $this->getNewEmptyItem();
            if ($this->getIdFieldName()) {
                $item->setIdFieldName($this->getIdFieldName());
            }
            $item->setData($data);

            return $item;
        }
        return false;
    }

    /**
     * Fetch collection data
     *
     * @param   Zend_Db_Select $select
     * @return  array
     */
    protected function _fetchAll($select)
    {
        return $this->getConnection()->fetchAll($select, $this->_bindParams);
    }

    /**
     * Set model name for collection items
     *
     * @param string $model
     * @return Core_Model_Resource_Db_Collection_Abstract
     */
    public function setModel($model)
    {
        if (is_string($model)) {
            $this->_model = $model;
            $this->setItemObjectClass(Virtual::getConfig()->getModelClassName($model));
        }
        return $this;
    }

    /**
     * Get model instance
     *
     * @param array $args
     * @return Varien_Object
     */
    public function getModelName($args = array())
    {
        return $this->_model;
    }

    /**
     *  Set resource model name for collection items
     *
     * @param string $model
     */
    public function setResourceModel($model)
    {
        $this->_resourceModel = $model;
    }

    /**
     * Retrieve all ids for collection
     * @return array
     * @throws Zend_Db_Select_Exception
     */
    public function getAllIds()
    {
        $idsSelect = clone $this->getSelect();
        $idsSelect->reset(Zend_Db_Select::ORDER);
        $idsSelect->reset(Zend_Db_Select::LIMIT_COUNT);
        $idsSelect->reset(Zend_Db_Select::LIMIT_OFFSET);
        $idsSelect->reset(Zend_Db_Select::COLUMNS);

        $idsSelect->columns($this->getResource()->getIdFieldName(), 'main_table');

        return $this->getConnection()->fetchCol($idsSelect);
    }

    /**
     * Initialize initial fields to select like id field
     * @return Core_Model_Resource_Db_Collection_Abstract
     * @throws Exception
     */
    protected function _initInitialFieldsToSelect()
    {
        $idFieldName = $this->getResource()->getIdFieldName();
        if ($idFieldName) {
            $this->_initialFieldsToSelect[] = $idFieldName;
        }

        return $this;
    }

    /**
     * Retrieve initial fields to select like id field
     * @return array|null
     * @throws Exception
     */
    protected function _getInitialFieldsToSelect()
    {
        if ($this->_initialFieldsToSelect === null) {
            $this->_initialFieldsToSelect = array();
            $this->_initInitialFieldsToSelect();
        }

        return $this->_initialFieldsToSelect;
    }

    /**
     * Add field to select
     * @param $field
     * @param null $alias
     * @return Core_Model_Resource_Db_Collection_Abstract
     * @throws Exception
     */
    public function addFieldToSelect($field, $alias = null)
    {
        if ($field === '*') { // If we will select all fields
            $this->_fieldsToSelect = null;
            $this->_fieldsToSelectChanged = true;
            return $this;
        }

        if (is_array($field)) {
            if ($this->_fieldsToSelect === null) {
                $this->_fieldsToSelect = $this->_getInitialFieldsToSelect();
            }

            foreach ($field as $key => $value) {
                $this->addFieldToSelect(
                    $value,
                    (is_string($key) ? $key : null),
                    false
                );
            }

            $this->_fieldsToSelectChanged = true;
            return $this;
        }

        if ($alias === null) {
            $this->_fieldsToSelect[] = $field;
        } else {
            $this->_fieldsToSelect[$alias] = $field;
        }

        $this->_fieldsToSelectChanged = true;

        return $this;
    }

    /**
     * @param $alias
     * @param $expression
     * @param $fields
     * @return Core_Model_Resource_Db_Collection_Abstract
     * @throws Zend_Db_Select_Exception
     */
    public function addExpressionFieldToSelect($alias, $expression, $fields)
    {
        // validate alias
        if (!is_array($fields)) {
            $fields = array($fields=>$fields);
        }

        $fullExpression = $expression;
        foreach ($fields as $fieldKey=>$fieldItem) {
            $fullExpression = str_replace('{{' . $fieldKey . '}}', $fieldItem, $fullExpression);
        }

        $this->getSelect()->columns(array($alias=>$fullExpression));

        return $this;
    }

    /**
     * @param $field
     * @param bool $isAlias
     * @return Core_Model_Resource_Db_Collection_Abstract
     */
    public function removeFieldFromSelect($field, $isAlias = false)
    {
        if ($isAlias) {
            if (isset($this->_fieldsToSelect[$field])) {
                unset($this->_fieldsToSelect[$field]);
            }
        } else {
            foreach ($this->_fieldsToSelect as $key => $value) {
                if ($value === $field) {
                    unset($this->_fieldsToSelect[$key]);
                    break;
                }
            }
        }

        $this->_fieldsToSelectChanged = true;

        return $this;
    }

    /**
     * @return Core_Model_Resource_Db_Collection_Abstract
     * @throws Exception
     */
    public function removeAllFieldsFromSelect()
    {
        $this->_fieldsToSelect = $this->_getInitialFieldsToSelect();
        $this->_fieldsToSelectChanged = true;
        return $this;
    }

}//End of class