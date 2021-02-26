<?php
/**
 * Created by PhpStorm.
 * User: hdo
 * Date: 3/29/2019
 * Time: 10:43 AM
 */

class Core_Model_Design_Config extends Varien_Simplexml_Config
{
    protected $_designRoot;

    /**
     * Assemble themes inheritance config
     * Core_Model_Design_Config constructor.
     * @param array $params
     * @throws Exception
     */
    public function __construct(array $params = array())
    {
        if (isset($params['designRoot'])) {
            if (!is_dir($params['designRoot'])) {
                Virtual::throwException("Design root '{$params['designRoot']}' isn't a directory.");
            }
            $this->_designRoot = $params['designRoot'];
        }
        else {
            $this->_designRoot = Virtual::getBaseDir('design');
        }

        $this->loadString('<theme />');
        $path = str_replace('/', DS, $this->_designRoot . '/*/*/*/etc/theme.xml');
        $files = glob($path);
        foreach ($files as $file) {
            $config = new Varien_Simplexml_Config();
            $config->loadFile($file);
            list($area, $package, $theme) = $this->_getThemePathSegments($file);
            $this->setNode($area . '/' . $package . '/' . $theme, null);
            $this->getNode($area . '/' . $package . '/' . $theme)->extend($config->getNode());
        }
    }

    /**
     * Get area, package and theme from path .../app/design/{area}/{package}/{theme}/etc/theme.xml
     *
     * @param string $filePath
     * @return array
     */
    protected function _getThemePathSegments($filePath)
    {
        $segments = array_reverse(explode(DS, $filePath));

        return array($segments[4], $segments[3], $segments[2]);
    }

}//End of class