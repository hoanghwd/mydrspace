<?php
/**
 * Created by PhpStorm.
 * User: hdo
 * Date: 1/2/2019
 * Time: 8:56 AM
 */

abstract class Core_Helper_Abstract
{
    /**
     * Helper module name
     *
     * @var string
     */
    protected $_moduleName;

    /**
     * Request object
     *
     * @var Zend_Controller_Request_Http
     */
    protected $_request;

    /**
     * Layout model object
     *
     * @var Core_Model_Layout
     */
    protected $_layout;

    /**
     * @deprecated after 1.4.0.0-rc1
     * @see self::escapeHtml()
     */
    public function htmlEscape($data, $allowedTags = null)
    {
        return $this->escapeHtml($data, $allowedTags);
    }

    /**
     * @return Core_Controller_Request_Http|Zend_Controller_Request_Http
     * @throws Zend_Controller_Request_Exception
     * @throws Zend_Controller_Response_Exception
     */
    protected function _getRequest()
    {
        if (!$this->_request) {
            $this->_request = Virtual::app()->getRequest();
        }
        return $this->_request;
    }

    /**
     * Escape html entities
     *
     * @param   mixed $data
     * @param   array $allowedTags
     * @return  mixed
     */
    public function escapeHtml($data, $allowedTags = null)
    {
        if (is_array($data)) {
            $result = array();
            foreach ($data as $item) {
                $result[] = $this->escapeHtml($item);
            }
        } else {
            // process single item
            if (strlen($data)) {
                if (is_array($allowedTags) and !empty($allowedTags)) {
                    $allowed = implode('|', $allowedTags);
                    $result = preg_replace('/<([\/\s\r\n]*)(' . $allowed . ')([\/\s\r\n]*)>/si', '##$1$2$3##', $data);
                    $result = htmlspecialchars($result, ENT_COMPAT, 'UTF-8', false);
                    $result = preg_replace('/##([\/\s\r\n]*)(' . $allowed . ')([\/\s\r\n]*)##/si', '<$1$2$3>', $result);
                } else {
                    $result = htmlspecialchars($data, ENT_COMPAT, 'UTF-8', false);
                }
            } else {
                $result = $data;
            }
        }
        return $result;
    }

    /**
     * Remove html tags, but leave "<" and ">" signs
     *
     * @param   string $html
     * @return  string
     */
    public function removeTags($html)
    {
        $html = preg_replace_callback(
            "# <(?![/a-z]) | (?<=\s)>(?![a-z]) #xi",
            function ($matches) {
                return htmlentities($matches[0]);
            },
            $html
        );
        $html =  strip_tags($html);

        return htmlspecialchars_decode($html);
    }

    /**
     * Wrapper for standart strip_tags() function with extra functionality for html entities
     *
     * @param string $data
     * @param string $allowableTags
     * @param bool $escape
     * @return string
     */
    public function stripTags($data, $allowableTags = null, $escape = false)
    {
        $result = strip_tags($data, $allowableTags);
        return $escape ? $this->escapeHtml($result, $allowableTags) : $result;
    }

    /**
     * @deprecated after 1.4.0.0-rc1
     * @see self::escapeHtml()
     */
    public function urlEscape($data)
    {
        return $this->escapeUrl($data);
    }

    /**
     * Escape html entities in url
     *
     * @param string $data
     * @return string
     */
    public function escapeUrl($data)
    {
        return htmlspecialchars($data);
    }

    /**
     * Escape quotes in java script
     *
     * @param mixed $data
     * @param string $quote
     * @return mixed
     */
    public function jsQuoteEscape($data, $quote='\'')
    {
        if (is_array($data)) {
            $result = array();
            foreach ($data as $item) {
                $result[] = str_replace($quote, '\\'.$quote, $item);
            }
            return $result;
        }
        return str_replace($quote, '\\'.$quote, $data);
    }

    /**
     * Escape quotes inside html attributes
     * Use $addSlashes = false for escaping js that inside html attribute (onClick, onSubmit etc)
     *
     * @param string $data
     * @param bool $addSlashes
     * @return string
     */
    public function quoteEscape($data, $addSlashes = false)
    {
        if ($addSlashes === true) {
            $data = addslashes($data);
        }
        return htmlspecialchars($data, ENT_QUOTES, null, false);
    }

    /**
     *  base64_encode() for URLs encoding
     *
     *  @param    string $url
     *  @return   string
     */
    public function urlEncode($url)
    {
        return strtr(base64_encode($url), '+/=', '-_,');
    }

    /**
     *   Translate array
     *
     *  @param    array $arr
     *  @return   array
     */
    public function translateArray($arr = array())
    {
        foreach ($arr as $k => $v) {
            if (is_array($v)) {
                $v = self::translateArray($v);
            } elseif ($k === 'label') {
                $v = self::__($v);
            }
            $arr[$k] = $v;
        }
        return $arr;
    }

    /**
     * Declare layout
     *
     * @param   Core_Model_Layout $layout
     * @return  Core_Helper_Abstract
     */
    public function setLayout($layout)
    {
        $this->_layout = $layout;
        return $this;
    }

    /**
     * Retrieve layout model object
     *
     * @return Core_Model_Layout
     */
    public function getLayout()
    {
        return $this->_layout;
    }

}//End of class