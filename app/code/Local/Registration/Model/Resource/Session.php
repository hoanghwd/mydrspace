<?php


class Registration_Model_Resource_Session extends Core_Model_Resource_Db_Abstract
{
    /**
     * Define main table
     */
    protected function _construct()
    {
        $this->_init('registration/session', 'id');
    }

    /**
     * @param $email
     * @param $randomString
     * @return string
     * @throws Zend_Db_Adapter_Exception
     */
    public function createReservation($email, $randomString)
    {
        $adapter = $this->_getReadAdapter();
        $data = array(
            'email'          => $email,
            'reservation_id' => $randomString
        );

        $adapter->insert($this->getMainTable(), $data);

        return $adapter->lastInsertId();
    }

}//End of class