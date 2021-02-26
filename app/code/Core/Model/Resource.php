<?php
/**
 * Created by PhpStorm.
 * User: hdo
 * Date: 3/28/2019
 * Time: 12:20 PM
 */

class Core_Model_Resource
{
    /**
     * @return Virtual_Db_Adapter_Pdo_Mysql
     * @throws Zend_Db_Adapter_Exception
     */
    public function getConnection()
    {
        return $this->_newConnection();
    }

    /**
     * @return Virtual_Db_Adapter_Pdo_Mysql
     * @throws Zend_Db_Adapter_Exception
     */
    protected function _newConnection()
    {
        $credentials = Virtual::getConfig()->getNode()->global->resources->connection->asArray();
        $connection = new Virtual_Db_Adapter_Pdo_Mysql($credentials);

        if ($connection instanceof Varien_Db_Adapter_Interface) {
            if (!empty($credentials['initStatements'])) {
                $connection->query($credentials['initStatements']);
            }
        }

        return $connection;
    }

    /**
     * Get resource table name, validated by db adapter
     * @param $modelEntity
     * @return string
     * @throws Zend_Db_Adapter_Exception
     */
    public function getTableName($modelEntity)
    {
        $parts = explode('/', $modelEntity);
        $tableName = '';
        //virtual::dump($parts);

        if (isset($parts[1])) {
            list($model, $entity) = $parts;
            $entityConfig = false;
            if (!empty(Virtual::getConfig()->getNode()->global->models->{$model}->resourceModel)) {
                $resourceModel = (string)Virtual::getConfig()->getNode()->global->models->{$model}->resourceModel;
                $entityConfig = $this->getEntity($resourceModel, $entity);
            }

            if ($entityConfig && !empty($entityConfig->table)) {
                $tableName = (string)$entityConfig->table;
            }
            else {
                Virtual::throwException('Can\'t retrieve entity config:'. $modelEntity);
            }
        }
        else {
            $tableName = $modelEntity;
        }

        return $this->getConnection()->getTableName($tableName);
    }

    /**
     * @param $model
     * @param $entity
     * @return Varien_Simplexml_Config
     */
    public function getEntity($model, $entity)
    {
        $modelsNode = Virtual::getConfig()->getNode()->global->models;

        return $modelsNode->$model->entities->{$entity};
    }

}//End of class