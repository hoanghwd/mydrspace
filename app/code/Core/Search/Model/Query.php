<?php

class Core_Search_Model_Query extends Core_Model_Abstract
{
    /**
     * Event prefix
     *
     * @var string
     */
    protected $_eventPrefix = 'catalogsearch_query';

    /**
     * Event object key name
     *
     * @var string
     */
    protected $_eventObject = 'catalogsearch_query';

    const CACHE_TAG                     = 'SEARCH_QUERY';
    const XML_PATH_MIN_QUERY_LENGTH     = 'catalog/search/min_query_length';
    const XML_PATH_MAX_QUERY_LENGTH     = 'catalog/search/max_query_length';
    const XML_PATH_MAX_QUERY_WORDS      = 'catalog/search/max_query_words';
    const XML_PATH_AJAX_SUGGESTION_COUNT = 'catalog/search/show_autocomplete_results_count';
    const SEARCH_PLACE_HOLDER            = 'Search entire website here...';
    
    
}