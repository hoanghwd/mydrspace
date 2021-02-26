<?php
class G2f_Block_Device extends Core_Block_Template
{
    /**
     * @return string
     * @throws Zend_Controller_Request_Exception
     * @throws Zend_Controller_Response_Exception
     */
    public function getAuthUrl()
    {
        return $this->getBaseUrl().DS.'g2f'.DS.'ajax';
    }

    /**
     * @return string
     * @throws Zend_Controller_Request_Exception
     * @throws Zend_Controller_Response_Exception
     */
    public function getQRUrl()
    {
        /**
         * @var G2f_Model_Authentication $ga
         */
        $ga = $this->_getGoogleAuth();

        if (!isset($_SESSION['auth_secret'])) {
            $secret = $ga->generateRandomSecret();
            $_SESSION['auth_secret'] = $secret;
        }

        $site = str_replace("https://", NULL, $this->getBaseUrl());

        return $ga->getQR($site, $_SESSION['auth_secret']);
    }

    /**
     * @return bool
     * @throws Exception
     */
    protected function _getGoogleAuth()
    {
        return Virtual::getModel('g2f/authentication');
    }

    /**
     * @return string
     * @throws Zend_Controller_Request_Exception
     * @throws Zend_Controller_Response_Exception
     */
    public function getTooltipsUrl()
    {
        return Virtual::getTooltipsUrl('g2f');
    }



}//End of class