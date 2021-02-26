<?php

class Core_Helper_String extends Core_Helper_Abstract
{
    const ICONV_CHARSET = 'UTF-8';

    /**
     * @var Core_Helper_Array
     */
    protected $_arrayHelper;


    /**
     * Truncate a string to a certain length if necessary, appending the $etc string.
     * $remainder will contain the string that has been replaced with $etc.
     *
     * @param string $string
     * @param int $length
     * @param string $etc
     * @param string &$remainder
     * @param bool $breakWords
     * @return string
     */
    public function truncate($string, $length = 80, $etc = '...', &$remainder = '', $breakWords = true)
    {
        $remainder = '';
        if (0 == $length) {
            return '';
        }

        $originalLength = $this->strlen($string);
        if ($originalLength > $length) {
            $length -= $this->strlen($etc);
            if ($length <= 0) {
                return '';
            }
            $preparedString = $string;
            $preparedlength = $length;
            if (!$breakWords) {
                $preparedString = preg_replace('/\s+?(\S+)?$/u', '', $this->substr($string, 0, $length + 1));
                $preparedlength = $this->strlen($preparedString);
            }
            $remainder = $this->substr($string, $preparedlength, $originalLength);
            return $this->substr($preparedString, 0, $length) . $etc;
        }

        return $string;
    }

    /**
     * Retrieve string length using default charset
     *
     * @param string $string
     * @return int
     */
    public function strlen($string)
    {
        return iconv_strlen($string, self::ICONV_CHARSET);
    }

    /**
     * Passthrough to iconv_substr()
     *
     * @param string $string
     * @param int $offset
     * @param int $length
     * @return string
     */
    public function substr($string, $offset, $length = null)
    {
        $string = $this->cleanString($string);
        if (is_null($length)) {
            $length = $this->strlen($string) - $offset;
        }
        return iconv_substr($string, $offset, $length, self::ICONV_CHARSET);
    }

    /**
     * Split string and appending $insert string after $needle
     *
     * @param string $str
     * @param integer $length
     * @param string $needle
     * @param string $insert
     * @return string
     */
    public function splitInjection($str, $length = 50, $needle = '-', $insert = ' ')
    {
        $str = $this->str_split($str, $length);
        $newStr = '';
        foreach ($str as $part) {
            if ($this->strlen($part) >= $length) {
                $lastDelimetr = $this->strpos($this->strrev($part), $needle);
                $tmpNewStr = '';
                $tmpNewStr = $this->substr($this->strrev($part), 0, $lastDelimetr)
                    . $insert . $this->substr($this->strrev($part), $lastDelimetr);
                $newStr .= $this->strrev($tmpNewStr);
            } else {
                $newStr .= $part;
            }
        }
        return $newStr;
    }

    /**
     * Clean non UTF-8 characters
     *
     * @param string $string
     * @return string
     */
    public function cleanString($string)
    {
        return '"libiconv"' == ICONV_IMPL ?
            iconv(self::ICONV_CHARSET, self::ICONV_CHARSET . '//IGNORE', $string) : $string;
    }

    /**
     * Find position of first occurrence of a string
     *
     * @param string $haystack
     * @param string $needle
     * @param int $offset
     * @return int|false
     */
    public function strpos($haystack, $needle, $offset = null)
    {
        return iconv_strpos($haystack, $needle, $offset, self::ICONV_CHARSET);
    }

    /**
     * Parse query string to array
     *
     * @param string $str
     * @return array
     */
    public function parseQueryStr($str)
    {
        $argSeparator = '&';
        $result = array();
        $partsQueryStr = explode($argSeparator, $str);

        foreach ($partsQueryStr as $partQueryStr) {
            if ($this->_validateQueryStr($partQueryStr)) {
                $param = $this->_explodeAndDecodeParam($partQueryStr);
                $param = $this->_handleRecursiveParamForQueryStr($param);
                $result = $this->_appendParam($result, $param);
            }
        }
        return $result;
    }

