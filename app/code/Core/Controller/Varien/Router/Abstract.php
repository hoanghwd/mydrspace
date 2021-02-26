<?php
/**
 * Created by PhpStorm.
 * User: hdo
 * Date: 3/29/2019
 * Time: 11:35 PM
 */

abstract class Core_Controller_Varien_Router_Abstract
{
    protected $_front;

    /**
     * @param $front
     * @return Core_Controller_Varien_Router_Abstract
     */
    public function setFront($front)
    {
        $this->_front = $front;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getFront()
    {
        return $this->_front;
    }

    /**
     * @param $routeName
     * @return mixed
     */
    public function getFrontNameByRoute($routeName)
    {
        return $routeName;
    }

    /**
     * @param $frontName
     * @return mixed
     */
    public function getRouteByFrontName($frontName)
    {
        return $frontName;
    }

    /**
     * @param Zend_Controller_Request_Http $request
     * @return mixed
     */
    abstract public function match(Zend_Controller_Request_Http $request);

}//End of abstract