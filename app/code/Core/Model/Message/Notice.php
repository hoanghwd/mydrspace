<?php


class Core_Model_Message_Notice extends Core_Model_Message_Abstract
{
    public function __construct($code)
    {
        parent::__construct(Core_Model_Message::NOTICE, $code);
    }
}