    /**
     * Validate query pair string
     *
     * @param string $str
     * @return bool
     */
    protected function _validateQueryStr($str)
    {
        if (!$str || (strpos($str, '=') === false)) {
            return false;
        }
        return true;
    }

    /**
     * Prepare param
     *
     * @param string $str
     * @return array
     */
    protected function _explodeAndDecodeParam($str)
    {
        $preparedParam = array();
        $param = explode('=', $str);
        $preparedParam['key'] = urldecode(array_shift($param));
        $preparedParam['value'] = urldecode(array_shift($param));

        return $preparedParam;
    }

    /**
     * Append param to general result
     *
     * @param array $result
     * @param array $param
     * @return array
     */
    protected function _appendParam(array $result, array $param)
    {
        $key   = $param['key'];
        $value = $param['value'];

        if ($key) {
            if (is_array($value) && array_key_exists($key, $result)) {
                $helper = $this->getArrayHelper();
                $result[$key] = $helper->mergeRecursiveWithoutOverwriteNumKeys($result[$key], $value);
            } else {
                $result[$key] = $value;
            }
        }

        return $result;
    }

    /**
     * Handle param recursively
     *
     * @param array $param
     * @return array
     */
    protected function _handleRecursiveParamForQueryStr(array $param)
    {
        $value = $param['value'];
        $key = $param['key'];

        $subKeyBrackets = $this->_getLastSubkey($key);
        $subKey = $this->_getLastSubkey($key, false);
        if ($subKeyBrackets) {
            if ($subKey) {
                $param['value'] = array($subKey => $value);
            } else {
                $param['value'] = array($value);
            }
            $param['key'] = $this->_removeSubkeyPartFromKey($key, $subKeyBrackets);
            $param = $this->_handleRecursiveParamForQueryStr($param);
        }

        return $param;
    }

    /**
     * Remove subkey part from key
     *
     * @param string $key
     * @param string $subKeyBrackets
     * @return string
     */
    protected function _removeSubkeyPartFromKey($key, $subKeyBrackets)
    {
        return substr($key, 0, strrpos($key, $subKeyBrackets));
    }

    /**
     * Get last part key from query array
     *
     * @param string $key
     * @param bool $withBrackets
     * @return string
     */
    protected function _getLastSubkey($key, $withBrackets = true)
    {
        $subKey = '';
        $leftBracketSymbol  = '[';
        $rightBracketSymbol = ']';

        $firstPos = strrpos($key, $leftBracketSymbol);
        $lastPos  = strrpos($key, $rightBracketSymbol);

        if (($firstPos !== false || $lastPos !== false)
            && $firstPos < $lastPos
        ) {
            $keyLenght = $lastPos - $firstPos + 1;
            $subKey = substr($key, $firstPos, $keyLenght);
            if (!$withBrackets) {
                $subKey = ltrim($subKey, $leftBracketSymbol);
                $subKey = rtrim($subKey, $rightBracketSymbol);
            }
        }
        return $subKey;
    }

    /**
     * Set array helper
     *
     * @param Core_Helper_Abstract $helper
     * @return Core_Helper_String
     */
    public function setArrayHelper(Core_Helper_Abstract $helper)
    {
        $this->_arrayHelper = $helper;
        return $this;
    }

    /**
     * Get Array Helper
     *
     * @return Core_Helper_Array
     */
    public function getArrayHelper()
    {
        if (!$this->_arrayHelper) {
            $this->_arrayHelper = Virtual::helper('core/array');
        }
        return $this->_arrayHelper;
    }

    /**
     * @param $email
     * @return string
     */
    public function maskEmail($email)
    {
        $add = strpos($email, '@');
        $dot = strpos($email, '.');
        $masked = '';

        for ($i = 0; $i < strlen($email); $i++) {
            if( $i <= 1 || $i == $add || $i == $add + 1 || $i >= $dot) {
                $masked .= $email[$i];
            }
            else {
                $masked .= 'x';
            }
        }

        return $masked;
    }


}//End of class