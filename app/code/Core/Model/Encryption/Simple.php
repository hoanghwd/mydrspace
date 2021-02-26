
<?php

class Core_Model_Encryption_Simple
{

    private $_salt;

    /**
     * @var Core_Model_Encryption
     */
    protected $_helper;

    /**
     * Vbox_Core_Encryption_Simple constructor.
     */
    function __construct()
    {
        $this->_salt = $this->_getSalt();
    }

    /**
     * Get salt;
     * @return mixed
     */
    protected function _getSalt()
    {
        return Virtual::getConfig()->getNode()->default->encryption->key->asArray();
    }

    /**
     * Generate a [salted] hash.
     *
     *
     * @param string $password
     * @return string
     */
    public function getHash($password)
    {
        return $this->hash($this->_salt . $password) . ':' . $this->_salt;
    }

    /**
     * Set helper instance
     *
     * @param Core_Model_Encryption $helper
     * @return Core_Model_Encryption_Simple
     */
    public function setHelper($helper)
    {
        $this->_helper = $helper;

        return $this;
    }

}//End of class