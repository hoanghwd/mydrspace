<?php
class User_Model_Resource_Profile extends Core_Model_Resource_Db_Abstract
{
    /**
     * Define main table
     *
     */
    protected function _construct()
    {
        $this->_init('user/profile', 'user_id');
    }

    /**
     * Load data by specified userId
     * @param $userId
     * @return mixed
     * @throws Zend_Db_Adapter_Exception
     */
    public function loadByUserId($userId)
    {
        $adapter = $this->_getReadAdapter();
        $select = $adapter->select()
                          ->from($this->getMainTable())
                          ->where('user_id=:user_id');

        $binds = array('user_id' => $userId);

        return $adapter->fetchRow($select, $binds);
    }

    /**
     * Load data by email
     * @param $email
     * @return mixed
     * @throws Zend_Db_Adapter_Exception
     */
    public function loadByEmail($email)
    {
        $adapter = $this->_getReadAdapter();
        $select = $adapter->select()
                          ->from($this->getMainTable())
                          ->where('email=:email');

        $binds = array('email' => $email);

        return $adapter->fetchRow($select, $binds);
    }


}//End of class