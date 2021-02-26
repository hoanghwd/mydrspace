<?php

class Core_Helper_Cookie extends Core_Helper_Abstract
{
    /**
     * Cookie name for users who allowed cookie save
     */
    const IS_USER_ALLOWED_SAVE_COOKIE  = 'user_allowed_save_cookie';

    /**
     * Path to configuration, check is enable cookie restriction mode
     */
    const XML_PATH_COOKIE_RESTRICTION  = 'web/cookie/cookie_restriction';



    /**
     * Cookie restriction lifetime configuration path
     */
    const XML_PATH_COOKIE_RESTRICTION_LIFETIME = 'web/cookie/cookie_restriction_lifetime';

    /**
     * Cookie restriction notice cms block identifier
     */
    const COOKIE_RESTRICTION_NOTICE_CMS_BLOCK_IDENTIFIER = 'cookie_restriction_notice_block';

    /**
     * Cookie instance
     *
     * @var Core_Model_Cookie
     */
    protected $_cookieModel;

    /**
     * Store instance
     *
     * @var Core_Model_Store
     */
    protected $_currentStore;


    /**
     * Website instance
     *
     * @var Core_Model_Website
     */
    protected $_website;

    public function __construct(array $data = array())
    {
        $this->_currentStore = isset($data['current_store']) ? $data['current_store'] : Virtual::app()->getStore();

        if (!$this->_currentStore instanceof Core_Model_Store) {
            throw new InvalidArgumentException('Required store object is invalid');
        }

        $this->_cookieModel = isset($data['cookie_model']) ? $data['cookie_model'] : Virtual::getSingleton('core/cookie');

        if (!$this->_cookieModel instanceof Core_Model_Cookie) {
            throw new InvalidArgumentException('Required cookie object is invalid');
        }

        $this->_website = isset($data['website']) ? $data['website'] : Virtual::app()->getWebsite();

        if (!$this->_website instanceof Core_Model_Website) {
            throw new InvalidArgumentException('Required website object is invalid');
        }
    }

    /**
     * Check if cookie restriction notice should be displayed
     *
     * @return bool
     */
    public function isUserNotAllowSaveCookie()
    {
        return Virtual::getStoreConfig(self::XML_PATH_COOKIE_RESTRICTION);
    }

    /**
     * Get cookie restriction notice cms block identifier
     *
     * @return string
     */
    public function getCookieRestrictionNoticeCmsBlockIdentifier()
    {
        return self::COOKIE_RESTRICTION_NOTICE_CMS_BLOCK_IDENTIFIER;
    }

    /**
     * Return serialized list of accepted save cookie website
     *
     * @return string
     */
    public function getAcceptedSaveCookiesWebsiteIds()
    {
        $acceptedSaveCookiesWebsites = $this->_getAcceptedSaveCookiesWebsites();
        $acceptedSaveCookiesWebsites[$this->_website->getId()] = 1;

        return json_encode($acceptedSaveCookiesWebsites);
    }

    /**
     * Get accepted save cookies websites
     *
     * @return array
     */
    protected function _getAcceptedSaveCookiesWebsites()
    {
        $serializedList = $this->_cookieModel->get(self::IS_USER_ALLOWED_SAVE_COOKIE);
        $unSerializedList = json_decode($serializedList, true);

        return is_array($unSerializedList) ? $unSerializedList : array();
    }

    /**
     * Get cookie restriction lifetime (in seconds)
     *
     * @return int
     */
    public function getCookieRestrictionLifetime()
    {
        return (int)$this->_currentStore->getConfig(self::XML_PATH_COOKIE_RESTRICTION_LIFETIME);
    }

}//End of class