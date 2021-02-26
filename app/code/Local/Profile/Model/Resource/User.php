<?php

/**
 * CMS block model
 *
 * @category    Core
 * @package     Core_Profile
 */
class Profile_Model_Resource_User extends Core_Model_Resource_Db_Abstract
{
    /**
     * Initialize resource model
     *
     */
    protected function _construct()
    {
        $this->_init('profile/user', 'user_id');
    }

    /**
     * @param $userId
     * @return array
     * @throws Zend_Db_Adapter_Exception
     */
    public function loadById($userId)
    {
        $adapter = $this->_getReadAdapter();
        $select = $adapter->select()
                          ->from($this->getMainTable())
                          ->where('user_id=:user_id');

        $binds = array('user_id' => $userId);

        return $adapter->fetchRow($select, $binds);
    }

    /**
     * @param $userId
     * @return array
     * @throws Zend_Db_Adapter_Exception
     */
    public function getFullProfile($userId)
    {
        $adapter = $this->_getReadAdapter();
        $select = $adapter->select()
                          ->from( array('UP' => $this->getMainTable()) )
                          ->join(array('LU' => 'login_user'), 'LU.user_id = UP.user_id')
                          ->where('UP.user_id=:user_id');
        $binds = array('user_id' => $userId);

        return $adapter->fetchAll($select,$binds);
    }

}//End of class