<?php
class User_Model_Profile extends Core_Model_Abstract
{
    /**
     * Initialize user profile model
     */
    protected function _construct()
    {
        $this->_init('user/profile');
    }

    /**
     * Load user by email
     * @param $email
     * @return Login_Model_User
     * @throws Exception
     */
    public function loadByEmail($email)
    {
        $this->setData($this->getResource()->loadByEmail($email));

        return $this;
    }

    /**
     * @param $email
     * @return mixed
     * @throws Exception
     */
    public function isEmailExist($email)
    {
        $this->loadByEmail($email);

        return $this->getId();
    }

}//End of class