<?php
/**
 * Created by PhpStorm.
 * User: hdo
 * Date: 3/29/2019
 * Time: 10:54 PM
 */

class Core_Controller_Varien_Front extends Varien_Object
{
    const XML_STORE_ROUTERS_PATH = 'web/routers';

    /**
     * Available routers array
     *
     * @var array
     */
    protected $_routers = array();

    protected $_defaults = array();


    /**
     * Init Front Controller
     *
     * @return Core_Controller_Varien_Front
     */
    public function init()
    {
        $routersInfo = Virtual::app()->getVirtual()->getConfig(self::XML_STORE_ROUTERS_PATH);
        foreach ($routersInfo as $routerCode => $routerInfo) {
            if (isset($routerInfo['class'])) {
                $router = new $routerInfo['class'];
                if (isset($routerInfo['area'])) {
                    $router->collectRoutes($routerInfo['area'], $routerCode);
                }
                $this->addRouter($routerCode, $router);
            }
        }

        // Add default router at the last
        $default = new Core_Controller_Varien_Router_Default();
        $this->addRouter('default', $default);

        return $this;
    }

    /**
     * @return Core_Controller_Varien_Front
     * @throws Zend_Controller_Request_Exception
     */
    public function dispatch()
    {
        $request = $this->getRequest();
        // If pre-configured, check equality of base URL and requested URL
        $this->_checkBaseUrl($request);

        $request->setPathInfo()->setDispatched(false);
        $i = 0;
        $found = false;
        $maxTry = 2;
        //Virtual::dump($this->_routers);
        while (!$request->isDispatched() && $i++ < $maxTry) {
            foreach ($this->_routers as $router) {
                /** @var $router Core_Controller_Varien_Router_Standard */
                if ($router->match($request)) {
                    $found = true;
                    break;
                }
            }
        }//while

        if (!$found) {
            //require_once(Virtual::getBaseDir() . DS . 'errors' . DS . '404.php');
            //die();
            echo "Not found here";
        }

        Varien_Profiler::start('virtual::app::dispatch::send_response');
        $this->getResponse()->sendResponse();
        Varien_Profiler::stop('virtual::app::dispatch::send_response');

        return $this;
    }

    /**
     * Adding new router
     *
     * @param   string $name
     * @param   Core_Controller_Varien_Router_Abstract $router
     * @return  Core_Controller_Varien_Front
     */
    public function addRouter($name, Core_Controller_Varien_Router_Abstract $router)
    {
        $router->setFront($this);
        $this->_routers[$name] = $router;

        return $this;
    }

    /**
     * Retrieve router by name
     *
     * @param   string $name
     * @return  Core_Controller_Varien_Router_Abstract
     */
    public function getRouter($name)
    {
        if (isset($this->_routers[$name])) {
            return $this->_routers[$name];
        }

        return false;
    }

    /**
     * @return Core_Controller_Request_Http|Zend_Controller_Request_Http
     * @throws Zend_Controller_Request_Exception
     */
    public function getRequest()
    {
        return Virtual::app()->getRequest();
    }

    /**
     * Retrieve response object
     *
     * @return Zend_Controller_Response_Http
     */
    public function getResponse()
    {
        return Virtual::app()->getResponse();
    }

    /**
     * Returns router instance by route name
     *
     * @param string $routeName
     * @return Core_Controller_Varien_Router_Abstract
     */
    public function getRouterByRoute($routeName)
    {
        /// empty route supplied - return base url
        if (empty($routeName)) {
            $router = $this->getRouter('standard');
        }
        elseif ($this->getRouter('standard')->getFrontNameByRoute($routeName)) {
            // try standard router url assembly
            $router = $this->getRouter('standard');
        }
        elseif ($router = $this->getRouter($routeName)) {
            // try custom router url assembly
        }
        else {
            // get default router url
            $router = $this->getRouter('default');
        }

        return $router;
    }

    /**
     * @param $frontName
     * @return Core_Controller_Varien_Router_Abstract
     */
    public function getRouterByFrontName($frontName)
    {
        // empty route supplied - return base url
        if (empty($frontName)) {
            $router = $this->getRouter('standard');
        }
        elseif ($this->getRouter('standard')->getRouteByFrontName($frontName)) {
            // try standard router url assembly
            $router = $this->getRouter('standard');
        }
        elseif ($router = $this->getRouter($frontName)) {
            // try custom router url assembly
        }
        else {
            // get default router url
            $router = $this->getRouter('default');
        }

        return $router;
    }

    /**
     * Retrieve routers collection
     *
     * @return array
     */
    public function getRouters()
    {
        return $this->_routers;
    }

    /**
     * Auto-redirect to base url (without SID) if the requested url doesn't match it.
     * By default this feature is enabled in configuration.
     *
     * @param Zend_Controller_Request_Http $request
     */
    protected function _checkBaseUrl($request)
    {
        $redirectCode = (int)Virtual::getStoreConfig('web/url/redirect_to_base');

        if (!$redirectCode) {
            return;
        }
        elseif ($redirectCode != 301) {
            $redirectCode = 302;
        }

        $baseUrl = Virtual::getBaseUrl(
            Core_Model_Virtual::URL_TYPE_WEB,
            IS_SECURE
        );

        if (!$baseUrl) {
            return;
        }

        $uri = @parse_url($baseUrl);
        $requestUri = $request->getRequestUri() ? $request->getRequestUri() : '/';
        if (isset($uri['scheme']) && $uri['scheme'] != $request->getScheme()
            || isset($uri['host']) && $uri['host'] != $request->getHttpHost()
            || isset($uri['path']) && strpos($requestUri, $uri['path']) === false
        ) {
            Virtual::app()->getFrontController()->getResponse()
                          ->setRedirect($baseUrl, $redirectCode)
                          ->sendResponse();
            exit;
        }
    }

}//End of class