<?php

class G2f_Model_Authentication extends Core_Model_Abstract
{
    protected $length = 6;

    protected $_chars = array();

    /**
     * G2f_Model_Authentication constructor.
     */
    function __construct()
    {
        $this->_chars = array(
            'A', 'B', 'C', 'D', 'E', 'F', 'G', 'H',
            'I', 'J', 'K', 'L', 'M', 'N', 'O', 'P',
            'Q', 'R', 'S', 'T', 'U', 'V', 'W', 'X',
            'Y', 'Z', '2', '3', '4', '5', '6', '7',
            '=',
        );
    }

    /**
     * @param int $secretLength
     * @return string
     * @throws Exception
     */
    public function generateRandomSecret($secretLength = 16)
    {
        $secret = '';
        /*
        $validChars = array(
            'A', 'B', 'C', 'D', 'E', 'F', 'G', 'H',
            'I', 'J', 'K', 'L', 'M', 'N', 'O', 'P',
            'Q', 'R', 'S', 'T', 'U', 'V', 'W', 'X',
            'Y', 'Z', '2', '3', '4', '5', '6', '7',
            '=',
        );
        */

        // Valid secret lengths are 80 to 640 bits
        if ($secretLength < 16 || $secretLength > 128) {
            throw new Exception('Bad secret length');
        }
        $random = false;
        if (function_exists('random_bytes')) {
            $random = random_bytes($secretLength);
        } elseif (function_exists('mcrypt_create_iv')) {
            $random = mcrypt_create_iv($secretLength, MCRYPT_DEV_URANDOM);
        } elseif (function_exists('openssl_random_pseudo_bytes')) {
            $random = openssl_random_pseudo_bytes($secretLength, $cryptoStrong);
            if (!$cryptoStrong) {
                $random = false;
            }
        }
        if ($random !== false) {
            for ($i = 0; $i < $secretLength; ++$i) {
                $secret .= $this->_chars[ord($random[$i]) & 31];
            }
        } else {
            throw new Exception('Cannot create secure random secret due to source unavailbility');
        }

        return $secret;
    }

    /**
     * @param $secret
     * @param null $timeSlice
     * @return string
     */
    public function getCode($secret, $timeSlice = null)
    {
        if ($timeSlice === null) {
            $timeSlice = floor(time() / 30);
        }

        $secretkey = $this->debase32($secret);

        $time = chr(0).chr(0).chr(0).chr(0).pack('N*', $timeSlice);
        $hm = hash_hmac('SHA1', $time, $secretkey, true);
        $offset = ord(substr($hm, -1)) & 0x0F;
        $hashpart = substr($hm, $offset, 4);

        $value = unpack('N', $hashpart);
        $value = $value[1];
        $value = $value & 0x7FFFFFFF;

        $modulo = pow(10, $this->length);

        return str_pad($value % $modulo, $this->length, '0', STR_PAD_LEFT);
    }

    /**
     * @param $name
     * @param $secret
     * @param null $title
     * @param array $params
     * @return string
     */
    public function getQR($name, $secret, $title = null, $params = array())
    {
        $width = !empty($params['width']) && (int) $params['width'] > 0 ? (int) $params['width'] : 200;
        $height = !empty($params['height']) && (int) $params['height'] > 0 ? (int) $params['height'] : 200;
        $level = !empty($params['level']) && array_search($params['level'], array('L', 'M', 'Q', 'H')) !== false ? $params['level'] : 'M';

        $urlencoded = urlencode('otpauth://totp/'.$name.'?secret='.$secret.'');
        if (isset($title)) {
            $urlencoded .= urlencode('&issuer='.urlencode($title));
        }

        return ($this->_getGoogleApi()).$width.'x'.$height.'&chld='.$level.'|0&cht=qr&chl='.$urlencoded.'';
    }

    /**
     * @return string
     */
    private function _getGoogleApi()
    {
        return 'https://chart.googleapis.com/chart?chs=';
    }

    /**
     * @param $secret
     * @param $code
     * @param int $discrepancy
     * @param null $currentTimeSlice
     * @return bool
     */
    public function verifyCode($secret, $code, $discrepancy = 1, $currentTimeSlice = null)
    {
        if ($currentTimeSlice === null) {
            $currentTimeSlice = floor(time() / 30);
        }

        if (strlen($code) != 6) {
            return false;
        }

        for ($i = -$discrepancy; $i <= $discrepancy; ++$i) {
            $calculatedCode = $this->getCode($secret, $currentTimeSlice + $i);

            if ($this->timingSafeEquals($calculatedCode, $code)) {
                return true;
            }
        }

        return false;
    }

    /**
     * @param $length
     * @return G2f_Model_Authentication
     */
    public function setCodeLength($length)
    {
        $this->length  = $length;

        return $this;
    }

    /**
     * @param $secret
     * @return false|string
     */
    protected function debase32($secret)
    {
        if (empty($secret)) {
            return '';
        }

        $base32charsFlipped = array_flip($this->_chars);

        $paddingCharCount = substr_count($secret, $this->_chars[32]);
        $allowedValues = array(6, 4, 3, 1, 0);
        if (!in_array($paddingCharCount, $allowedValues)) {
            return false;
        }
        for ($i = 0; $i < 4; ++$i) {
            if ($paddingCharCount == $allowedValues[$i] &&
                substr($secret, -($allowedValues[$i])) != str_repeat($this->_chars[32], $allowedValues[$i])) {
                return false;
            }
        }
        $secret = str_replace('=', '', $secret);
        $secret = str_split($secret);
        $binaryString = '';
        for ($i = 0; $i < count($secret); $i = $i + 8) {
            $x = '';
            if (!in_array($secret[$i], $this->_chars)) {
                return false;
            }
            for ($j = 0; $j < 8; ++$j) {
                $x .= str_pad(base_convert(@$base32charsFlipped[@$secret[$i + $j]], 10, 2), 5, '0', STR_PAD_LEFT);
            }
            $eightBits = str_split($x, 8);
            for ($z = 0; $z < count($eightBits); ++$z) {
                $binaryString .= (($y = chr(base_convert($eightBits[$z], 2, 10))) || ord($y) == 48) ? $y : '';
            }
        }

        return $binaryString;
    }


    /**
     * @param $safeString
     * @param $userString
     * @return bool
     */
    private function timingSafeEquals($safeString, $userString)
    {
        if (function_exists('hash_equals')) {
            return hash_equals($safeString, $userString);
        }
        $safeLen = strlen($safeString);
        $userLen = strlen($userString);

        if ($userLen != $safeLen) {
            return false;
        }

        $result = 0;

        for ($i = 0; $i < $userLen; ++$i) {
            $result |= (ord($safeString[$i]) ^ ord($userString[$i]));
        }
        return $result === 0;
    }

}//End of class