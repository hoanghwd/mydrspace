<?php
/**
 * Created by PhpStorm.
 * User: hdo
 * Date: 12/30/2018
 * Time: 5:59 PM
 */

class Core_Model_Encryption extends  Core_Model_Encryption_Simple
{
    const HASH_VERSION_MD5    = 0;
    const HASH_VERSION_SHA256 = 1;
    const HASH_VERSION_LATEST = 1;

    const CIPHER_BLOWFISH     = 0;
    const CIPHER_RIJNDAEL_128 = 1;
    const CIPHER_RIJNDAEL_256 = 2;
    const CIPHER_LATEST       = 2;

    protected $_cipher = self::CIPHER_LATEST;
    protected $_crypts = array();

    protected $_keyVersion;
    protected $_keys = array();

    protected $_iv = '';

    public function __construct()
    {
        // load all possible keys
        $this->_keys = preg_split('/\s+/s', (string)trim($this->_getSalt()));
        $this->_keyVersion = count($this->_keys) - 1;
    }

    /**
     * Check whether specified cipher version is supported
     *
     * Returns matched supported version or throws exception
     *
     * @param int $version
     * @return int
     * @throws Exception
     */
    public function validateCipher($version)
    {
        $version = (int)$version;
        if (!in_array($version, array(self::CIPHER_BLOWFISH, self::CIPHER_RIJNDAEL_128, self::CIPHER_RIJNDAEL_256), true)) {
            echo ('Not supported cipher version');
        }

        return $version;
    }

    /**
     * Validate hash against all supported versions.
     *
     * Priority is by newer version.
     *
     * @param string $password
     * @param string $hash
     * @return bool
     */
    public function validateHash($password, $hash)
    {
        return
            $this->validateHashByVersion($password, $hash, self::HASH_VERSION_SHA256) ||
            $this->validateHashByVersion($password, $hash, self::HASH_VERSION_MD5);
    }

    /**
     * Hash a string
     *
     * @param string $data
     * @param int $version
     * @return string
     */
    public function hash($data, $version = self::HASH_VERSION_LATEST)
    {
        if (self::HASH_VERSION_MD5 === $version) {
            return md5($data);
        }

        return hash('sha256', $data);
    }

    /**
     * Validate hash by specified version
     *
     * @param string $password
     * @param string $hash
     * @param int $version
     * @return bool
     */
    public function validateHashByVersion($password, $hash, $version = self::HASH_VERSION_LATEST)
    {
        // look for salt
        $hashArr = explode(':', $hash, 2);
        //var_dump($hashArr);

        if (1 === count($hashArr)) {
            //echo $this->hash('demo', $version);echo '<br/>';
            return hash_equals($this->hash($password, $version), $hash);
        }
        list($hash, $salt) = $hashArr;

        return hash_equals($this->hash($this->_getSalt() . $password, $version), $hash);
    }

    /**
     * @param $password
     * @param int $version
     * @return string
     */
    public function generatePassword($password, $version = self::HASH_VERSION_LATEST)
    {
        return $this->hash($password, $version);
    }

    /**
     * Attempt to append new key & version
     *
     * @param  $key
     * @return Core_Model_Encryption
     */
    public function setNewKey($key)
    {
        parent::validateKey($key);
        $this->_keys[] = $key;
        $this->_keyVersion += 1;

        return $this;
    }

    /**
     * Export current keys as string
     *
     * @return string
     */
    public function exportKeys()
    {
        return implode("\n", $this->_keys);
    }

