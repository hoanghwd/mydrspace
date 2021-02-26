<?php



class Core_Controller_Varien_Router_Default extends Core_Controller_Varien_Router_Abstract
{
    /**
     * Modify request and set to no-route action
     * If store is admin and specified different admin front name,
     * change store to default (Possible when enabled Store Code in URL)
     *
     * @param Zend_Controller_Request_Http $request
     * @return boolean
     */
    public function match(Zend_Controller_Request_Http $request)
    {
        //Index_IndexController

        $noRoute        = explode('/', $this->_getNoRouteConfig());
        $moduleName     = isset($noRoute[0]) && $noRoute[0] ? $noRoute[0] : 'core';
        $controllerName = isset($noRoute[1]) && $noRoute[1] ? $noRoute[1] : 'index';
        $actionName     = isset($noRoute[2]) && $noRoute[2] ? $noRoute[2] : 'index';

        //Virtual::dump($noRoute);

        $request->setModuleName($moduleName)
                ->setControllerName($controllerName)
                ->setActionName($actionName);

        return true;
    }

    /**
     * Retrieve default router config
     *
     * @return string
     */
    protected function _getNoRouteConfig()
    {
        return Virtual::app()->getStore()->getConfig('web/default/no_route');
    }

}//End of class