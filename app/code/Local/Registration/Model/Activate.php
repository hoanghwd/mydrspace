<?php
class Registration_Model_Activate extends Core_Model_Abstract
{
    const ACTIVATE_ACCOUNT_EXPIRE_MIN = 'web/activate_account_hash/expire_in_minutes';

    /**
     *
     */
    protected function _construct()
    {
        $this->_init('registration/activate');
    }

    /**
     * @param $username
     * @return Registration_Model_Activate
     * @throws Exception
     */
    public function loadByUsername($username)
    {
        $this->setData($this->getResource()->loadByUsername($username));

        return $this;
    }

    /**
     * @param $username
     * @return Registration_Model_Activate
     * @throws Exception
     */
    public function activateAccount($username)
    {
        $this->loadByUsername($username);
        $this->getResource()->activeUser($this);

        return $this;
    }

    /**
     * @param $username
     * @return bool
     * @throws Zend_Controller_Request_Exception
     * @throws Zend_Controller_Response_Exception
     */
    public function isActivateCodeExpired($username)
    {
        $this->loadByUsername($username);
        $regTime = $this->getUserRegistrationDatetime();
        $resetTime = strtotime($regTime);
        $nowTime = strtotime(now());
        $diff = abs($nowTime - $resetTime);
        $minutesDiff = floor($diff / 60);
        $allowMinutes = $this->_getAccountExpireInMin();

        return ($minutesDiff > $allowMinutes);
    }

    /**
     * @return string|null
     * @throws Zend_Controller_Request_Exception
     * @throws Zend_Controller_Response_Exception
     */
    protected function _getAccountExpireInMin()
    {
        return Virtual::getStoreConfig(self::ACTIVATE_ACCOUNT_EXPIRE_MIN);
    }

}//End of class