<?php
class Profile_Model_User extends Core_Model_Abstract
{
    /**
     *
     */
    protected function _construct()
    {
        $this->_init('profile/user');
    }

    /**
     * @param $userId
     * @return Profile_Model_User
     * @throws Exception
     */
    public function loadById($userId)
    {
        $this->setData($this->getResource()->loadById($userId));

        return $this;
    }

    /**
     * @param $userId
     * @return mixed
     * @throws Exception
     */
    public function getFullProfile($userId)
    {
        return $this->getResource()->getFullProfile($userId)[0];
    }

}//End of class