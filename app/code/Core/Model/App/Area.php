<?php
/**
 * Created by PhpStorm.
 * User: hdo
 * Date: 3/29/2019
 * Time: 9:25 AM
 */

class Core_Model_App_Area
{
    const AREA_GLOBAL   = 'global';
    const AREA_FRONTEND = 'frontend';
    const PART_CONFIG   = 'config';
    const PART_DESIGN   = 'design';

    /**
     * Array of area loaded parts
     *
     * @var array
     */
    protected $_loadedParts;

    /**
     * Area code
     *
     * @var string
     */
    protected $_code;


    /**
     * Area application
     *
     * @var Core_Model_App
     */
    protected $_application;

    /**
     * Core_Model_App_Area constructor.
     * @param $areaCode
     * @param $application
     */
    public function __construct($areaCode, $application)
    {
        $this->_code = $areaCode;
        $this->_application = $application;
    }

    /**
     * Load area data
     * @param null $part
     * @return Core_Model_App_Area
     * @throws Zend_Controller_Request_Exception
     */
    public function load($part=null)
    {
        if (is_null($part)) {
            $this->_loadPart(self::PART_CONFIG)
                 ->_loadPart(self::PART_DESIGN);

        }
        else {
            $this->_loadPart($part);
        }

        return $this;
    }

    /**
     * Loading part of area
     * @param $part
     * @return $this
     * @throws Zend_Controller_Request_Exception
     */
    protected function _loadPart($part)
    {
        if (isset($this->_loadedParts[$part])) {
            return $this;
        }
        Varien_Profiler::start('virtual::dispatch::controller::action::predispatch::load_area::'.$this->_code.'::'.$part);
        switch ($part) {
            case self::PART_CONFIG:
                $this->_initConfig();
                break;

            case self::PART_DESIGN:
                $this->_initDesign();
                break;
        }

        $this->_loadedParts[$part] = true;
        Varien_Profiler::stop('virtual::dispatch::controller::action::predispatch::load_area::'.$this->_code.'::'.$part);

        return $this;
    }

    /**
     * Init config
     */
    protected function _initConfig()
    {

    }

    /**
     * Init design
     * @return Core_Model_App_Area|void
     * @throws Zend_Controller_Request_Exception
     */
    protected function _initDesign()
    {
        if (Virtual::app()->getRequest()->isStraight()) {
            return $this;
        }

        /**
         * @var Core_Model_Design_Package $designPackage
         */
        $designPackage = Virtual::getSingleton('core/design_package');
        if ($designPackage->getArea() != self::AREA_FRONTEND)
            return;
    }

}//End of class