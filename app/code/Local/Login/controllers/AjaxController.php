<?php
/**
 * Created by PhpStorm.
 * User: hdo
 * Date: 4/10/2019
 * Time: 3:57 PM
 */

class Login_AjaxController extends Core_Controller_Front_Action
{
    protected $_isSuccess;

    function _construct()
    {
        parent::_construct(); // TODO: Change the autogenerated stub
        $this->_isSuccess = false;
    }

    /**
     * Index action
     */
    public function indexAction()
    {
        $redirect = true;
        if ( !empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) === 'xmlhttprequest' ) {
            $isAjax = Virtual::app()->getRequest()->isAjax();
            if ($isAjax) {
                $params = Virtual::app()->getRequest()->getParams();
                $errorMsg = 'Invalid parameters';
                $authenticate = '';
                $redirect = false;

                if (is_array($params) && sizeof($params) > 0) {
                    $authenticate = $this->_authenticate($params);
                    $this->_isSuccess = $authenticate->isSuccess();
                    $errorMsg = $authenticate->getErrorMessage();
                }

                $results = array(
                    'success' => $this->_isSuccess,
                    'error' => $errorMsg,
                    'failedCounts' => $authenticate->getLoginFailedCounts(),
                    'forward' => $this->_getForwardUrl(trim($params['username']))
                );

                echo Virtual::helper('core')->jsonEncode($results);
            }//if ajax
        }//If refer

        if( $redirect ) {
            $this->_getLoginSession()->destroyUserSession();
            $this->_redirectUrl(Virtual::getBaseUrl());
        }
    }

    /**
     * @param $params
     * @return Login_Model_User
     * @throws Exception
     */
    private function _authenticate($params)
    {
        return $this->_getUserModel()->login(trim($params['username']), trim($params['password']), trim($params['catpcha']));
    }

    /**
     * @param $username
     * @return string
     * @throws Zend_Controller_Request_Exception
     * @throws Zend_Controller_Response_Exception
     */
    protected function _getForwardUrl($username)
    {
        $url = (Virtual::getBaseUrl()) . DS . 'profile';

        if ($this->_isSuccess) {
            $code = $this->_getUserModel()->getGoogleAuthCode($username);
            if ($code == '') {
                $url = (Virtual::getBaseUrl()) . DS . 'g2f';
            }
        }

        return $url;
    }

    /**
     * Log user out
     */
    public function logoutAction()
    {
        $isAjax = Virtual::app()->getRequest()->isAjax();
        if ($isAjax) {
            //Destroy user login session
            Virtual::getModel('login/session')->destroyUserSession();
            $results = array(
                'success' => true,
                'forward' => (Virtual::getBaseUrl()) . DS . 'login'
            );

            echo Virtual::helper('core')->jsonEncode($results);
        }
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
     * @return Login_Model_Session
     * @throws Exception
     */
    protected function _getLoginSession()
    {
        return Virtual::getSingleton('login/session');
    }

}//End of class