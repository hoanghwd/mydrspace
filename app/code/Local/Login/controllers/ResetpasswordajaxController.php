<?php
class Login_ResetpasswordajaxController extends Core_Controller_Front_Action
{
    public function indexAction()
    {
        $redirect = true;
        if ( !empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) === 'xmlhttprequest' ) {
            $isAjax = Virtual::app()->getRequest()->isAjax();
            if ($isAjax) {
                $params = Virtual::app()->getRequest()->getParams();
                $isSuccess = false;
                $errorMsg = '';
                $redirect = false;

                if (is_array($params) && sizeof($params) > 0) {
                    $passwordModel = $this->_getPasswordModel();
                    $isSuccess = $passwordModel->resetPassword($params)->isSuccess();
                    $errorMsg = $passwordModel->getErrorMsg();
                }

                $results = array(
                    'success' => $isSuccess,
                    'error' => $this->_getHelper()->jsonEncode($errorMsg),
                    'forward' => $isSuccess ? Virtual::getBaseUrl() : ''
                );

                echo Virtual::helper('core')->jsonEncode($results);
            }//if ajax
        }//if refer

        if( $redirect ) {
            $this->_getLoginSession()->destroyUserSession();
            $this->_redirectUrl(Virtual::getBaseUrl());
        }
    }

    private function _getHelper()
    {
        return Virtual::helper('core');
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
     * @return Login_Model_Password
     * @throws Exception
     */
    private function _getPasswordModel()
    {
        return Virtual::getModel('login/password');
    }

}//End of class