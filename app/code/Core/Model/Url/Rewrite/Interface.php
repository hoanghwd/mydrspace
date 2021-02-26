<?php

/**
 * Url rewrite interface
 *
 * @package Core
 */
interface Core_Model_Url_Rewrite_Interface
{
    /**
     * Load rewrite information for request
     *
     * @param array|string $path
     * @return Core_Model_Url_Rewrite_Interface
     */
    public function loadByRequestPath($path);
}
