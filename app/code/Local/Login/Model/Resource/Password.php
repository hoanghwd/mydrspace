<?php
class Login_Model_Resource_Password extends Core_Model_Resource_Db_Abstract
{
    /**
     * Define main table
     *
     */
    protected function _construct()
    {
        $this->_init('login/password', 'password_id');
    }

    /**
     *
     * @param $passwordHash
     * @return array
     * @throws Zend_Db_Adapter_Exception
     */
    public function getPasswordHashByHash($passwordHash)
    {
        $adapter = $this->_getReadAdapter();
        $select = $adapter->select()
                          ->from($this->getMainTable())
                          ->where('password_hash=:password_hash');

        $binds = array('password_hash' => $passwordHash);

        return $adapter->fetchRow($select, $binds);
    }

    /**
     * @param $username
     * @param $newPasswordHash
     * @return $this
     * @throws Zend_Db_Adapter_Exception
     */
    public function updatePassword($username, $newPasswordHash)
    {
        $adapter = $this->_getReadAdapter();
        $data = array(
            'user_name' => $username,
            'password_hash'  => $newPasswordHash
        );

        $adapter->insert($this->getMainTable(), $data);

        return $this;
    }

}//End of class