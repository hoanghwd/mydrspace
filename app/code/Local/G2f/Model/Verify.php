<?php
class G2f_Model_Verify extends Core_Model_Abstract
{

    const G2F_FAILED_ALLOWED = 'web/login/failed_g2f_max';

    protected $_isMatched;

    /**
     *
     */
    function _construct()
    {
        $this->_isMatched = false;
    }

    /**
     * @param $code
     * @return G2f_Model_Verify
     * @throws Exception
     */
    public function verifyCode($code)
    {
        if( isset($_SESSION['auth_secret']) && is_numeric($code) ) {
            $userName = $_SESSION['username'];
            $this->_isMatched = $this->_getGoogleAuth()->verifyCode($_SESSION['auth_secret'], $code, 2);

            $userModel = $this->_getLoginModel();
            if( $this->_isMatched ) {
                $userModel->resetG2fAuthNums($userName);
            }
        }

        return $this;
    }

    /**
     * @param $isMatched
     * @param $authG2fFailedNums
     * @return string
     * @throws Zend_Controller_Request_Exception
     * @throws Zend_Controller_Response_Exception
     */
    public function getForwardUrl($isMatched, $authG2fFailedNums)
    {
        $baseurl = Virtual::getBaseUrl();

        if ( $authG2fFailedNums > $this->_getG2fMaxFailedAllow() ) {
            Virtual::getModel('login/session')->destroyUserSession();
            return $baseurl . DS . 'login';
        }
        else {
            if( $isMatched && isset($_SESSION['username']) ) {
                $userName = $_SESSION['username'];
                $loginModel = $this->_getLoginModel()->loadByUsername($userName);
                return ( $loginModel->getPasswordResetRequire() == 1 ) ? 'login' .DS. 'resetpassword' : 'profile';
            }
        }

        return '';
    }

    /**
     * @return string|null
     * @throws Zend_Controller_Request_Exception
     * @throws Zend_Controller_Response_Exception
     */
    private function _getG2fMaxFailedAllow()
    {
        return Virtual::app()->getStore()->getConfig(G2f_Model_Verify::G2F_FAILED_ALLOWED);
    }

    /**
     * @return Login_Model_User
     * @throws Exception
     */
    protected function _getLoginModel()
    {
        return Virtual::getModel('login/user');
    }

    /**
     * @return G2f_Model_Authentication
     * @throws Exception
     */
    protected function _getGoogleAuth()
    {
        return Virtual::getModel('g2f/authentication');
    }

    /**
     * @return mixed
     */
    public function isMatched()
    {
        return $this->_isMatched;
    }

}//End of class