<?php

class Core_Controller_Front_Action extends Core_Controller_Varien_Action
{
    /**
     * Session namespace to refer in other places
     */
    const SESSION_NAMESPACE = 'frontend';

    /**
     * Add secret key to url config path
     */
    const XML_CSRF_USE_FLAG_CONFIG_PATH   = 'system/csrf/use_form_key';

    /**
     * Currently used area
     *
     * @var string
     */
    protected $_currentArea = 'frontend';

    /**
     * Namespace for session.
     *
     * @var string
     */
    protected $_sessionNamespace = self::SESSION_NAMESPACE;

    /**
     * Predispatch: should set layout area
     *
     * @return Core_Controller_Front_Action
     */
    public function preDispatch()
    {
        parent::preDispatch();

        return $this;
    }

    /**
     * Postdispatch: should set last visited url
     *
     * @return Core_Controller_Front_Action
     */
    public function postDispatch()
    {
        parent::postDispatch();

        return $this;
    }

}//End of class