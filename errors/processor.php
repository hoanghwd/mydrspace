<?php
class Error_Processor
{
    const DEFAULT_SKIN = 'default';
    const ERROR_DIR = 'errors';

    /**
     * Page title
     *
     * @var string
     */
    public $pageTitle;

    /**
     * Base URL
     *
     * @var string
     */
    public $baseUrl;

    /**
     * Server script name
     *
     * @var string
     */
    protected $_scriptName;

    /**
     * Is root
     *
     * @var bool
     */
    protected $_root;

    /**
     * Internal config object
     *
     * @var stdClass
     */
    protected $_config;


    public function __construct()
    {
        $this->_errorDir  = dirname(__FILE__) . '/';
        $this->_reportDir = dirname($this->_errorDir) . '/var/report/';

        if (!empty($_SERVER['SCRIPT_NAME'])) {
            if (in_array(basename($_SERVER['SCRIPT_NAME'],'.php'), array('404','503','report'))) {
                $this->_scriptName = dirname($_SERVER['SCRIPT_NAME']);
            }
            else {
                $this->_scriptName = $_SERVER['SCRIPT_NAME'];
            }
        }

        $this->_indexDir = $this->_getIndexDir();
        $this->_root  = is_dir($this->_indexDir.'app');
    }


    /**
     * Send error headers
     *
     * @param int $statusCode
     */
    protected function _sendHeaders($statusCode)
    {
        $serverProtocol = !empty($_SERVER['SERVER_PROTOCOL']) ? $_SERVER['SERVER_PROTOCOL'] : 'HTTP/1.0';
        switch ($statusCode) {
            case 404:
                $description = 'Not Found';
                break;
            case 503:
                $description = 'Service Unavailable';
                break;
            default:
                $description = '';
                break;
        }

        header(sprintf('%s %s %s', $serverProtocol, $statusCode, $description), true, $statusCode);
        header(sprintf('Status: %s %s', $statusCode, $description), true, $statusCode);
    }

    /**
     * @param $template
     */
    protected function _renderPage($template)
    {
        $baseTemplate = $this->_getTemplatePath('page.phtml');
        $contentTemplate = $this->_getTemplatePath($template);

        if ($baseTemplate && $contentTemplate) {
            require_once $baseTemplate;
        }
    }

    /**
     * @param $template
     * @return string
     */
    protected function _getTemplatePath($template)
    {
        return $this->_getFilePath($template);
    }

    /**
     * @param $file
     * @return string
     */
    protected function _getFilePath($file)
    {
        return $this->_indexDir . self::ERROR_DIR . '/template/'.$file;
    }

    /**
     * Get index dir
     *
     * @return string
     */
    protected function _getIndexDir()
    {
        $documentRoot = '';
        if (!empty($_SERVER['DOCUMENT_ROOT'])) {
            $documentRoot = rtrim($_SERVER['DOCUMENT_ROOT'],'/');
        }
        return dirname($documentRoot . $this->_scriptName) . '/';
    }

    /**
     * Process 404 error
     */
    public function process404()
    {
        $this->pageTitle = 'Error 404: Not Found';
        $this->_sendHeaders(404);
        $this->_renderPage('404.phtml');
    }

    /**
     * Retrieve base URL
     *
     * @return string
     */
    public function getBaseUrl($param = false)
    {
        $path = $this->_scriptName;

        if($param && !$this->_root) {
            $path = dirname($path);
        }

        $basePath = str_replace('\\', '/', dirname($path));

        return $this->getHostUrl() . ('/' == $basePath ? '' : $basePath) . '/';
    }

    /**
     * Retrieve skin URL
     *
     * @return string
     */
    public function getSkinUrl()
    {
        return $this->getBaseUrl() . self::ERROR_DIR. '/' ;
    }

    /**
     * Retrieve base host URL without path
     *
     * @return string
     */
    public function getHostUrl()
    {
        /**
         * Define server http host
         */
        if (!empty($_SERVER['HTTP_HOST'])) {
            $host = $_SERVER['HTTP_HOST'];
        } elseif (!empty($_SERVER['SERVER_NAME'])) {
            $host = $_SERVER['SERVER_NAME'];
        } else {
            $host = 'localhost';
        }

        $isSecure = (!empty($_SERVER['HTTPS'])) && ($_SERVER['HTTPS'] != 'off');
        $url = ($isSecure ? 'https://' : 'http://') . htmlspecialchars($host, ENT_COMPAT | ENT_HTML401, 'UTF-8');

        if (!empty($_SERVER['SERVER_PORT'])
            && preg_match('/\d+/', $_SERVER['SERVER_PORT'])
            && !in_array($_SERVER['SERVER_PORT'], array(80, 433))
        ) {
            $url .= ':' . $_SERVER['SERVER_PORT'];
        }

        return  $url;
    }

}//End of class