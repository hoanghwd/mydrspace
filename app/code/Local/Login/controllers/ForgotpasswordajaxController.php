<?php
class Login_ForgotpasswordajaxController extends Core_Controller_Front_Action
{
    /**
     * @throws Zend_Controller_Request_Exception
     * @throws Zend_Controller_Response_Exception
     */
    public function indexAction()
    {
        $redirect = true;
        if ( !empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) === 'xmlhttprequest' ) {
            $isAjax = Virtual::app()->getRequest()->isAjax();
            if ($isAjax) {
                $params = Virtual::app()->getRequest()->getParams();
                $errorMsg = 'Invalid parameters';
                $successMsg = '';
                $isMatched = false;
                $redirect = false;

                if (is_array($params) && sizeof($params) > 0) {
                    $userName = trim($params['username']);
                    $isMatched = $this->_getUserModel()->isUsernameExist($userName);
                    if ( $isMatched ) {
                        /**
                         * @var Login_Model_Password $passwordModel
                         */
                        $passwordModel = $this->_getPasswordModel();
                        if ($passwordModel->init($userName)->forgotPassword()->isMailSent()) {
                            $errorMsg = '';
                            $maskedEmail = $this->_getPasswordModel()->maskEmail($userName);
                            $successMsg = 'A temporary password has been sent to this email '. $maskedEmail;
                        }
                        else {
                            $errorMsg = 'Sorry, we encounter some technical difficulties, please try again!';
                            $isMatched = false;
                        }
                    }
                    else {
                        $errorMsg = "Sorry, username does not match!";
                    }
                }

                $results = array(
                    'success'    => $isMatched,
                    'error'      => $errorMsg,
                    'successMsg' => $successMsg,
                );

                echo Virtual::helper('core')->jsonEncode($results);
            }//if ajax
        }//if refer

        if( $redirect ) {
            $this->_getLoginSession()->destroyUserSession();
            $this->_redirectUrl(Virtual::getBaseUrl());
        }
    }

    /**
     * @return Login_Model_Session
     * @throws Exception
     */
    protected function _getLoginSession()
    {
        return Virtual::getSingleton('login/session');
    }

    /**
     * @return Login_Model_User
     * @throws Exception
     */
    private function _getUserModel()
    {
        return Virtual::getModel('login/user');
    }

    /**
     * @return Login_Model_Password
     * @throws Exception
     */
    private function _getPasswordModel()
    {
        return Virtual::getModel('login/password');
    }

}//End of class