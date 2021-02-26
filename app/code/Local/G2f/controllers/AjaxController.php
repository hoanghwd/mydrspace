<?php
/**
 * Created by PhpStorm.
 * User: hdo
 * Date: 4/10/2019
 * Time: 3:57 PM
 */

class G2f_AjaxController extends Core_Controller_Front_Action
{
    /**
     * Index action
     */
    public function indexAction()
    {
        $redirect = true;
        if (!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) === 'xmlhttprequest' ) {
            $isAjax = Virtual::app()->getRequest()->isAjax();
            if ($isAjax) {
                $params = Virtual::app()->getRequest()->getParams();
                $isMatched = false;
                $authG2fFailedNums = 0;
                $redirect = false;

                if (is_array($params) && sizeof($params) > 0) {
                    $isMatched = $this->_getDeviceConfirm()
                        ->verifyCode(trim($params['code']))
                        ->isMatched();
                }

                if (!$isMatched) {
                    $userName = $_SESSION['username'];
                    $authG2fFailedNums = $this->_getLoginModel()
                                                ->recordFailedG2f($userName)
                                                ->getG2fAuthFailedNum($userName);
                }

                $results = array(
                    'success' => $isMatched,
                    'error'   => $isMatched ? '' : "Invalid code",
                    'forward' => $this->_getDeviceConfirm()->getForwardUrl($isMatched, $authG2fFailedNums)
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
     * @return Login_Model_User
     * @throws Exception
     */
    protected function _getLoginModel()
    {
        return Virtual::getModel('login/user');
    }

    /**
     * @return G2f_Model_Verify
     * @throws Exception
     */
    protected function _getDeviceConfirm()
    {
        return Virtual::getModel('g2f/verify');
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