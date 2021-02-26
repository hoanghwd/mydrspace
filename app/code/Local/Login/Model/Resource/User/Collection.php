<?php

/**
 * Created by PhpStorm.
 * User: hdo
 * Date: 4/5/2019
 * Time: 8:53 AM
 */
class Login_Model_Resource_User_Collection extends Core_Model_Resource_Db_Collection_Abstract
{
    /**
     * Define resource model
     *
     */
    protected function _construct()
    {
        $this->_init('login/user');
    }

}//End of class