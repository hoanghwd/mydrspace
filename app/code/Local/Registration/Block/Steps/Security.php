<?php
class Registration_Block_Steps_Security extends Registration_Block_Form_Createaccount
{
    /**
     * @param $id
     * @return string
     * @throws Exception
     */
    public function renderSecurityOptions($id)
    {
        $html = '';
        $securityQuestionArray = $this->_getCreateAccountModel()->getSecurityQuestions();
        foreach ( $securityQuestionArray AS $securityQuestion ) {
            $myId = $id.$securityQuestion['sequence_id'];
            $value = $securityQuestion['sequence_id'];
            $html .= '<option id="'.$myId.'" value="'.$value.'">'.$securityQuestion['question_value'].'</option>';
        }

        return $html;
    }

    /**
     * @return Registration_Model_Createaccount
     * @throws Exception
     */
    private function _getCreateAccountModel()
    {
        return Virtual::getModel('registration/createaccount');
    }


}//End of class