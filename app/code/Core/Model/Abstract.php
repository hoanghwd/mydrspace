<?php
/**
 * Created by PhpStorm.
 * User: hdo
 * Date: 3/29/2019
 * Time: 11:57 AM
 */

abstract class Core_Model_Abstract extends Varien_Object
{
    /**
     * Name of the resource model
     *
     * @var string
     */
    protected $_resourceName;

    /**
     * Resource model instance
     *
     * @var Virtual_Db_Adapter_Pdo_Mysql
     */
    protected $_resource;

    /**
     * Name of the resource collection model
     *
     * @var string
     */
    protected $_resourceCollectionName;

    /**
     * Standard model initialization
     * @param $resourceModel
     */
    protected function _init($resourceModel)
    {
        $this->_setResourceModel($resourceModel);
    }

    /**
     * @return string
     */
    public function getResourceName()
    {
        return $this->_resourceName;
    }

    /**
     * Set resource names
     *
     * If collection name is ommited, resource name will be used with _collection appended
     *
     * @param string $resourceName
     * @param string|null $resourceCollectionName
     */
    protected function _setResourceModel($resourceName, $resourceCollectionName=null)
    {
        $this->_resourceName = $resourceName;
        if (is_null($resourceCollectionName)) {
            $resourceCollectionName = $resourceName.'_collection';
        }

        $this->_resourceCollectionName = $resourceCollectionName;
    }

    /**
     * @return bool
     * @throws Exception
     */
    public function getResourceCollection()
    {
        if (empty($this->_resourceCollectionName)) {
            Virtual::throwException('Model collection resource name is not defined.');
        }

        return Virtual::getResourceModel($this->_resourceCollectionName, $this->_getResource());
    }

    /**
     *  Get resource instance
     * @return mixed
     * @throws Exception
     */
    protected function _getResource()
    {
        if (empty($this->_resourceName)) {
            Virtual::throwException('Resource is not set.'.$this->_resourceName);
        }

        return Virtual::getResourceSingleton($this->_resourceName);
    }

    /**
     * @return Varien_Object
     * @throws Exception
     */
    public function getIdFieldName()
    {
        if (!($fieldName = parent::getIdFieldName())) {
            $fieldName = $this->_getResource()->getIdFieldName();
            $this->setIdFieldName($fieldName);
        }

        return $fieldName;
    }

    /**
     * @param mixed $id
     * @return Core_Model_Abstract|Varien_Object
     * @throws Exception
     */
    public function setId($id)
    {
        if ($this->getIdFieldName()) {
            $this->setData($this->getIdFieldName(), $id);
        } else {
            $this->setData('id', $id);
        }

        return $this;
    }

    /**
     * @param $id
     * @param null $field
     * @return $this
     * @throws Exception
     */
    public function load($id, $field=null)
    {
        $this->_beforeLoad($id, $field);
        $this->_getResource()->load($this, $id, $field);
        $this->_afterLoad();
        $this->setOrigData();
        $this->_hasDataChanges = false;

        return $this;
    }

    /**
     * @param $id
     * @param null $field
     * @return Core_Model_Abstract
     */
    protected function _beforeLoad($id, $field = null)
    {
        return $this;
    }

    /**
     * Processing object after load data
     *
     * @return Core_Model_Abstract
     */
    protected function _afterLoad()
    {
        return $this;
    }

    /**
     * Initialize object original data
     *
     * @param string $key
     * @param mixed $data
     * @return Varien_Object
     */
    public function setOrigData($key=null, $data=null)
    {
        if (is_null($key)) {
            $this->_origData = $this->_data;
        } else {
            $this->_origData[$key] = $data;
        }
        return $this;
    }

    /**
     * Compare object data with original data
     *
     * @param string $field
     * @return boolean
     */
    public function dataHasChangedFor($field)
    {
        $newData = $this->getData($field);
        $origData = $this->getOrigData($field);

        return $newData!=$origData;
    }


    /**
     * Retrieve model object identifier
     * @return mixed
     * @throws Exception
     */
    public function getId()
    {
        $fieldName = $this->getIdFieldName();
        if ($fieldName) {
            return $this->_getData($fieldName);
        } else {
            return $this->_getData('id');
        }
    }

    /**
     *  Retrieve model resource
     * @return mixed
     * @throws Exception
     */
    public function getResource()
    {
        return $this->_getResource();
    }

    /**
     * @return mixed
     */
    public function getEntityId()
    {
        return $this->_getData('entity_id');
    }

    /**
     * Clearing object's data
     *
     * @return Core_Model_Abstract
     */
    protected function _clearData()
    {
        return $this;
    }

}//End of class