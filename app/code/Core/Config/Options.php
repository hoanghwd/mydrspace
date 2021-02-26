<?php
/**
 * Created by PhpStorm.
 * User: hdo
 * Date: 3/27/2019
 * Time: 3:49 PM
 */

class Core_Config_Options extends Varien_Object
{
    /**
     * Var directory
     *
     * @var string
     */
    const VAR_DIRECTORY = 'var';
    /**
     * Flag cache for existing or already created directories
     *
     * @var array
     */
    protected $_dirExists = array();

    /**
     * Initialize default values of the options
     */
    protected function _construct()
    {
        $appRoot = Virtual::getRoot();
        $root = dirname($appRoot);

        $this->_data['app_dir']     = $appRoot;
        $this->_data['base_dir']    = $root;
        $this->_data['code_dir']    = $appRoot.DS.'code';
        $this->_data['design_dir']  = $appRoot.DS.'design';
        $this->_data['etc_dir']     = $appRoot.DS.'etc';
        $this->_data['lib_dir']     = $root.DS.'lib';
        $this->_data['skin_dir']    = $root.DS.'skin';
    }

    /**
     * @param $type
     * @return mixed
     * @throws Exception
     */
    public function getDir($type)
    {
        $method = 'get'.ucwords($type).'Dir';
        $dir = $this->$method();

        if (!$dir) {
            Virtual::throwException('Invalid dir type requested: '.$type);
        }

        return $dir;
    }

    public function getAppDir()
    {
        return $this->_data['app_dir'];
    }

    public function getBaseDir()
    {
        return $this->_data['base_dir'];
    }

    public function getCodeDir()
    {
        return $this->_data['code_dir'];
    }

    public function getDesignDir()
    {
        return $this->_data['design_dir'];
    }

    public function getEtcDir()
    {
        return $this->_data['etc_dir'];
    }

    public function getLibDir()
    {
        return $this->_data['lib_dir'];
    }

    public function getSkinDir()
    {
        return $this->_data['skin_dir'];
    }

}//End of class