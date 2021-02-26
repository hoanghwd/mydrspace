<?php
/**
 * Created by PhpStorm.
 * User: hdo
 * Date: 3/29/2019
 * Time: 11:33 PM
 */

class Core_Controller_Varien_Router_Standard extends Core_Controller_Varien_Router_Abstract
{
    protected $_modules = array();
    protected $_routes = array();
    protected $_dispatchData = array();


    /**
     * @param $configArea
     * @param $useRouterName
     */
    public function collectRoutes($configArea, $useRouterName)
    {
        $routers = array();
        $routersConfigNode = Virtual::getConfig()->getNode($configArea.'/routers');
        if($routersConfigNode) {
            $routers = $routersConfigNode->children();
        }
        //Virtual::dump($routers);

        foreach ($routers as $routerName=>$routerConfig) {
            $use = (string)$routerConfig->use;
            if ($use === $useRouterName) {
                $modules = array((string)$routerConfig->args->module);
                $frontName = (string)$routerConfig->args->frontName;
                $this->addModule($frontName, $modules, $routerName);
                //echo $frontName.' - '.$frontName.'<br/>';
                //Virtual::dump($modules);
            }
        }//foreach

        //Virtual::dump($this->_modules);
    }

    /**
     * @param $frontName
     * @param $moduleName
     * @param $routeName
     * @return Core_Controller_Varien_Router_Standard
     */
    public function addModule($frontName, $moduleName, $routeName)
    {
        $this->_modules[$frontName] = $moduleName;
        $this->_routes[$routeName] = $frontName;

        return $this;
    }

    /**
     * Match the request
     *
     * @param Zend_Controller_Request_Http $request
     * @return boolean
     */
    public function match(Zend_Controller_Request_Http $request)
    {
        $this->fetchDefault();
        $front = $this->getFront();
        $response = $front->getResponse();
        $path = trim($request->getPathInfo(), '/');
        //Virtual::dump($path);

        if ($path) {
            $p = explode('/', $path);
        }
        else {
            $p = explode('/', $this->_getDefaultPath());
        }

        // get module name
        if ($request->getModuleName()) {
            $module = $request->getModuleName();
        }
        else {
            $module = !empty($p[0]) ? $p[0]: $this->getFront()->getDefault('module');
        }

        /**
         * Searching router args by module name from route using it as key
         */
        $modules = $this->getModuleByFrontName($module);
        //Virtual::dump($modules);

        /**
         * Going through modules to find appropriate controller
         */
        $found = false;
        if( is_array($modules) && sizeof($modules) > 0 ) {
            $controllerInstance = '';
            foreach ($modules as $realModule) {
                $request->setRouteName($this->getRouteByFrontName($module));
                // get controller name
                if ($request->getControllerName()) {
                    $controller = $request->getControllerName();
                }
                else {
                    if (!empty($p[1])) {
                        $controller = $p[1];
                    }
                    else {
                        $controller = $front->getDefault('controller');
                    }
                }

                //Friendly https://www.huynhdo.us/virtual/login/authenticate.html?param=1
                $controller = $this->_getRealController($controller);

                // get action name
                if (empty($action)) {
                    if ($request->getActionName()) {
                        $action = $request->getActionName();
                    } else {
                        $action = !empty($p[2]) ? $p[2] : $front->getDefault('action');
                    }
                }
                //echo $action;
                $action = 'index';

                //Validate controller
                $controllerClassName = $this->_validateControllerClassName($realModule, $controller);
                #$controllerClassName = 'Index_IndexController';

                if (!$controllerClassName) {
                    continue;
                }
                $controllerInstance = Virtual::getControllerInstance($controllerClassName, $request, $response);

                if (!$this->_validateControllerInstance($controllerInstance)) {
                    continue;
                }

                if (!$controllerInstance->hasAction($action)) {
                    continue;
                }

                $found = true;
                break;
            }//foreach

            // set values only after all the checks are done
            $request->setModuleName($module);
            $request->setControllerName($controller);
            $request->setActionName($action);
            $request->setControllerModule($realModule);

            // set parameters from path info
            for ($i = 3, $l = sizeof($p); $i < $l; $i += 2) {
                $request->setParam($p[$i], isset($p[$i + 1]) ? urldecode($p[$i + 1]) : '');
            }

            // dispatch action
            $request->setDispatched(true);
            if ( method_exists($controllerInstance, 'hasAction') ) {
                $controllerInstance->dispatch($action);
            }
            else {
                require_once(Virtual::getBaseDir() . DS . 'errors' . DS . '404.php');
                die();
            }
        }

        return $found;
    }

