<?php
class Registration_Model_Resource_Activate extends Core_Model_Resource_Db_Abstract
{
    /**
     * Define main table
     *
     */
    protected function _construct()
    {
        $this->_init('login/user', 'user_id');
    }

    /**
     * Load data by specified username
     * @param $username
     * @return array
     * @throws Zend_Db_Adapter_Exception
     */
    public function loadByUsername($username)
    {
        $adapter = $this->_getReadAdapter();
        $select = $adapter->select()
                          ->from($this->getMainTable())
                          ->where('username=:username');

        $binds = array('username' => $username);

        return $adapter->fetchRow($select, $binds);
    }

    /**
     * @param Registration_Model_Activate $user
     * @return Registration_Model_Resource_Activate
     * @throws Zend_Db_Adapter_Exception
     */
    public function activeUser(Registration_Model_Activate $user)
    {
        $adapter = $this->_getReadAdapter();

        $data = array(
            'is_active'            => 1,
            'user_activation_hash' => ''
        );
        $condition = array('user_id = ?' => (int) $user->getUserId());

        $adapter->update($this->getMainTable(), $data, $condition);

        return $this;
    }

}//End of class