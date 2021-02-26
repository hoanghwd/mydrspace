<?php

class Core_Search_Helper_Data extends Core_Helper_Abstract
{
    /**
     * Query variable name
     */
    const QUERY_VAR_NAME = 'q';

    /*
     * Maximum query length
     */
    const MAX_QUERY_LEN  = 200;

    /**
     * Query object
     *
     * @var Core_Search_Model_Query
     */
    protected $_query;

    /**
     * Query string
     *
     * @var string
     */
    protected $_queryText;

    /**
     * Note messages
     *
     * @var array
     */
    protected $_messages = array();

    /**
     * Is a maximum length cut
     *
     * @var bool
     */
    protected $_isMaxLength = false;

    /**
     * Search engine model
     *
     * @var Core_Search_Model_Resource_Fulltext_Engine
     */
    protected $_engine;



    /**
     * Retrieve result page url and set "secure" param to avoid confirm
     * message when we submit form from secure page to unsecure
     *
     * @param   string $query
     * @return  string
     */
    public function getResultUrl($query = null)
    {

        return $this->_getUrl('catalogsearch/result', array(
            '_query' => array(self::QUERY_VAR_NAME => $query),
            '_secure' =>  $this->_getApp()->getFrontController()->getRequest()->isSecure()
        ));

    }

    /**
     * Retrieve maximum query length
     *
     * @param mixed $store
     * @return int|string
     */
    public function getMaxQueryLength($store = null)
    {
        return Virtual::getStoreConfig(Core_Search_Model_Query::XML_PATH_MAX_QUERY_LENGTH);
    }

    /**
     * Retrieve HTML escaped search query
     *
     * @return string
     */
    public function getEscapedQueryText()
    {
        return $this->escapeHtml($this->getQueryText());
    }

    /**
     * Retrieve search query text
     *
     * @return string
     */
    public function getQueryText()
    {
        if (!isset($this->_queryText)) {
            $this->_queryText = $this->_getRequest()->getParam($this->getQueryParamName());
            if ($this->_queryText === null) {
                $this->_queryText = '';
            }
            else {
                /**
                 * @var Core_Helper_String $stringHelper
                 */
                $stringHelper = Virtual::helper('core/string');
                $this->_queryText = is_array($this->_queryText) ? '' : $stringHelper->cleanString(trim($this->_queryText));
                $maxQueryLength = $this->getMaxQueryLength();
                if ($maxQueryLength !== '' && $stringHelper->strlen($this->_queryText) > $maxQueryLength) {
                    $this->_queryText = $stringHelper->substr($this->_queryText, 0, $maxQueryLength);
                    $this->_isMaxLength = true;
                }
            }

        }

        return $this->_queryText;
    }

    /**
     * @param $route
     * @param array $params
     * @return mixed
     * @throws Exception
     */
    protected function _getUrl($route, $params = array())
    {
        return Virtual::getUrl($route, $params);
    }

    /**
     * Retrieve suggest url
     *
     * @return string
     */
    public function getSuggestUrl()
    {
        return $this->_getUrl('search/ajax/suggest', array(
            '_secure' => $this->_getApp()->getFrontController()->getRequest()->isSecure()
        ));
    }

    /**
     * Retrieve search query parameter name
     *
     * @return string
     */
    public function getQueryParamName()
    {
        return self::QUERY_VAR_NAME;
    }

    /**
     * @return Core_Model_App
     * @throws Zend_Controller_Request_Exception
     * @throws Zend_Controller_Response_Exception
     */
    protected function _getApp()
    {
        return Virtual::app();
    }


}//End of class