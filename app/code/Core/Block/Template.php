<?php
/**
 * Created by PhpStorm.
 * User: hdo
 * Date: 4/2/2019
 * Time: 11:11 AM
 */

class Core_Block_Template extends Core_Block_Abstract
{

    const XML_PATH_DEBUG_TEMPLATE_HINTS         = 'dev/debug/template_hints';
    const XML_PATH_DEBUG_TEMPLATE_HINTS_BLOCKS  = 'dev/debug/template_hints_blocks';
    const XML_PATH_TEMPLATE_ALLOW_SYMLINK       = 'dev/template/allow_symlink';

    /**
     * View scripts directory
     *
     * @var string
     */
    protected $_viewDir = '';

    /**
     * Assigned variables for view
     *
     * @var array
     */
    protected $_viewVars = array();

    protected $_baseUrl;

    protected $_jsUrl;

    /**
     * Is allowed symlinks flag
     *
     * @var bool
     */
    protected $_allowSymlinks = null;


    /**
     * Path to template file in theme.
     *
     * @var string
     */
    protected $_template;

    /**
     * Internal constructor, that is called from real constructor
     *
     */
    protected function _construct()
    {
        parent::_construct();

        /*
         * In case template was passed through constructor
         * we assign it to block's property _template
         * Mainly for those cases when block created
         * not via Mage_Core_Model_Layout::addBlock()
         */
        if ($this->hasData('template')) {
            $this->setTemplate($this->getData('template'));
        }
    }

    /**
     * Get relevant path to template
     *
     * @return string
     */
    public function getTemplate()
    {
        return $this->_template;
    }

    /**
     * Set path to template used for generating block's output.
     *
     * @param string $template
     * @return Core_Block_Template
     */
    public function setTemplate($template)
    {
        $this->_template = $template;

        return $this;
    }

    /**
     * Assign variable
     *
     * @param   string|array $key
     * @param   mixed $value
     * @return  Core_Block_Template
     */
    public function assign($key, $value=null)
    {
        if (is_array($key)) {
            foreach ($key as $k=>$v) {
                $this->assign($k, $v);
            }
        }
        else {
            $this->_viewVars[$key] = $value;
        }

        return $this;
    }

    /**
     * Set template location directory
     * @param $dir
     * @return $this
     * @throws Exception
     */
    public function setScriptPath($dir)
    {
        $scriptPath = realpath($dir);
        if (strpos($scriptPath, realpath(Virtual::getBaseDir('design'))) === 0 ) {
            $this->_viewDir = $dir;
        }
        else {
            //Mage::log('Not valid script path:' . $dir, Zend_Log::CRIT, null, null, true);
        }

        return $this;
    }

    /**
     *  Retrieve block view from file (template)
     *
     * @param $fileName
     * @throws Exception
     */
    public function fetchView($fileName)
    {
        //extract($this->_viewVars, EXTR_SKIP);
        $do = $this->getDirectOutput();

        if (!$do) {
            ob_start();
        }

        $includeFilePath = realpath(DS . $fileName);
        if (strpos($includeFilePath, realpath($this->_viewDir)) === 0 ) {
            include $includeFilePath;
        }
        else {
            Virtual::throwException('Not valid template file:'.$fileName);
        }
    }

    /**
     * Check if direct output is allowed for block
     *
     * @return bool
     */
    public function getDirectOutput()
    {
        if ($this->getLayout()) {
            return $this->getLayout()->getDirectOutput();
        }

        return false;
    }

    /**
     * Render block HTML
     *
     * @return string|void
     * @throws Exception
     */
    protected function _toHtml()
    {
        if (!$this->getTemplate()) {
            return '';
        }

        return $this->renderView();
    }

    /**
     * Render block
     *
     * @throws Exception
     */
    public function renderView()
    {
        $this->setScriptPath(Virtual::getBaseDir('design'));

        return $this->fetchView($this->getTemplateFile());
    }

    /**
     *  Get absolute path to template
     *
     * @return string
     * @throws Exception
     */
    public function getTemplateFile()
    {
        $params = array('_relative'=>true);
        $area = $this->getArea();
        if ($area) {
            $params['_area'] = $area;
        }

        //Get template file name from Core_Model_Design_Package

        return Virtual::getDesign()->getTemplateFilename($this->getTemplate(), $params);
    }

    /**
     * Get design area
     * @return string
     */
    public function getArea()
    {
        return $this->_getData('area');
    }

    /**
     * @return string
     * @throws Zend_Controller_Request_Exception
     * @throws Zend_Controller_Response_Exception
     */
    public function getBaseUrl()
    {
        return Virtual::getBaseUrl();
    }

    /**
     * @return string
     * @throws Zend_Controller_Request_Exception
     * @throws Zend_Controller_Response_Exception
     */
    public function getImgUrl()
    {
        return Virtual::getImgUrl();
    }

    /**
     * Get is allowed symliks flag
     *
     * @return bool
     */
    protected function _getAllowSymlinks()
    {
        if (is_null($this->_allowSymlinks)) {
            $this->_allowSymlinks = Virtual::getStoreConfigFlag(self::XML_PATH_TEMPLATE_ALLOW_SYMLINK);
        }
        return $this->_allowSymlinks;
    }

}//End of class