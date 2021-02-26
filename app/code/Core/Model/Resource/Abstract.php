<?php
/**
 * Created by PhpStorm.
 * User: hdo
 * Date: 3/28/2019
 * Time: 11:56 AM
 */

abstract class Core_Model_Resource_Abstract
{
    /**
     * Main constructor
     */
    public function __construct()
    {
        /**
         * Please override this one instead of overriding real __construct constructor
         */
        $this->_construct();
    }

}//End of class