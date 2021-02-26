<?php

class Core_Model_Message_Warning extends Core_Model_Message_Abstract
{
    public function __construct($code)
    {
        parent::__construct(Core_Model_Message::WARNING, $code);
    }
}
