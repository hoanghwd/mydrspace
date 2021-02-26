<?php

/**
 * Created by PhpStorm.
 * User: hdo
 * Date: 3/27/2019
 * Time: 3:18 PM
 */
class Core_Config_Base extends Varien_Simplexml_Config
{
    /**
     * Core_Config_Base constructor.
     * @param null $sourceData
     */
    public function __construct($sourceData = NULL)
    {
        $this->_elementClass = 'Core_Config_Element';

        parent::__construct($sourceData);
    }

}//End of class