    /**
     * Check if current controller instance is allowed in current router.
     *
     * @param Core_Controller_Varien_Action $controllerInstance
     * @return boolean
     */
    protected function _validateControllerInstance($controllerInstance)
    {
        return $controllerInstance instanceof Core_Controller_Front_Action;
    }

    /**
     * Generating and validating class file name,
     * class and if everything ok do include if needed and return of class name
     * @param $realModule
     * @param $controller
     * @return bool
     */
    protected function _validateControllerClassName($realModule, $controller)
    {
        $controllerFileName = $this->getControllerFileName($realModule, $controller);
        if (!$this->validateControllerFileName($controllerFileName)) {
            return false;
        }
        $controllerClassName = $this->getControllerClassName($realModule, $controller);

        if (!$controllerClassName) {
            return false;
        }

        //include controller file if needed
        if (!$this->_includeControllerClass($controllerFileName, $controllerClassName)) {
            return false;
        }

        return $controllerClassName;
    }

    /**
     * @param $fileName
     * @return bool
     */
    public function validateControllerFileName($fileName)
    {
        if ($fileName && is_readable($fileName) && false===strpos($fileName, '//')) {
            return true;
        }

        return false;
    }

    /**
     * @param $realModule
     * @param $controller
     * @return string
     */
    public function getControllerClassName($realModule, $controller)
    {
        //Login_AuthenticateController

        return uc_words($realModule).'_'.uc_words($controller).'Controller';
    }

    /**
     * Include the file containing controller class if this class is not defined yet
     *
     * @param string $controllerFileName
     * @param string $controllerClassName
     * @return bool
     */
    protected function _includeControllerClass($controllerFileName, $controllerClassName)
    {
        if (!class_exists($controllerClassName, false)) {
            if (!file_exists($controllerFileName)) {
                return false;
            }

            include $controllerFileName;

            if (!class_exists($controllerClassName, false)) {
                echo ('Controller file was loaded but class does not exist');
            }
        }

        return true;
    }

    /**
     * @param $realModule
     * @param $controller
     * @return string
     */
    public function getControllerFileName($realModule, $controller)
    {
        $parts = explode('_', $realModule);
        $realModule = implode('_', array_splice($parts, 0, 2));
        $file = Virtual::getModuleDir('controllers', $realModule);

        if (count($parts)) {
            $file .= DS . implode(DS, $parts);
        }
        $file .= DS.uc_words($controller, DS).'Controller.php';

        return $file;
    }

    /**
     * @param $frontName
     * @return mixed
     */
    public function getRouteByFrontName($frontName)
    {
        return array_search($frontName, $this->_routes);
    }

    /**
     * @param $controller
     * @return mixed
     */
    protected function _getRealController($controller)
    {
        $ctlParts = explode('.', $controller);
        if( is_array($ctlParts) && sizeof($ctlParts) > 1 ) {
            return $ctlParts[0];
        }

        return $controller;
    }

    /**
     * @param $frontName
     * @return bool
     */
    public function getModuleByFrontName($frontName)
    {
        if (isset($this->_modules[$frontName])) {
            return $this->_modules[$frontName];
        }

        return false;
    }

    /**
     * Fetch default
     */
    public function fetchDefault()
    {
        $this->getFront()->setDefault(
            array(
                'module'     => 'core',
                'controller' => 'index',
                'action'     => 'index'
        ));
    }

    /**
     * Get router default request path
     * @return string
     */
    protected function _getDefaultPath()
    {
        return Virtual::getStoreConfig('web/default/front');
    }

}//End of class