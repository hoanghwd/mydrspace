<?php
/**
 * Created by PhpStorm.
 * User: hdo
 * Date: 3/29/2019
 * Time: 12:57 PM
 */

class Core_Model_Virtual extends Core_Model_Abstract
{
    const XML_ROOT = 'default';

    /**
     * Possible URL types
     */
    const URL_TYPE_LINK                   = 'link';
    /**
     *
     */
    const URL_TYPE_DIRECT_LINK            = 'direct_link';
    /**
     *
     */
    const URL_TYPE_WEB                    = '';
    /**
     *
     */
    const URL_TYPE_SKIN                   = 'skin';
    /**
     *
     */
    const URL_TYPE_JS                     = 'js';

    /**
     * Code constants
     */
    const DEFAULT_CODE                    = 'default';

    /**
     * Cookie name
     */
    const COOKIE_NAME                     = 'store';

    /**
     *
     */
    const XML_PATH_STORE_IN_URL           = 'web/url/use_store';
    /**
     *
     */
    const XML_PATH_USE_REWRITES           = 'web/seo/use_rewrites';
    /**
     *
     */
    const XML_PATH_UNSECURE_BASE_URL      = 'web/unsecure/base_url';
    /**
     *
     */
    const XML_PATH_SECURE_BASE_URL        = 'web/secure/base_url';
    /**
     *
     */
    const XML_PATH_SECURE_IN_FRONTEND     = 'web/secure/use_in_frontend';

    /**
     *
     */
    const XML_PATH_SECURE_BASE_LINK_URL   = 'web/secure/base_link_url';
    /**
     *
     */
    const XML_PATH_UNSECURE_BASE_LINK_URL = 'web/unsecure/base_link_url';
    /**
     *
     */
    const XML_PATH_OFFLOADER_HEADER       = 'web/secure/offloader_header';

    /**
     *
     */
    const XML_PATH_COOKIE_LIFE_TIME       =  'web/cookie/cookie_lifetime';

    /**
     * Retrieve store configuration data
     *
     * @param   string $path
     * @return  string|null
     */
    public function getConfig($path)
    {
        $fullPath = self::XML_ROOT.'/' . $path;
        $data = Virtual::getConfig()->getNode($fullPath);

        if (!$data ) {
            return null;
        }

        return $this->_processConfigValue($fullPath, $path, $data);
    }

    /**
     * Process config value
     *
     * @param string $fullPath
     * @param string $path
     * @param Varien_Simplexml_Element $node
     * @return string
     */
    protected function _processConfigValue($fullPath, $path, $node)
    {
        if ($node->hasChildren()) {
            $aValue = array();
            foreach ($node->children() as $k => $v) {
                $aValue[$k] = $this->_processConfigValue($fullPath . '/' . $k, $path . '/' . $k, $v);
            }

            return $aValue;
        }

        $sValue = (string) $node;
        if (is_string($sValue) && strpos($sValue, '{{') !== false) {
            if (strpos($sValue, '{{unsecure_base_url}}') !== false) {
                $unsecureBaseUrl = $this->getConfig(self::XML_PATH_UNSECURE_BASE_URL).DS;
                $sValue = str_replace('{{unsecure_base_url}}', $unsecureBaseUrl, $sValue);
            }
            elseif (strpos($sValue, '{{secure_base_url}}') !== false) {
                $secureBaseUrl = $this->getConfig(self::XML_PATH_SECURE_BASE_URL).DS;
                $sValue = str_replace('{{secure_base_url}}', $secureBaseUrl, $sValue);
            }
        }

        return $sValue;
    }

    /**
     * Set config value for CURRENT model
     *
     * This value don't save in config
     *
     * @param string $path
     * @param mixed $value
     * @return Core_Model_Virtual
     */
    public function setConfig($path, $value)
    {

        $fullPath = self::XML_ROOT.'/' . $path;
        Virtual::getConfig()->setNode($fullPath, $value);

        return $this;
    }

    /**
     * @return string
     * @throws Zend_Controller_Request_Exception
     */
    public function getDefaultBasePath()
    {
        if (!isset($_SERVER['SCRIPT_NAME'])) {
            return '/';
        }

        return rtrim(Virtual::app()->getRequest()->getBasePath() . '/') . '/';
    }

    /**
     * @param string $type
     * @param bool $secure
     * @return string
     * @throws Exception
     */
    public function getBaseUrl($type = self::URL_TYPE_LINK, $secure = IS_SECURE)
    {
        $url = '';
        switch ($type) {
            case self::URL_TYPE_WEB:
                $secure = is_null($secure) ? $this->isCurrentlySecure() : (bool)$secure;
                $url = $this->getConfig('web/' . ($secure ? 'secure' : 'unsecure') . '/base_url');
                break;

            case self::URL_TYPE_LINK:
                $url = $this->getConfig('web/' . ($secure ? 'secure' : 'unsecure') . '/base_link_url');
                break;

            /*
                case self::URL_TYPE_DIRECT_LINK:
                $url = $this->getConfig('web/' . ($secure ? 'secure' : 'unsecure') . '/base_link_url');
                break;
            */

            case self::URL_TYPE_SKIN:
            case self::URL_TYPE_JS:                
                $secure = is_null($secure) ? $this->isCurrentlySecure() : (bool) $secure;
                $url = $this->getConfig('web/' . ($secure ? 'secure' : 'unsecure') . '/base_' . $type . '_url');
                break;

            default:
                Virtual::throwException('Invalid base url type');
        }//switch

        return rtrim($url, '/') . '/';
    }

    /**
     * @return bool
     */
    public function isFrontUrlSecure()
    {
        return IS_SECURE;
    }

    /**
     * Check if request was secure
     *
     * @return boolean
     */
    public function isCurrentlySecure()
    {
        return $this->isFrontUrlSecure();
    }

    /**
     * @return string
     */
    public function getCode()
    {
        return self::DEFAULT_CODE;
    }

}//End of class