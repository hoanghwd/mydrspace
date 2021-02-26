<?php

/**
 * Core Email Template Filter Model
 *
 * @category   Core
 */
class Core_Model_Email_Template_Filter extends Varien_Filter_Template
{
    /**
     * Filter the string as template.
     * Rewrited for logging exceptions
     *
     * @param string $value
     * @return string
     */
    public function filter($value)
    {
        try {
            $value = parent::filter($value);
        } catch (Exception $e) {
            $value = '';
            Virtual::throwException($e->getMessage());
        }
        return $value;
    }

}//End of class