<?php

class Core_Model_Design_Fallback
{
    /**
     * @var Core_Model_Design_Config
     */
    protected $_config = null;

    /**
     * Used to find circular dependencies
     *
     * @var array
     */
    protected $_visited;


    /**
     * Core_Model_Design_Fallback constructor.
     * @param array $params
     * @throws Exception
     */
    public function __construct(array $params = array())
    {
        $this->_config = isset($params['config']) ? $params['config'] : Virtual::getModel('core/design_config');
    }

    /**
     * Get fallback scheme
     *
     * @param string $area
     * @param string $package
     * @param string $theme
     * @return array
     */
    public function getFallbackScheme($area, $package, $theme)
    {

    }

    /**
     * Check if inheritance defined in theme config
     *
     * @param $area
     * @param $package
     * @param $theme
     * @return bool
     */
    protected function _isInheritanceDefined($area, $package, $theme)
    {
        $path = $area . '/' . $package . '/' . $theme . '/parent';

        return $this->_config->getNode($path) !== false;
    }

    /**
     * Get fallback scheme according to theme config
     *
     * @param string $area
     * @param string $package
     * @param string $theme
     * @return array
     */
    protected function _getFallbackScheme($area, $package, $theme)
    {

    }

    /**
     * Prevent circular inheritance
     *
     * @param string $area
     * @param string $package
     * @param string $theme
     * @return array
     */
    protected function _checkVisited($area, $package, $theme)
    {

    }

    /**
     * Get fallback scheme when inheritance is not defined (backward compatibility)
     *
     * @return array
     */
    protected function _getLegacyFallbackScheme()
    {
        return array(
            array(),
            array('_theme' => $this->_getFallbackTheme()),
            array('_theme' => Core_Model_Design_Package::DEFAULT_THEME),
        );
    }

    /**
     * Default theme getter
     * @return string
     */
    protected function _getFallbackTheme()
    {
        return Core_Model_Design_Package::DEFAULT_THEME;
    }

}//End of class
