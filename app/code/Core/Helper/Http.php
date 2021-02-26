<?php

/**
 * Class Core_Helper_Http
 *
 * @category    Virtual
 * @package     Core
 */

class Core_Helper_Http extends Core_Helper_Abstract
{
    const XML_NODE_REMOTE_ADDR_HEADERS  = 'global/remote_addr_headers';

    protected function _getRequest()
    {
        if (!$this->_request) {
            $this->_request = Virtual::app()->getRequest();
        }
        return $this->_request;
    }

    /**
     * Remote address cache
     *
     * @var string
     */
    protected $_remoteAddr;

    /**
     * Retrieve Server IP address
     *
     * @param bool $ipToLong converting IP to long format
     * @return string IPv4|long
     */
    public function getServerAddr($ipToLong = false)
    {
        $address = $this->_getRequest()->getServer('SERVER_ADDR');
        if (!$address) {
            return false;
        }
        return $ipToLong ? inet_pton($address) : $address;
    }

    /**
     * Retrieve Client Remote Address
     *
     * @param bool $ipToLong converting IP to long format
     * @return string IPv4|long
     */
    public function getRemoteAddr($ipToLong = false)
    {
        if (is_null($this->_remoteAddr)) {

            if (!$this->_remoteAddr) {
                $this->_remoteAddr = $this->_getRequest()->getServer('REMOTE_ADDR');
            }
        }

        if (!$this->_remoteAddr) {
            return false;
        }

        if (strpos($this->_remoteAddr, ',') !== false) {
            $ipList = explode(',', $this->_remoteAddr);
            $this->_remoteAddr = trim(reset($ipList));
        }

        return $ipToLong ? inet_pton($this->_remoteAddr) : $this->_remoteAddr;
    }

    /**
     * Retrieve HTTP HOST
     *
     * @param boolean $clean clean non UTF-8 characters
     * @return string
     */
    public function getHttpHost($clean = true)
    {
        return $this->_getHttpCleanValue('HTTP_HOST', $clean);
    }

    /**
     * Retrieve HTTP "clean" value
     *
     * @param string $var
     * @param boolean $clean clean non UTF-8 characters
     * @return string
     */
    protected function _getHttpCleanValue($var, $clean = true)
    {
        $value = $this->_getRequest()->getServer($var, '');
        if ($clean) {
            $value = Virtual::helper('core/string')->cleanString($value);
        }

        return $value;
    }

}//End of class