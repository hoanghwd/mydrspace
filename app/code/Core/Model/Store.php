<?php


/**
 * Store model
 *
 * @method Core_Model_Resource_Store _getResource()
 * @method Core_Model_Resource_Store getResource()
 * @method Core_Model_Store setCode(string $value)
 * @method Core_Model_Store setWebsiteId(int $value)
 * @method Core_Model_Store setGroupId(int $value)
 * @method Core_Model_Store setName(string $value)
 * @method int getSortOrder()
 * @method Core_Model_Store setSortOrder(int $value)
 * @method Core_Model_Store setIsActive(int $value)
 *
 * @package     Core
 * @author      Magento Core Team <core@magentocommerce.com>
 */
class Core_Model_Store extends Core_Model_Abstract
{
    /**
     * Entity name
     */
    const ENTITY = 'core_store';

    /**
     * Configuration pathes
     */
    const XML_PATH_STORE_STORE_NAME       = 'general/store_information/name';
    /**
     *
     */
    const XML_PATH_STORE_STORE_PHONE      = 'general/store_information/phone';
    /**
     *
     */
    const XML_PATH_STORE_STORE_HOURS      = 'general/store_information/hours';
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
    const XML_PATH_SECURE_IN_ADMINHTML    = 'web/secure/use_in_adminhtml';
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
    const XML_PATH_PRICE_SCOPE            = 'catalog/price/scope';

    /**
     * Price scope constants
     */
    const PRICE_SCOPE_GLOBAL              = 0;
    /**
     *
     */
    const PRICE_SCOPE_WEBSITE             = 1;

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
    const URL_TYPE_WEB                    = 'web';
    /**
     *
     */
    const URL_TYPE_SKIN                   = 'skin';
    /**
     *
     */
    const URL_TYPE_JS                     = 'js';
    /**
     *
     */
    const URL_TYPE_MEDIA                  = 'media';

    /**
     * Code constants
     */
    const DEFAULT_CODE                    = 'default';
    /**
     *
     */
    const ADMIN_CODE                      = 'admin';

    /**
     * Cache tag
     */
    const CACHE_TAG                       = 'store';

    /**
     * Cookie name
     */
    const COOKIE_NAME                     = 'store';

    /**
     * Cookie currency key
     */
    const COOKIE_CURRENCY                 = 'currency';

    /**
     * Script name, which returns all the images
     */
    const MEDIA_REWRITE_SCRIPT            = 'get.php/';

    /**
     * Cache flag
     *
     * @var boolean
     */
    protected $_cacheTag    = true;

    /**
     * Event prefix for model events
     *
     * @var string
     */
    protected $_eventPrefix = 'store';

    /**
     * Event object name
     *
     * @var string
     */
    protected $_eventObject = 'store';

    /**
     * Price filter
     *
     * @var Directory_Model_Currency_Filter
     */
    protected $_priceFilter;

    /**
     * Website model
     *
     * @var Core_Model_Website
     */
    protected $_website;

    /**
     * Group model
     *
     * @var Core_Model_Store_Group
     */
    protected $_group;

    /**
     * Store configuration cache
     *
     * @var array|null
     */
    protected $_configCache = null;

    /**
     * Base nodes of store configuration cache
     *
     * @var array
     */
    protected $_configCacheBaseNodes = array();

    /**
     * Directory cache
     *
     * @var array
     */
    protected $_dirCache = array();

    /**
     * URL cache
     *
     * @var array
     */
    protected $_urlCache = array();

    /**
     * Base URL cache
     *
     * @var array
     */
    protected $_baseUrlCache = array();

    /**
     * Session entity
     *
     * @var Core_Model_Session_Abstract
     */
    protected $_session;

    /**
     * Flag that shows that backend URLs are secure
     *
     * @var boolean|null
     */
    protected $_isAdminSecure = null;

    /**
     * Flag that shows that frontend URLs are secure
     *
     * @var boolean|null
     */
    protected $_isFrontSecure = null;

    /**
     * Store frontend name
     *
     * @var string|null
     */
    protected $_frontendName = null;

    /**
     * Readonly flag
     *
     * @var bool
     */
    private $_isReadOnly = false;


    /**
     * Initialize object
     */
    protected function _construct()
    {
        $this->_init('core/store');

    }

    /**
     * Retrieve store configuration data
     *
     * @param   string $path
     * @return  string|null
     */
    public function getConfig($path)
    {
        return Virtual::getStoreConfig($path);
    }

    /**
     * Retrieve base URL
     *
     * @param string $type
     * @param boolean|null $secure
     * @return string
     */
    public function getBaseUrl($type = self::URL_TYPE_LINK, $secure = null)
    {
        $secure = IS_SECURE;

        switch ($type) {

            case self::URL_TYPE_WEB:
                $url = $this->getConfig('web/' . ($secure ? 'secure' : 'unsecure') . '/base_url');
                break;

            case self::URL_TYPE_LINK:
                $url = $this->getConfig('web/' . ($secure ? 'secure' : 'unsecure') . '/base_link_url');
                $url = $this->_updatePathUseRewrites($url);
                $url = $this->_updatePathUseStoreView($url);

            case self::URL_TYPE_DIRECT_LINK:
                $url = $this->getConfig('web/' . ($secure ? 'secure' : 'unsecure') . '/base_link_url');
                $url = $this->_updatePathUseRewrites($url);
                break;

            case self::URL_TYPE_SKIN:
            case self::URL_TYPE_JS:
                $url = $this->getConfig('web/' . ($secure ? 'secure' : 'unsecure') . '/base_' . $type . '_url');
                break;

            case self::URL_TYPE_MEDIA:
                $url = $this->_updateMediaPathUseRewrites($secure);
                break;
        }//switch

        if (false !== strpos($url, '{{base_url}}')) {
            $baseUrl = Virtual::getConfig()->substDistroServerVars('{{base_url}}');
            $url = str_replace('{{base_url}}', $baseUrl, $url);
        }

        return $url;
    }

    /**
     * Add store code to url in case if it is enabled in configuration
     *
     * @param   string $url
     * @return  string
     */
    protected function _updatePathUseStoreView($url)
    {
        if ($this->getStoreInUrl()) {
            $url .= $this->getCode() . '/';
        }

        return $url;
    }

    /**
     * Remove script file name from url in case when server rewrites are enabled
     *
     * @param   string $url
     * @return  string
     */
    protected function _updatePathUseRewrites($url)
    {
        if ( !$this->getConfig(self::XML_PATH_USE_REWRITES)) {
            $indexFileName = $this->_isCustomEntryPoint() ? 'index.php' : basename($_SERVER['SCRIPT_FILENAME']);
            $url .= $indexFileName . '/';
        }

        return $url;
    }

    /**
     * Returns whether url forming scheme prepends url path with store view code
     *
     * @return bool
     */
    public function getStoreInUrl()
    {
        return $this->getConfig(self::XML_PATH_STORE_IN_URL);
    }

    /**
     * Check if used entry point is custom
     *
     * @return bool
     */
    protected function _isCustomEntryPoint()
    {
        return (bool)Virtual::registry('custom_entry_point');
    }


}//End of class