    /**
     * @param null $key
     * @param null $cipherVersion
     * @return mixed
     * @throws Exception
     */
    protected function _getCrypt($key = null, $cipherVersion = null)
    {
        if (null === $key && null == $cipherVersion) {
            $cipherVersion = self::CIPHER_RIJNDAEL_256;
        }

        if (null === $key) {
            $key = $this->_keys[$this->_keyVersion];
        }
        if (null === $cipherVersion) {
            $cipherVersion = $this->_cipher;
        }
        $cipherVersion = $this->validateCipher($cipherVersion);


        $this->_crypts[$key][$cipherVersion] = Varien_Crypt::factory();
        $this->_crypts[$key][$cipherVersion]->setMode(MCRYPT_MODE_ECB);
        $this->_crypts[$key][$cipherVersion]->setCipher(MCRYPT_BLOWFISH);


        if ($cipherVersion === self::CIPHER_RIJNDAEL_128) {
            $this->_crypts[$key][$cipherVersion]->setCipher(MCRYPT_RIJNDAEL_128);
        }
        elseif ($cipherVersion === self::CIPHER_RIJNDAEL_256) {
            $this->_crypts[$key][$cipherVersion]->setCipher(MCRYPT_RIJNDAEL_128);
            $this->_crypts[$key][$cipherVersion]->setMode(MCRYPT_MODE_CBC);
            $this->_crypts[$key][$cipherVersion]->setInitVector($this->_iv);
        }
        $this->_crypts[$key][$cipherVersion]->init($key);

        return $this->_crypts[$key][$cipherVersion];
    }

    /**
     * Look for key and crypt versions in encrypted data before decrypting
     *
     * Unsupported/unspecified key version silently fallback to the oldest we have
     * Unsupported cipher versions eventually throw exception
     * Unspecified cipher version fallback to the oldest we support
     *
     * @param $data
     * @return mixed|string
     * @throws Exception
     */
    public function decrypt($data)
    {
        if ($data) {
            $parts = explode(':', $data, 4);
            $partsCount = count($parts);

            // specified key, specified crypt, specified iv
            if (4 === $partsCount) {
                list($keyVersion, $cryptVersion, $iv, $data) = $parts;
                $this->_iv    = $iv ? $iv : null;
                $keyVersion   = (int)$keyVersion;
                $cryptVersion = self::CIPHER_RIJNDAEL_256;
            }
            // specified key, specified crypt
            elseif (3 === $partsCount) {
                list($keyVersion, $cryptVersion, $data) = $parts;
                $keyVersion   = (int)$keyVersion;
                $cryptVersion = (int)$cryptVersion;
                $this->_iv = null;
            }
            // no key version = oldest key, specified crypt
            elseif (2 === $partsCount) {
                list($cryptVersion, $data) = $parts;
                $keyVersion   = 0;
                $cryptVersion = (int)$cryptVersion;
                $this->_iv = null;
            }
            // no key version = oldest key, no crypt version = oldest crypt
            elseif (1 === $partsCount) {
                $keyVersion   = 0;
                $cryptVersion = self::CIPHER_BLOWFISH;
                $this->_iv = null;
            }
            // not supported format
            else {
                return '';
            }
            // no key for decryption
            if (!isset($this->_keys[$keyVersion])) {
                return '';
            }
            $crypt = $this->_getCrypt($this->_keys[$keyVersion], $cryptVersion);

            return str_replace("\x0", '', trim($crypt->decrypt(base64_decode((string)$data))));
        }
        return '';
    }

    /**
     * Prepend key and cipher versions to encrypted data after encrypting
     *
     * @param $data
     * @return string
     * @throws Exception
     */
    public function encrypt($data)
    {
        $crypt = $this->_getCrypt();
        return
            (MCRYPT_BLOWFISH !== $crypt->getCypher() ? $this->_keyVersion . ':' . $this->_cipher . ':' : '') .
            (MCRYPT_MODE_CBC === $crypt->getMode() ? $crypt->getInitVector() . ':' : '') .
            base64_encode($crypt->encrypt((string)$data));
    }

    /**
     * Validate an encryption key
     * @param $key
     * @return mixed
     * @throws Exception
     */
    public function validateKey($key)
    {
        if ((false !== strpos($key, '<![CDATA[')) || (false !== strpos($key, ']]>')) || preg_match('/\s/s', $key)) {
            echo ('Encryption key format is invalid');
        }
        return parent::validateKey($key);
    }

}//End of class