<?php

class Core_Model_Url_Rewrite extends Core_Model_Abstract implements Core_Model_Url_Rewrite_Interface
{
    const REWRITE_REQUEST_PATH_ALIAS = 'rewrite_request_path';


    /**
     *
     */
    protected function _construct()
    {
        $this->_init('core/url_rewrite');
    }

    /**
     * Load rewrite information for request
     * If $path is array - we must load possible records and choose one matching earlier record in array
     *
     * @param   mixed $path
     * @return  Core_Model_Url_Rewrite
     */
    public function loadByRequestPath($path)
    {

    }

}//End of class