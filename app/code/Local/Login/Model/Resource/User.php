<?php
/**
 * Created by PhpStorm.
 * User: hdo
 * Date: 4/5/2019
 * Time: 9:33 AM
 */

class Login_Model_Resource_User extends Core_Model_Resource_Db_Abstract
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
     * Authenticate user by $username and $password
     * @param Login_Model_User $user
     * @return Login_Model_Resource_User
     * @throws Zend_Db_Adapter_Exception
     */
    public function recordLogin(Login_Model_User $user)
    {
        $adapter = $this->_getReadAdapter();
        $data = array(
            'logdate' => now(),
            'lognum'  => $user->getLognum() + 1
        );
        $condition = array('user_id = ?' => (int) $user->getUserId());

        $adapter->update($this->getMainTable(), $data, $condition);

        return $this;
    }

    /**
     * @param Login_Model_User $user
     * @return Login_Model_Resource_User
     * @throws Zend_Db_Adapter_Exception
     */
    public function recordFailedLogin(Login_Model_User $user)
    {
        $adapter = $this->_getReadAdapter();
        $data = array(
            'user_last_failed'  => time(),
            'failed_login_nums' => $user->getFailedLoginNums() + 1
        );
        $condition = array('user_id = ?' => (int) $user->getUserId());

        $adapter->update($this->getMainTable(), $data, $condition);

        return $this;
    }

    /**
     * @param Login_Model_User $user
     * @return Login_Model_Resource_User
     * @throws Zend_Db_Adapter_Exception
     */
    public function recordFailedG2f(Login_Model_User $user)
    {
        $adapter = $this->_getReadAdapter();
        $data = array(
            'auth_code_failed_nums' => $user->getAuthCodeFailedNums() + 1,

        );
        $condition = array('user_id = ?' => (int) $user->getUserId());

        $adapter->update($this->getMainTable(), $data, $condition);

        return $this;
    }

    /**
     * @param Login_Model_User $user
     * @return Login_Model_Resource_User
     * @throws Zend_Db_Adapter_Exception
     */
    public function resetG2fAuthNums(Login_Model_User $user)
    {
        $adapter = $this->_getReadAdapter();
        $data = array(
            'auth_code_failed_nums' => 0,
            'authorization_code'    => time()
        );
        $condition = array('user_id = ?' => (int) $user->getUserId());

        $adapter->update($this->getMainTable(), $data, $condition);

        return $this;
    }

    /**
     * @param Login_Model_User $user
     * @return Login_Model_Resource_User
     * @throws Zend_Db_Adapter_Exception
     */
    public function resetAuthorizationCodeField(Login_Model_User $user)
    {
        $adapter = $this->_getReadAdapter();
        $data = array('authorization_code' => '');
        $condition = array('user_id = ?' => (int) $user->getUserId());
        $adapter->update($this->getMainTable(), $data, $condition);

        return $this;
    }

    /**
     * @param Login_Model_User $user
     * @return Login_Model_Resource_User
     * @throws Zend_Db_Adapter_Exception
     */
    public function resetFailedLogin(Login_Model_User $user)
    {
        $adapter = $this->_getReadAdapter();
        $data = array(
            'user_last_failed'  => '',
            'failed_login_nums' => ''
        );
        $condition = array('user_id = ?' => (int) $user->getUserId());

        $adapter->update($this->getMainTable(), $data, $condition);

        return $this;
    }

    /**
     * @param Login_Model_User $user
     * @param $passwordHash
     * @return Login_Model_Resource_User
     * @throws Zend_Db_Adapter_Exception
     */
    public function forgotPassword(Login_Model_User $user, $passwordHash)
    {
        $adapter = $this->_getReadAdapter();
        $data = array(
            'password'               => $passwordHash,
            'password_reset_require' => 1,
            'authorization_code'     => '',
            'password_reset_time'    => date('Y/m/d H:i:s')
        );
        $condition = array('user_id = ?' => (int) $user->getUserId());

        $adapter->update($this->getMainTable(), $data, $condition);

        return $this;
    }

    /**
     * @param Login_Model_User $user
     * @param $passwordHash
     * @return Login_Model_Resource_User
     * @throws Zend_Db_Adapter_Exception
     */
    public function updatePassword(Login_Model_User $user, $passwordHash)
    {
        $adapter = $this->_getReadAdapter();
        $data = array(
            'password'               => $passwordHash,
            'password_reset_require' => 0,
            'password_reset_time'    => '',
            'auth_code_failed_nums'  => 0,
            'failed_login_nums'      => 0
        );
        $condition = array('user_id = ?' => (int) $user->getUserId());

        $adapter->update($this->getMainTable(), $data, $condition);

        return $this;
    }

}//End of class