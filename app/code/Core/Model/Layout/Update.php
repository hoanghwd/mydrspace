<?php
/**
 * Created by PhpStorm.
 * User: hdo
 * Date: 3/31/2019
 * Time: 5:26 PM
 */

class Core_Model_Layout_Update
{
    /**
     * Layout Update Simplexml Element Class Name
     *
     * @var string
     */
    protected $_elementClass;

    /**
     * @var Simplexml_Element
     */
    protected $_packageLayout;

    /**
     * Cumulative array of update XML strings
     *
     * @var array
     */
    protected $_updates = array();

    /**
     * Handles used in this update
     *
     * @var array
     */
    protected $_handles = array();

    /**
     * Substitution values in structure array('from'=>array(), 'to'=>array())
     *
     * @var array
     */
    protected $_subst = array();

    /**
     * Core_Model_Layout_Update constructor.
     */
    public function __construct()
    {
        $subst = Virtual::getConfig()->getPathVars();
        foreach ($subst as $k=>$v) {
            $this->_subst['from'][] = '{{'.$k.'}}';
            $this->_subst['to'][] = $v;
        }
    }

    /**
     * @param $handle
     * @return Core_Model_Layout_Update
     */
    public function addHandle($handle)
    {
        if (is_array($handle)) {
            foreach ($handle as $h) {
                $this->_handles[$h] = 1;
            }
        }
        else {
            $this->_handles[$handle] = 1;
        }

        return $this;
    }

    /**
     * @param array $handles
     * @return Core_Model_Layout_Update
     * @throws Exception
     */
    public function load($handles=array())
    {
        if (is_string($handles)) {
            $handles = array($handles);
        }
        elseif (!is_array($handles)) {
           Virtual::throwException('Invalid layout update handle');
        }

        if( sizeof($handles) > 0 ) {
            foreach ($handles as $handle) {
                $this->addHandle($handle);
            }
        }

        foreach ($this->getHandles() as $handle) {
            $this->merge($handle);
        }

        return $this;
    }

    /**
     * Merge layout update by handle
     * @param $handle
     * @return Core_Model_Layout_Update
     * @throws Exception
     */
    public function merge($handle)
    {
        $this->fetchPackageLayoutUpdates($handle);
        $this->fetchDbLayoutUpdates($handle);

        return $this;
    }

    /**
     * @param $handle
     * @return bool
     * @throws Exception
     */
    public function fetchPackageLayoutUpdates($handle)
    {
        if (empty($this->_packageLayout)) {
            $this->fetchFileLayoutUpdates($handle);
        }

        foreach ($this->_packageLayout->$handle as $updateXml) {
            $this->fetchRecursiveUpdates($updateXml);
            $this->addUpdate($updateXml->innerXml());
        }

        return true;
    }

    /**
     * @param $updateXml
     * @return Core_Model_Layout_Update
     * @throws Exception
     */
    public function fetchRecursiveUpdates($updateXml)
    {
        foreach ($updateXml->children() as $child) {
            if (strtolower($child->getName())=='update' && isset($child['handle'])) {
                $this->merge((string)$child['handle']);

                // Adding merged layout handle to the list of applied hanles
                $this->addHandle((string)$child['handle']);
            }
        }

        return $this;
    }

    /**
     * @return Core_Model_Layout_Update
     * @throws Exception
     */
    public function fetchFileLayoutUpdates()
    {
        $design = Virtual::getSingleton('core/design_package');
        if (empty($layoutStr)) {
            $this->_packageLayout =
                $this->getFileLayoutUpdatesXml(
                    $design->getArea(),
                    $design->getPackageName(),
                    $design->getTheme('layout')
            );
        }

        return $this;
    }

    /**
     * Collect and merge layout updates from file
     * @param $area
     * @param $package
     * @param $theme
     * @return SimpleXMLElement|null
     * @throws Exception
     */
    public function getFileLayoutUpdatesXml($area, $package, $theme)
    {
        /* @var $design Core_Model_Design_Package */
        $design = Virtual::getSingleton('core/design_package');
        $layoutXml = null;
        $elementClass = $this->getElementClass();
        $updatesRoot = Virtual::app()->getConfig()->getNode($area.'/layout/updates');
        $updates = $updatesRoot->asArray();

        $themeUpdates = Virtual::getSingleton('core/design_config')->getNode("$area/$package/$theme/layout/updates");
        if ($themeUpdates && is_array($themeUpdates->asArray())) {
            //array_values() to ensure that theme-specific layouts don't override, but add to module layouts
            $updates = array_merge($updates, array_values($themeUpdates->asArray()));
        }

        $updateFiles = array();
        foreach ($updates as $updateNode) {
            if (!empty($updateNode['file'])) {
                $updateFiles[] = $updateNode['file'];
            }
        }

        // custom local layout updates file - load always last
        $updateFiles[] = 'local.xml';
        $layoutStr = '';
        foreach ($updateFiles as $file) {
            $filename = $design->getLayoutFilename($file, array(
                '_area'    => $area,
                '_package' => $package,
                '_theme'   => $theme
            ));

            if (!is_readable($filename)) {
                continue;
            }

            $fileStr = file_get_contents($filename);
            $fileStr = str_replace($this->_subst['from'], $this->_subst['to'], $fileStr);
            $fileXml = simplexml_load_string($fileStr, $elementClass);
            if (!$fileXml instanceof SimpleXMLElement) {
                continue;
            }
            $layoutStr .= $fileXml->innerXml();
        }

        $layoutXml = simplexml_load_string('<layouts>'.$layoutStr.'</layouts>', $elementClass);

        return $layoutXml;
    }

    /**
     * @param $handle
     * @return bool
     * @throws Exception
     */
    public function fetchDbLayoutUpdates($handle)
    {
        $updateStr = $this->_getUpdateString($handle);
        if (!$updateStr) {
            return false;
        }

        return true;
    }

    /**
     * Get update string
     * @param $handle
     * @return mixed
     * @throws Exception
     */
    protected function _getUpdateString($handle)
    {
        return Virtual::getResourceModel('core/layout')->fetchUpdatesByHandle($handle);
    }

    /**
     * Get handles
     *
     * @return array
     */
    public function getHandles()
    {
        return array_keys($this->_handles);
    }

    /**
     * @return string
     */
    public function getElementClass()
    {
        if (!$this->_elementClass) {
            $this->_elementClass = Virtual::getConfig()->getModelClassName('core/layout_element');
        }

        return $this->_elementClass;
    }

    /**
     * @param $update
     * @return Core_Model_Layout_Update
     */
    public function addUpdate($update)
    {
        $this->_updates[] = $update;

        return $this;
    }

    public function asString()
    {
        return implode('', $this->_updates);
    }

    /**
     * As convert to simple XML
     * @return SimpleXMLElement
     */
    public function asSimplexml()
    {
        $updates = trim($this->asString());
        $updates = '<'.'?xml version="1.0"?'.'><layout>'.$updates.'</layout>';

        return simplexml_load_string($updates, $this->getElementClass());
    }

}//End of class