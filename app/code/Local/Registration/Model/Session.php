<?php
class Registration_Model_Session extends Core_Model_Abstract
{
    const NUMBER_SEQ = '123456789';

    const RESERVATION_LEN = 6;

    /**
     *
     */
    protected function _construct()
    {
        $this->_init('registration/session');
    }

    /**
     * @param $email
     * @return false
     * @throws Exception
     */
    public function createNewReservation($email)
    {
        //Need to check email whether already registered or not first
        if( $this->_isEmailExist($email) ) {
            return false;
        }
        else {
            $randomString = $this->_getHelper()->getRandomString(self::RESERVATION_LEN, self::NUMBER_SEQ);
            $id = $this->getResource()->createReservation($email, $randomString);

            return ($id > 0) ? $randomString : false;
        }
    }

    /**
     * @return Core_Helper_Data|null
     */
    private function _getHelper()
    {
        return Virtual::helper('core');
    }

    /**
     * @param $email
     * @return mixed
     * @throws Exception
     */
    private function _isEmailExist($email)
    {
        return $this->_getUserProfileModel()->isEmailExist($email);
    }

    /**
     * @return User_Model_Profile
     * @throws Exception
     */
    private function _getUserProfileModel()
    {
        return Virtual::getModel('user/profile');
    }

}//End of class