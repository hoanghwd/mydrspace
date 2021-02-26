<?php
class Password_Model_Evaluate extends Core_Model_Abstract
{
    protected $_password;

    /**
     * @param $password
     * @return Password_Model_Evaluate
     */
    public function setPassword($password)
    {
        $this->_password = $password;

        return $this;
    }

    /**
     * @return int
     */
    public function isPasswordComplied()
    {
        return preg_match(PASSWORD_PATTERN_PHP, $this->_password);
    }

    /**
     * @param $max
     * @return bool
     */
    public function checkPasswordSequence($max)
    {
        $password = $this->_password;
        $j = 0;
        for($i = 0; $i < strlen($password); $i++) {
            if(isset($password[$i+1]) && ord($password[$i]) + 1 === ord($password[$i+1])) {
                $j++;
            }
            else {
                $j = 0;
            }

            if($j === $max) {
                return true;
            }
        }//for

        return false;
    }

    /**
     * @return string|null
     * @throws Zend_Controller_Request_Exception
     * @throws Zend_Controller_Response_Exception
     */
    private function _getPasswordMinLen()
    {
        return Virtual::getStoreConfig('web/password/min_lenght');
    }

    /**
     * @return string
     * @throws Zend_Controller_Request_Exception
     * @throws Zend_Controller_Response_Exception
     */
    public function passwordRules()
    {
        $err = '';
        $minLen = $this->_getPasswordMinLen();
        $password = $this->_password;

        if (strlen($password) <=  $minLen) {
            $err = "Password must have minimum $minLen characters!";
        }
        elseif(!preg_match("#[0-9]+#",$password)) {
            $err = "Password must contain at least 1 digit!";
        }
        elseif(!preg_match("#[A-Z]+#",$password)) {
            $err = "Password must contain at least 1 upper letter!";
        }
        elseif(!preg_match("#[a-z]+#",$password)) {
            $err = "Password must contain at least 1 lowercase letter!";
        }
        elseif(!preg_match('/[\'^£$%&*()}{@#~?><>,|=_+¬-]/', $password)) {
            $err = "Password must contain at least 1 special character!";
        }

        return $err;
    }

}//End of class