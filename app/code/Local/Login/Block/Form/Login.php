<?php
/**
 * Created by PhpStorm.
 * User: hdo
 * Date: 4/4/2019
 * Time: 9:59 AM
 */

class Login_Block_Form_Login extends Core_Block_Template
{
    /**
     * @return string
     * @throws Zend_Controller_Request_Exception
     * @throws Zend_Controller_Response_Exception
     */
    public function getAuthUrl()
    {
        return $this->getBaseUrl().DS.'login'.DS.'ajax';
    }

    /**
     * @return string
     * @throws Zend_Controller_Request_Exception
     * @throws Zend_Controller_Response_Exception
     */
    public function getTooltipsUrl()
    {
        return Virtual::getTooltipsUrl('login');
    }

    /**
     * @return mixed
     */
    private function _getCaptchaConfig()
    {
        $config = Virtual::getConfig()->getNode()->default->web->captcha->asArray();

        return
            array (
                'input_name' => $config['input_name'],
                'input_id'   => $config['input_id'] ,
                'input_text' => $config['input_text']
            );
    }

    /**
     * @return bool
     * @throws Zend_Controller_Request_Exception
     * @throws Zend_Controller_Response_Exception
     */
    public function isCaptChaIsEnabled()
    {
        $enabled = Virtual::app()->getStore()->getConfig('web/captcha/enable');

        return $enabled == 1;
    }

    /**
     * @return bool
     * @throws Zend_Controller_Request_Exception
     * @throws Zend_Controller_Response_Exception
     */
    public function renderCaptCha()
    {
        if( $this->isCaptChaIsEnabled() ) {
            echo Securimage::getCaptchaHtml($this->_getCaptchaConfig());
            return true;
        }

        return false;
    }

}//End of class