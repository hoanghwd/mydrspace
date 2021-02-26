<?php
/**
 * Created by PhpStorm.
 * User: hdo
 * Date: 3/29/2019
 * Time: 10:35 AM
 */

class Core_Model_Design_Package
{
    const DEFAULT_AREA = 'frontend';
    const DEFAULT_PACKAGE = 'base';
    const DEFAULT_THEME = 'default';
    const BASE_PACKAGE = 'base';

    /**
     * Package area
     *
     * @var string
     */
    protected $_area;

    /**
     * Package name
     *
     * @var string
     */
    protected $_name;

    /**
     * Package theme
     *
     * @var string
     */
    protected $_theme;

    /**
     * Package root directory
     *
     * @var string
     */
    protected $_rootDir;

    /**
     * @var Core_Model_Design_Config $_config
     */
    protected $_config = null;


    /**
     * Core_Model_Design_Package constructor.
     */
    public function __construct()
    {
        if (is_null($this->_config)) {
            $this->_config = Virtual::getSingleton('core/design_config');
        }
    }

    /**
     * Set package area
     *
     * @param  string $area
     * @return Core_Model_Design_Package
     */
    public function setArea($area)
    {
        $this->_area = $area;

        return $this;
    }

    /**
     * Retrieve package area
     *
     * @return string
     */
    public function getArea()
    {
        if (is_null($this->_area)) {
            $this->_area = self::DEFAULT_AREA;
        }

        return $this->_area;
    }

    /**
     * Set package name
     * In case of any problem, the default will be set.
     *
     * @param  string $name
     * @return Core_Model_Design_Package
     */
    public function setPackageName($name = '')
    {
        $this->_name = self::DEFAULT_PACKAGE;

        return $this;
    }

    /**
     * Retrieve package name
     *
     * @return string
     */
    public function getPackageName()
    {
        if (null === $this->_name) {
            $this->setPackageName();
        }

        return $this->_name;
    }

    /**
     * Declare design package theme params
     * Polymorph method:
     * 1) if 1 parameter specified, sets everything to this value
     * 2) if 2 parameters, treats 1st as key and 2nd as value
     * @return Core_Model_Design_Package
     * @throws Exception
     */
    public function setTheme()
    {
        switch (func_num_args()) {
            case 1:
                foreach (array('layout', 'template', 'skin', 'locale') as $type) {
                    $this->_theme[$type] = func_get_arg(0);
                }
                break;

            case 2:
                $this->_theme[func_get_arg(0)] = func_get_arg(1);
                break;

            default:
                Virtual::throwException('Wrong number of arguments for %s', __METHOD__);
        }//switch

        return $this;
    }

    /**
     * @param $type
     * @return mixed
     */
    public function getTheme($type)
    {
        $this->_theme[$type] = $this->getDefaultTheme();

        return $this->_theme[$type];
    }

    /**
     * Get default theme
     *
     * @return string
     */
    public function getDefaultTheme()
    {
        return self::DEFAULT_THEME;
    }

    /**
     * @param $file
     * @return bool
     * @throws Exception
     */
    public function validateFile($file)
    {
        if (!file_exists($file)) {
            Virtual::throwException('file not exist'. $file);
            return false;
        }

        return $file;
    }

    /**
     * Get filename by specified theme parameters
     *
     * @param array $file
     * @param $params
     * @return string
     */
    protected function _renderFilename($file, array $params)
    {
        switch ($params['_type']) {
            case 'skin':
                $dir = $this->getSkinBaseDir($params);
                break;

            default:
                $dir = $this->getBaseDir($params);
                break;
        }

        return $dir . DS . $file;
    }

    /**
     * @param array $params
     * @return string
     */
    public function getSkinBaseDir(array $params=array())
    {
        $params['_type'] = 'locale';
        $this->updateParamDefaults($params);
        $baseDir = (empty($params['_relative']) ? Virtual::getBaseDir('design').DS : '').DS.
            $params['_area'].DS.$params['_package'].DS.$params['_theme'] . DS . 'locale' . DS .
            Virtual::app()->getLocale()->getLocaleCode();

        return $baseDir;
    }

    /**
     * @param array $params
     * @return string
     */
    public function getSkinBaseUrl(array $params=array())
    {
        $params['_type'] = 'skin';
        $this->updateParamDefaults($params);

        return
         Virtual::getBaseUrl('skin', isset($params['_secure'])?(bool)$params['_secure']:null).
                  DS.$params['_area'].'/'.$params['_package'].'/'.$params['_theme'].'/';
    }

    /**
     * @param array $params
     * @return Core_Model_Design_Package
     */
    public function updateParamDefaults(array &$params)
    {
        if (empty($params['_area'])) {
            $params['_area'] = $this->getArea();
        }
        if (empty($params['_package'])) {
            $params['_package'] = $this->getPackageName();
        }
        if (empty($params['_theme'])) {
            $params['_theme'] = $this->getTheme( (isset($params['_type'])) ? $params['_type'] : '' );
        }
        if (empty($params['_default'])) {
            $params['_default'] = false;
        }

        return $this;
    }

    /**
     * @param $file
     * @param array $params
     * @return bool
     * @throws Exception
     */
    public function getFilename($file, array $params)
    {
        $this->updateParamDefaults($params);
        $file = Virtual::getBaseDir('design').DS.
                $params['_area'].DS.$params['_package'].DS.$params['_theme'].DS.$params['_type'].DS.
                $file;

        return $this->validateFile($file);
    }

    /**
     * @param $file
     * @param array $params
     * @return bool
     * @throws Exception
     */
    public function getLayoutFilename($file, array $params=array())
    {
        $params['_type'] = 'layout';

        return $this->getFilename($file, $params);
    }

    /**
     * @param $file
     * @param array $params
     * @return bool
     * @throws Exception
     */
    public function getTemplateFilename($file, array $params=array())
    {
        $params['_type'] = 'template';

        return $this->getFilename($file, $params);
    }

    /**
     * Get skin file url
     *
     * @param string $file
     * @param array $params
     * @return string
     */
    public function getSkinUrl($file = null, array $params = array())
    {
        if (empty($params['_type'])) {
            $params['_type'] = 'skin';
        }
        if (empty($params['_default'])) {
            $params['_default'] = false;
        }
        $this->updateParamDefaults($params);

        return $this->getSkinBaseUrl($params) . (empty($file) ? '' : $file);
    }

}//End of class