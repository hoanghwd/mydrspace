<?php
/**
 * Created by PhpStorm.
 * User: hdo
 * Date: 3/31/2019
 * Time: 2:57 PM
 */

class Core_Model_Layout extends Varien_Simplexml_Config
{

    const SECURE_BASE_URL_PATH = 'web/secure/base_link_url';

    /**
     * Layout Update module
     *
     * @var Core_Model_Layout_Update
     */
    protected $_update;

    /**
     * Blocks registry
     *
     * @var array
     */
    protected $_blocks = array();

    /**
     * Cache of block callbacks to output during rendering
     *
     * @var array
     */
    protected $_output = array();

    /**
     * Layout area (f.e. admin, frontend)
     *
     * @var string
     */
    protected $_area;

    /**
     * Helper blocks cache for this layout
     *
     * @var array
     */
    protected $_helpers = array();

    /**
     * Flag to have blocks' output go directly to browser as oppose to return result
     *
     * @var boolean
     */
    protected $_directOutput = false;


    /**
     * Core_Model_Layout constructor.
     * @param array $data
     * @throws Exception
     */
    public function __construct($data=array())
    {
        $this->_elementClass = Virtual::getConfig()->getModelClassName('core/layout_element');
        $this->setXml(simplexml_load_string('<layout/>', $this->_elementClass));
        $this->_update = Virtual::getModel('core/layout_update');

        parent::__construct($data);
    }

    /**
     * Get all blocks marked for output
     *
     * @return string
     */
    public function getOutput()
    {
        $out = '';
        if (!empty($this->_output)) {
            foreach ($this->_output as $callback) {
                $out .= $this->getBlock($callback[0])->{$callback[1]}();
            }
        }

        return $out;
    }

    /**
     * Layout update instance
     *
     * @return Core_Model_Layout_Update
     */
    public function getUpdate()
    {
        return $this->_update;
    }

    /**
     * @return Core_Model_Layout
     */
    public function generateXml()
    {
        $xml = $this->getUpdate()->asSimplexml();
        $this->setXml($xml);
        //Virtual::dump($xml->asArray());

        return $this;
    }

    /**
     * Create layout blocks hierarchy from layout xml configuration
     * @param null $parent
     * @throws Exception
     */
    public function generateBlocks($parent=null)
    {
        if (empty($parent)) {
            $parent = $this->getNode();
        }

        foreach ($parent as $node) {
            $attributes = $node->attributes();
            if ((bool)$attributes->ignore) {
                continue;
            }

            switch ($node->getName()) {
                case 'block':
                    $this->_generateBlock($node, $parent);
                    $this->generateBlocks($node);
                    break;

                case 'reference':
                    $this->generateBlocks($node);
                    break;

                case 'action':
                    $this->_generateAction($node, $parent);
                    break;
            }
            //Virtual::dump($attributes);
        }//foreach
    }

    /**
     * Enter description here...
     *
     * @param Varien_Simplexml_Element $node
     * @param Varien_Simplexml_Element $parent
     * @return Core_Model_Layout
     */
    protected function _generateAction($node, $parent)
    {
        $method = (string)$node['method'];
        $parentName = '';
        if (!empty($node['block'])) {
            $parentName = (string)$node['block'];
        }
        else {
            $parentName = $parent->getBlockName();
        }

        if (!empty($parentName)) {
            $block = $this->getBlock($parentName);
        }

        if (!empty($block)) {
            $args = (array)$node->children();
            unset($args['@attributes']);
            foreach ($args as $key => $arg) {
                if (($arg instanceof Core_Model_Layout_Element)) {
                    //Virtual::dump($arg);
                }
            }

            if (isset($node['json'])) {
                $json = explode(' ', (string)$node['json']);
                foreach ($json as $arg) {
                    $args[$arg] = Virtual::helper('core')->jsonDecode($args[$arg]);
                }
            }

            $this->_translateLayoutNode($node, $args);
            call_user_func_array(array($block, $method), $args);
        }

        return $this;
    }

    /**
     * @param $name
     * @return Core_Model_Layout
     */
    public function unsetBlock($name)
    {
        $this->_blocks[$name] = null;
        unset($this->_blocks[$name]);

        return $this;
    }

    /**
     * @param $node
     * @param $args
     */
    protected function _translateLayoutNode($node, &$args)
    {
        if (isset($node['translate'])) {
            // Translate value by core module if module attribute was not set
            $moduleName = (isset($node['module'])) ? (string)$node['module'] : 'core';

            // Handle translations in arrays if needed
            $translatableArguments = explode(' ', (string)$node['translate']);
            foreach ($translatableArguments as $translatableArgumentName) {
                /*
                 * .(dot) character is used as a path separator in nodes hierarchy
                 * e.g. info.title means that Magento needs to translate value of <title> node
                 * that is a child of <info> node
                 */
                // @var $argumentHierarhy array - path to translatable item in $args array
                $argumentHierarchy = explode('.', $translatableArgumentName);
                $argumentStack = &$args;
                $canTranslate = true;
                while (is_array($argumentStack) && count($argumentStack) > 0) {
                    $argumentName = array_shift($argumentHierarchy);
                    if (isset($argumentStack[$argumentName])) {
                        /*
                         * Move to the next element in arguments hieracrhy
                         * in order to find target translatable argument
                         */
                        $argumentStack = &$argumentStack[$argumentName];
                    } else {
                        // Target argument cannot be found
                        $canTranslate = false;
                        break;
                    }
                }
                if ($canTranslate && is_string($argumentStack)) {
                    // $argumentStack is now a reference to target translatable argument so it can be translated
                    $argumentStack = Virtual::helper($moduleName)->__($argumentStack);
                }
            }
        }
    }

