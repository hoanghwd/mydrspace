<?php
class Login_ForgotusernameajaxController extends Core_Controller_Front_Action
{
    public function indexAction()
    {
        $redirect = true;
        if ( !empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) === 'xmlhttprequest' ) {
            $isAjax = Virtual::app()->getRequest()->isAjax();
            if ($isAjax) {
                $params = Virtual::app()->getRequest()->getParams();
                $errorMsg = 'Invalid parameters';
                $mailSent = false;
                $redirect = false;

                if (is_array($params) && sizeof($params) > 0) {
                    $loginEmailModel = $this->_getLoginEmailModel()
                                            ->init( trim($params['email']) )
                                            ->sendEmailReminder();
                    $mailSent = $loginEmailModel->isMailSent();
                    $errorMsg = $loginEmailModel->getErrorMsg();
                }

                $results = array(
                    'success' => $mailSent,
                    'error'   => $errorMsg
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
     * @return Login_Model_Email
     * @throws Exception
     */
    protected function _getLoginEmailModel()
    {
        return Virtual::getModel('login/email');
    }

}//End of class