<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

class Registration_Block_Form_Createaccount extends Core_Block_Template
{
    /**
     * @return string
     * @throws Zend_Controller_Request_Exception
     * @throws Zend_Controller_Response_Exception
     */
    public function getRegUrl()
    {
        return $this->getBaseUrl().DS.'registration'.DS.'ajax';
    }

    /**
     * @return string
     * @throws Zend_Controller_Request_Exception
     * @throws Zend_Controller_Response_Exception
     */
    public function getUploadUrl()
    {
        return $this->getBaseUrl().DS.'registration'.DS.'upload';
    }

    /**
     * @return string
     * @throws Zend_Controller_Request_Exception
     * @throws Zend_Controller_Response_Exception
     */
    public function getTooltipsUrl()
    {
        return Virtual::getTooltipsUrl('registration');
    }

    /**
     * @return string
     */
    public function renderStateOption()
    {
        $html = '<option selected="selected" value="">Select</option>';
        foreach (STATE_ARRAY as $state => $name) {
            $html .= '<option value="'.$state.'">'.$state.' - '.$name.'</option>';
        }//foreach

        return $html;
    }

    /**
     * @return string
     */
    public function renderMedicalType()
    {
        $html = '<option selected="selected" value="">Select</option>';
        foreach (MEDICAL_TYPE as $key => $name) {
            $html .= '<option value="'.$key.'">'.$name.'</option>';
        }//foreach

        return $html;
    }

}//End of class