    /**
     * Add block object to layout based on xml node data
     * @param $node
     * @param $parent
     * @return Core_Model_Layout
     * @throws Exception
     */
    protected function _generateBlock($node, $parent)
    {
        if (!empty($node['class'])) {
            $className = (string)$node['class'];
        }
        else {
            $className = (string)$node['type'];
        }

        $blockName = (string)$node['name'];
        $block = $this->addBlock($className, $blockName);

        if (!$block) {
            return $this;
        }

        $parentBlock = '';
        if (!empty($node['parent'])) {
            $parentBlock = $this->getBlock((string)$node['parent']);
        }
        else {
            $parentName = $parent->getBlockName();
            if (!empty($parentName)) {
                $parentBlock = $this->getBlock($parentName);
            }
        }

        if (!empty($parentBlock)) {
            $alias = isset($node['as']) ? (string)$node['as'] : '';
            if (isset($node['before'])) {
                $sibling = (string)$node['before'];
                if ('-'===$sibling) {
                    $sibling = '';
                }
                $parentBlock->insert($block, $sibling, false, $alias);
            }
            elseif (isset($node['after'])) {
                $sibling = (string)$node['after'];
                if ('-'===$sibling) {
                    $sibling = '';
                }
                $parentBlock->insert($block, $sibling, true, $alias);
            }
            else {
                $parentBlock->append($block, $alias);
            }
        }

        if (!empty($node['template'])) {
            $block->setTemplate((string)$node['template']);
        }

        if (!empty($node['output'])) {
            $method = (string)$node['output'];
            $this->addOutputBlock($blockName, $method);
        }

        return $this;
    }

    /**
     * Add a block to registry, create new object if needed
     * @param $block
     * @param $blockName
     * @return bool|mixed
     * @throws Exception
     */
    public function addBlock($block, $blockName)
    {
        return $this->createBlock($block, $blockName);
    }

    /**
     * Block Factory
     * @param $type
     * @param string $name
     * @param array $attributes
     * @return bool|mixed
     * @throws Exception
     */
    public function createBlock($type, $name='', array $attributes = array())
    {
        try {
            $block = $this->_getBlockInstance($type, $attributes);
        }
        catch (Exception $e) {
            Virtual::throwException('Invalid block type '.$type, $e->getMessage());
            return false;
        }

        $block->setType($type);
        $block->setNameInLayout($name);
        $block->addData($attributes);
        $block->setLayout($this);

        $this->_blocks[$name] = $block;

        return $this->_blocks[$name];
    }

    /**
     * Create block object instance based on block type
     * @param $block
     * @param array $attributes
     * @return string
     * @throws Exception
     */
    protected function _getBlockInstance($block, array $attributes=array())
    {
        if (is_string($block)) {
            if (strpos($block, '/')!==false) {
                if (!$block = Virtual::getConfig()->getBlockClassName($block)) {
                    Virtual::throwException('Invalid block type: '. $block);
                }
            }

            if (class_exists($block, false) || virtualFindClassFile($block)) {
                $block = new $block($attributes);
            }
            else {
                Virtual::throwException('Class not found "<b>'.Virtual::getConfig()->getBlockClassName($block).'</b>"');
            }
        }

        return $block;
    }

    /**
     * Retrieve direct output flag
     *
     * @return bool
     */
    public function getDirectOutput()
    {
        return $this->_directOutput;
    }

    /**
     * Declaring layout direct output flag
     *
     * @param   bool $flag
     * @return  Core_Model_Layout
     */
    public function setDirectOutput($flag)
    {
        $this->_directOutput = $flag;

        return $this;
    }

    /**
     * @param $name
     * @param $block
     * @return Core_Model_Layout
     */
    public function setBlock($name, $block)
    {
        $this->_blocks[$name] = $block;

        return $this;
    }

    /**
     * Retrieve all blocks from registry as array
     *
     * @return array
     */
    public function getAllBlocks()
    {
        return $this->_blocks;
    }

    /**
     * Get block object by name
     *
     * @param string $name
     * @return Core_Block_Abstract
     */
    public function getBlock($name)
    {
        if (isset($this->_blocks[$name])) {
            return $this->_blocks[$name];
        }
        else {
            return false;
        }
    }

    /**
     * @param $blockName
     * @param string $method
     * @return Core_Model_Layout
     */
    public function addOutputBlock($blockName, $method='toHtml')
    {
        $this->_output[$blockName] = array($blockName, $method);

        return $this;
    }

    /**
     * Retrieve helper object
     *
     * @param   string $name
     * @return  Core_Helper_Abstract
     */
    public function helper($name)
    {
        $helper = Virtual::helper($name);
        if (!$helper) {
            return false;
        }
        return $helper->setLayout($this);
    }


    /**
     * @param $blockIdentifier
     * @return mixed
     * @throws Exception
     */
    public function getBlockByIdentifier($blockIdentifier)
    {
        $block = Virtual::getModel('cms/block')->loadBlockByIdentifier($blockIdentifier);

        $replaceArray = array(
            "{{secure_base_url}}",
        );

        return str_replace($replaceArray,Virtual::getStoreConfig(self::SECURE_BASE_URL_PATH),$block->getContent());
    }



}//End of class