<?php
class Core_Mail_Model_Send extends Core_Model_Abstract
{
    private $_serverConfig;

    /**
     * @var PHPMailer
     */
    private $_mailObject;

    private $_isSuccess;


    /**
     * Initialize resource model
     *
     */
    protected function _construct()
    {
        $this->_initServerConfig();
        $this->_isSuccess = false;

        if( is_array($this->_serverConfig) && sizeof($this->_serverConfig) > 0 ) {
            $this->_mailObject = $this->_initMailObject();
        }
    }

    /**
     * @param $emailTo
     * @param $subject
     * @param $body
     * @param $isHtml
     * @return Core_Mail_Model_Send
     */
    public function sendMail($emailTo, $subject, $body, $isHtml = true)
    {
        if( is_array($emailTo) && sizeof($emailTo) > 0 ) {
            foreach($emailTo AS $address) {
                $this->_mailObject->addAddress($address);
            }//foreach
        }
        else {
            $this->_mailObject->addAddress($emailTo);
        }

        $this->_mailObject->Subject = $subject;
        $this->_mailObject->Body = $body;

        if( $isHtml ) {
            $this->_mailObject->isHTML(true);
        }

        $this->_isSuccess = $this->_mailObject->send();

        return $this;
    }

    /**
     * @return mixed
     */
    public function isSuccess()
    {
        return $this->_isSuccess;
    }

    /**
     * Set mail server configuration
     */
    private function _initServerConfig()
    {
        $this->_serverConfig = $this->_getServerConfig();
    }

    /**
     * @return mixed
     */
    private function _getServerConfig()
    {
        return Virtual::getConfig()->getNode()->default->mail->asArray();
    }

    /**
     * @return PHPMailer
     */
    private function _initMailObject()
    {
        $mail = new PHPMailer();

        if( $this->_serverConfig['smtp'] ) {
            // Set mailer to use SMTP
            $mail->IsSMTP();
            //useful for debugging, shows full SMTP errors
            //$mail->SMTPDebug = 2; // debugging: 1 = errors and messages, 2 = messages only
            // Enable SMTP authentication
            $mail->SMTPAuth = $this->_serverConfig['auth'];
            // Enable encryption, usually SSL/TLS
            if ( isset($this->_serverConfig['encryption'])) {
                $mail->SMTPSecure = $this->_serverConfig['encryption'] ;
            }

            // Specify host server
            $mail->Host = $this->_serverConfig['host'];
            $mail->Username = $this->_serverConfig['username'];
            $mail->Password = $this->_serverConfig['password'];
            $mail->Port = $this->_serverConfig['port'];
            $mail->From = $this->_serverConfig['email_from'];
            $mail->FromName = $this->_serverConfig['email_from_name'];
        }
        else {
            $mail->IsMail();
            //$mail->isHTML();
        }

        return $mail;
    }

}//End of class