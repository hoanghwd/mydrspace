<?php

abstract class Core_Block_Abstract extends Varien_Object
{
    /**
     * Block name in layout
     *
     * @var string
     */
    protected $_nameInLayout;

    /**
     * Parent layout of the block
     *
     * @var Core_Model_Layout
     */
    protected $_layout;

    /**
     * Parent block
     *
     * @var Core_Block_Abstract
     */
    protected $_parent;

    /**
     * Short alias of this block that was refered from parent
     *
     * @var string
     */
    protected $_alias;

    /**
     * Suffix for name of anonymous block
     *
     * @var string
     */
    protected $_anonSuffix;

    /**
     * Contains references to child block objects
     *
     * @var array
     */
    protected $_children = array();

    /**
     * Sorted children list
     *
     * @var array
     */
    protected $_sortedChildren = array();

    /**
     * Children blocks HTML cache array
     *
     * @var array
     */
    protected $_childrenHtmlCache = array();

    /**
     * Arbitrary groups of child blocks
     *
     * @var array
     */
    protected $_childGroups = array();

    /**
     * Request object
     *
     * @var Zend_Controller_Request_Http
     */
    protected $_request;

    /**
     * Messages block instance
     *
     * @var Core_Block_Messages
     */
    protected $_messagesBlock = null;

    /**
     * Whether this block was not explicitly named
     *
     * @var boolean
     */
    protected $_isAnonymous = false;

    /**
     * Parent block
     *
     * @var Core_Block_Abstract
     */
    protected $_parentBlock;

    /**
     * Block html frame open tag
     * @var string
     */
    protected $_frameOpenTag;

    /**
     * Block html frame close tag
     * @var string
     */
    protected $_frameCloseTag;

    /**
     * Url object
     *
     * @var Core_Model_Url
     */
    protected static $_urlModel;

    /**
     * @var Varien_Object
     */
    private static $_transportObject;

    /**
     * Array of block sort priority instructions
     *
     * @var array
     */
    protected $_sortInstructions = array();

    /**
     * Application instance
     *
     * @var Core_Model_App
     */
    protected $_app;

    /**
     * Initialize factory instance
     *
     * @param array $args
     */
    public function __construct(array $args = array())
    {

        if (!empty($args['app']) && ($args['app'] instanceof Core_Model_App)) {
            $this->_app = $args['app'];
        }
        parent::__construct($args);
    }

    /**
     * Internal constructor, that is called from real constructor
     *
     * Please override this one instead of overriding real __construct constructor
     *
     */
    protected function _construct()
    {
        /**
         * Please override this one instead of overriding real __construct constructor
         */
    }

    /**
     * Retrieve application instance
     * @return Core_Model_App|mixed
     * @throws Zend_Controller_Request_Exception
     */
    protected function _getApp()
    {
        return is_null($this->_app) ? Virtual::app() : $this->_app;
    }

    /**
     * Retrieve request object
     *
     * @return Core_Controller_Request_Http
     * @throws Exception
     */
    public function getRequest()
    {
        $controller = $this->_getApp()->getFrontController();
        if ($controller) {
            $this->_request = $controller->getRequest();
        }
        else {
            Virtual::throwException("Can't retrieve request object");
        }

        return $this->_request;
    }

    /**
     * Retrieve parent block
     *
     * @return Core_Block_Abstract
     */
    public function getParentBlock()
    {
        return $this->_parentBlock;
    }

    /**
     * Set parent block
     *
     * @param Core_Block_Abstract $block
     * @return Core_Block_Abstract
     */
    public function setParentBlock(Core_Block_Abstract $block)
    {
        $this->_parentBlock = $block;

        return $this;
    }

    /**
     * Retrieve current action object
     * @return mixed
     * @throws Zend_Controller_Request_Exception
     */
    public function getAction()
    {
        return $this->_getApp()->getFrontController()->getAction();
    }

    /**
     * Set layout object
     *
     * @param   Core_Model_Layout $layout
     * @return  Core_Block_Abstract
     */
    public function setLayout(Core_Model_Layout $layout)
    {
        $this->_layout = $layout;
        $this->_prepareLayout();

        return $this;
    }

    /**
     * Preparing global layout
     *
     * You can redefine this method in child classes for changing layout
     *
     * @return Core_Block_Abstract
     */
    protected function _prepareLayout()
    {
        return $this;
    }

    /**
     * Retrieve layout object
     *
     * @return Core_Model_Layout
     */
    public function getLayout()
    {
        return $this->_layout;
    }

    /**
     * Check if block is using auto generated (Anonymous) name
     * @return bool
     */
    public function getIsAnonymous()
    {
        return $this->_isAnonymous;
    }

    /**
     * Set the anonymous flag
     *
     * @param  bool $flag
     * @return Core_Block_Abstract
     */
    public function setIsAnonymous($flag)
    {
        $this->_isAnonymous = (bool)$flag;

        return $this;
    }

    /**
     * Returns anonymous block suffix
     *
     * @return string
     */
    public function getAnonSuffix()
    {
        return $this->_anonSuffix;
    }

    /**
     * Set anonymous suffix for current block
     *
     * @param string $suffix
     * @return Core_Block_Abstract
     */
    public function setAnonSuffix($suffix)
    {
        $this->_anonSuffix = $suffix;

        return $this;
    }

    /**
     * Returns block alias
     *
     * @return string
     */
    public function getBlockAlias()
    {
        return $this->_alias;
    }

    /**
     * Set block alias
     *
     * @param string $alias
     * @return Core_Block_Abstract
     */
    public function setBlockAlias($alias)
    {
        $this->_alias = $alias;

        return $this;
    }

    /**
     * Set block's name in layout and unsets previous link if such exists.
     *
     * @param string $name
     * @return Core_Block_Abstract
     */
    public function setNameInLayout($name)
    {
        if (!empty($this->_nameInLayout) && $this->getLayout()) {
            $this->getLayout()->unsetBlock($this->_nameInLayout)
                 ->setBlock($name, $this);
        }
        $this->_nameInLayout = $name;

        return $this;
    }

    /**
     * Retrieve sorted list of children.
     *
     * @return array
     */
    public function getSortedChildren()
    {
        $this->sortChildren();

        return $this->_sortedChildren;
    }

    /**
     * Set block attribute value
     *
     * Wrapper for method "setData"
     *
     * @param   string $name
     * @param   mixed $value
     * @return  Core_Block_Abstract
     */
    public function setAttribute($name, $value = null)
    {
        return $this->setData($name, $value);
    }

    /**
     * Set child block
     *
     * @param   string $alias
     * @param   Core_Block_Abstract $block
     * @return  Core_Block_Abstract
     */
    public function setChild($alias, $block)
    {
        if (is_string($block)) {
            $block = $this->getLayout()->getBlock($block);
        }
        if (!$block) {
            return $this;
        }

        //Virtual::dump($block);

        if ($block->getIsAnonymous()) {

            $suffix = $block->getAnonSuffix();
            if (empty($suffix)) {
                $suffix = 'child' . sizeof($this->_children);
            }
            $blockName = $this->getNameInLayout() . '.' . $suffix;

            if ($this->getLayout()) {
                $this->getLayout()->unsetBlock($block->getNameInLayout())
                    ->setBlock($blockName, $block);
            }

            $block->setNameInLayout($blockName);
            $block->setIsAnonymous(false);

            if (empty($alias)) {
                $alias = $blockName;
            }
        }

        $block->setParentBlock($this);
        $block->setBlockAlias($alias);
        $this->_children[$alias] = $block;

        return $this;
    }

    /**
     * Unset child block
     *
     * @param  string $alias
     * @return Core_Block_Abstract
     */
    public function unsetChild($alias)
    {
        if (isset($this->_children[$alias])) {
            /** @var Mage_Core_Block_Abstract $block */
            $block = $this->_children[$alias];
            $name = $block->getNameInLayout();
            unset($this->_children[$alias]);
            $key = array_search($name, $this->_sortedChildren);
            if ($key !== false) {
                unset($this->_sortedChildren[$key]);
            }
        }

        return $this;
    }

    /**
     * Call a child and unset it, if callback matched result
     *
     * $params will pass to child callback
     * $params may be array, if called from layout with elements with same name, for example:
     * ...<foo>value_1</foo><foo>value_2</foo><foo>value_3</foo>
     *
     * Or, if called like this:
     * ...<foo>value_1</foo><bar>value_2</bar><baz>value_3</baz>
     * - then it will be $params1, $params2, $params3
     *
     * It is no difference anyway, because they will be transformed in appropriate way.
     *
     * @param string $alias
     * @param string $callback
     * @param mixed $result
     * @param array $params
     * @return Core_Block_Abstract
     */
    public function unsetCallChild($alias, $callback, $result, $params)
    {
        $child = $this->getChild($alias);
        if ($child) {
            $args = func_get_args();
            $alias = array_shift($args);
            $callback = array_shift($args);
            $result = (string)array_shift($args);
            if (!is_array($params)) {
                $params = $args;
            }

            if ($result == call_user_func_array(array(&$child, $callback), $params)) {
                $this->unsetChild($alias);
            }
        }

        return $this;
    }

    /**
     * Unset all children blocks
     *
     * @return Core_Block_Abstract
     */
    public function unsetChildren()
    {
        $this->_children = array();
        $this->_sortedChildren = array();

        return $this;
    }

    /**
     * Retrieve child block by name
     *
     * @param  string $name
     * @return mixed
     */
    public function getChild($name = '')
    {
        if ($name === '') {
            return $this->_children;
        }
        elseif (isset($this->_children[$name])) {
            return $this->_children[$name];
        }

        return false;
    }

    /**
     * Retrieve child block HTML
     *
     * @param   string $name
     * @param   boolean $sorted
     * @return  string
     */
    public function getChildHtml($name = '', $sorted = false)
    {
        if ($name === '') {
            if ($sorted) {
                $children = array();
                foreach ($this->getSortedChildren() as $childName) {
                    $children[$childName] = $this->getLayout()->getBlock($childName);
                }
            }
            else {
                $children = $this->getChild();
            }
            $out = '';
            foreach ($children as $child) {
                $out .= $this->_getChildHtml($child->getBlockAlias());
            }
            return $out;
        }
        else {
            return $this->_getChildHtml($name);
        }
    }

    /**
     * @param string $name          Parent block name
     * @param string $childName     OPTIONAL Child block name
     * @param bool $sorted          OPTIONAL @see getChildHtml()
     * @return string
     */
    public function getChildChildHtml($name, $childName = '', $sorted = false)
    {
        if (empty($name)) {
            return '';
        }
        $child = $this->getChild($name);
        if (!$child) {
            return '';
        }
        return $child->getChildHtml($childName, $sorted);
    }

    /**
     * Obtain sorted child blocks
     *
     * @return array
     */
    public function getSortedChildBlocks()
    {
        $children = array();
        foreach ($this->getSortedChildren() as $childName) {
            $children[$childName] = $this->getLayout()->getBlock($childName);
        }
        return $children;
    }

    /**
     * Retrieve child block HTML
     * @param $name
     * @return string
     */
    protected function _getChildHtml($name)
    {
        $child = $this->getChild($name);

        if (!$child) {
            $html = '';
        }
        else {
            $this->_beforeChildToHtml($name, $child);
            $html = $child->toHtml();
        }


        return $html;
    }

    /**
     * Prepare child block before generate html
     *
     * @param   string $name
     * @param   Core_Block_Abstract $child
     */
    protected function _beforeChildToHtml($name, $child)
    {
    }

    /**
     * Retrieve block html
     * @param $name
     * @return string
     * @throws Zend_Controller_Request_Exception
     */
    public function getBlockHtml($name)
    {
        if (!($layout = $this->getLayout()) && !($layout = $this->getAction()->getLayout())) {
            return '';
        }
        if (!($block = $layout->getBlock($name))) {
            return '';
        }
        return $block->toHtml();
    }

    /**
     * @param $block
     * @param string $siblingName
     * @param bool $after
     * @param string $alias
     * @return Core_Block_Abstract
     * @throws Exception
     */
    public function insert($block, $siblingName = '', $after = false, $alias = '')
    {
        if (is_string($block)) {
            $block = $this->getLayout()->getBlock($block);
        }

        if (!$block) {
            Virtual::throwException('Invalid block name to set child '. $alias.' '.$block);
            return $this;
        }

        if ($block->getIsAnonymous()) {
            $this->setChild('', $block);
            $name = $block->getNameInLayout();
        }
        elseif ('' != $alias) {
            $this->setChild($alias, $block);
            $name = $block->getNameInLayout();
        }
        else {
            $name = $block->getNameInLayout();
            $this->setChild($name, $block);
        }

        if ($siblingName === '') {
            if ($after) {
                array_push($this->_sortedChildren, $name);
            }
            else {
                array_unshift($this->_sortedChildren, $name);
            }
        }
        else {
            $key = array_search($siblingName, $this->_sortedChildren);
            if (false !== $key) {
                if ($after) {
                    $key++;
                }
                array_splice($this->_sortedChildren, $key, 0, $name);
            }
            else {
                if ($after) {
                    array_push($this->_sortedChildren, $name);
                }
                else {
                    array_unshift($this->_sortedChildren, $name);
                }
            }

            $this->_sortInstructions[$name] = array($siblingName, (bool)$after, false !== $key);
        }

        return $this;
    }

    /**
     * Sort block's children
     *
     * @param boolean $force force re-sort all children
     * @return Core_Block_Abstract
     */
    public function sortChildren($force = false)
    {
        foreach ($this->_sortInstructions as $name => $list) {
            list($siblingName, $after, $exists) = $list;
            if ($exists && !$force) {
                continue;
            }
            $this->_sortInstructions[$name][2] = true;

            $index = array_search($name, $this->_sortedChildren);
            $siblingKey = array_search($siblingName, $this->_sortedChildren);

            if ($index === false || $siblingKey === false) {
                continue;
            }

            if ($after) {
                // insert after block
                if ($index == $siblingKey + 1) {
                    continue;
                }
                // remove sibling from array
                array_splice($this->_sortedChildren, $index, 1, array());
                // insert sibling after
                array_splice($this->_sortedChildren, $siblingKey + 1, 0, array($name));
            }
            else {
                // insert before block
                if ($index == $siblingKey - 1) {
                    continue;
                }
                // remove sibling from array
                array_splice($this->_sortedChildren, $index, 1, array());
                // insert sibling after
                array_splice($this->_sortedChildren, $siblingKey, 0, array($name));
            }
        }

        return $this;
    }

    /**
     * Append child block
     * @param $block
     * @param string $alias
     * @return Core_Block_Abstract
     * @throws Exception
     */
    public function append($block, $alias = '')
    {
        $this->insert($block, '', true, $alias);

        return $this;
    }

    /**
     * Make sure specified block will be registered in the specified child groups
     *
     * @param string $groupName
     * @param Core_Block_Abstract $child
     */
    public function addToChildGroup($groupName, Core_Block_Abstract $child)
    {
        if (!isset($this->_childGroups[$groupName])) {
            $this->_childGroups[$groupName] = array();
        }
        if (!in_array($child->getBlockAlias(), $this->_childGroups[$groupName])) {
            $this->_childGroups[$groupName][] = $child->getBlockAlias();
        }
    }

    /**
     * Add self to the specified group of parent block
     *
     * @param string $groupName
     * @return Core_Block_Abstract
     */
    public function addToParentGroup($groupName)
    {
        $this->getParentBlock()->addToChildGroup($groupName, $this);

        return $this;
    }

    /**
     * Get a group of child blocks
     *
     * Returns an array of <alias> => <block>
     * or an array of <alias> => <callback_result>
     * The callback currently supports only $this methods and passes the alias as parameter
     *
     * @param string $groupName
     * @param string $callback
     * @param bool $skipEmptyResults
     * @return array
     */
    public function getChildGroup($groupName, $callback = null, $skipEmptyResults = true)
    {
        $result = array();
        if (!isset($this->_childGroups[$groupName])) {
            return $result;
        }
        foreach ($this->getSortedChildBlocks() as $block) {
            $alias = $block->getBlockAlias();
            if (in_array($alias, $this->_childGroups[$groupName])) {
                if ($callback) {
                    $row = $this->$callback($alias);
                    if (!$skipEmptyResults || $row) {
                        $result[$alias] = $row;
                    }
                } else {
                    $result[$alias] = $block;
                }

            }
        }

        return $result;
    }

    /**
     * Get a value from child block by specified key
     *
     * @param string $alias
     * @param string $key
     * @return mixed
     */
    public function getChildData($alias, $key = '')
    {
        $child = $this->getChild($alias);
        if ($child) {
            return $child->getData($key);
        }
    }

    /**
     * Before rendering html, but after trying to load cache
     *
     * @return Core_Block_Abstract
     */
    protected function _beforeToHtml()
    {
        return $this;
    }

    /**
     * Specify block output frame tags
     *
     * @param $openTag
     * @param $closeTag
     * @return Core_Block_Abstract
     */
    public function setFrameTags($openTag, $closeTag = null)
    {
        $this->_frameOpenTag = $openTag;
        if ($closeTag) {
            $this->_frameCloseTag = $closeTag;
        }
        else {
            $this->_frameCloseTag = '/' . $openTag;
        }

        return $this;
    }

    /**
     * Processing block html after rendering
     *
     * @param   string $html
     * @return  string
     */
    protected function _afterToHtml($html)
    {
        return $html;
    }

    /**
     * Override this method in descendants to produce html
     *
     * @return string
     */
    protected function _toHtml()
    {
        return '';
    }

    /**
     * Produce and return block's html output
     *
     * It is a final method, but you can override _toHtml() method in descendants if needed.
     *
     * @return string
     */
    final public function toHtml()
    {
        $this->_beforeToHtml();
        $html = $this->_toHtml();
        $html = $this->_afterToHtml($html);

        /**
         * Use single transport object instance for all blocks
         */
        if (self::$_transportObject === null) {
            self::$_transportObject = new Varien_Object;
        }
        self::$_transportObject->setHtml($html);

        $html = self::$_transportObject->getHtml();

        return $html;
    }

    /**
     * Alias for getName method.
     *
     * @return string
     */
    public function getNameInLayout()
    {
        return $this->_nameInLayout;
    }

    /**
     * Get children blocks count
     * @return int
     */
    public function countChildren()
    {
        return count($this->_children);
    }

    /**
     * Checks is request Url is secure
     * @return bool
     * @throws Zend_Controller_Request_Exception
     */
    protected function _isSecure()
    {
        return $this->_getApp()->getFrontController()->getRequest()->isSecure();
    }

    /**
     * Retrieve module name of block
     *
     * @return string
     */
    public function getModuleName()
    {
        $module = $this->getData('module_name');
        if (is_null($module)) {
            $class = get_class($this);
            $module = substr($class, 0, strpos($class, '_Block'));
            $this->setData('module_name', $module);
        }
        return $module;
    }

    /**
     * Generate base64-encoded url by route and parameters
     *
     * @param   string $route
     * @param   array $params
     * @return  string
     */
    public function getUrlBase64($route = '', $params = array())
    {
        return Virtual::helper('core')->urlEncode($this->getUrl($route, $params));
    }

    /**
     * Retrieve url of skins file
     * @param null $file
     * @param array $params
     * @return string
     * @throws Exception
     */
    public function getSkinUrl($file = null, array $params = array())
    {
        return Virtual::getDesign()->getSkinUrl($file, $params);
    }

    /**
     * Generate url by route and parameters
     *
     * @param   string $route
     * @param   array $params
     * @return  string
     */
    public function getUrl($route = '', $params = array())
    {
        return $this->_getUrlModel()->getUrl($route, $params);
    }

    /**
     * @param $name
     * @return Core_Helper_Abstract|null
     */
    protected function _helper($name)
    {
        return Virtual::helper($name);
    }

    /**
     * Returns helper object
     *
     * @param string $name
     * @return Core_Helper_Abstract
     */
    public function helper($name)
    {
        return Virtual::helper($name);
    }

    /**
     * Escape html entities
     *
     * @param   mixed $data
     * @param   array $allowedTags
     * @return  string
     */
    public function escapeHtml($data, $allowedTags = null)
    {
        return $this->helper('core')->escapeHtml($data, $allowedTags);
    }

    /**
     * Wrapper for standard strip_tags() function with extra functionality for html entities
     *
     * @param string $data
     * @param string $allowableTags
     * @param bool $allowHtmlEntities
     * @return string
     */
    public function stripTags($data, $allowableTags = null, $allowHtmlEntities = false)
    {
        return $this->helper('core')->stripTags($data, $allowableTags, $allowHtmlEntities);
    }

    /**
     * @deprecated after 1.4.0.0-rc1
     * @see self::escapeUrl()
     */
    public function urlEscape($data)
    {
        return $this->escapeUrl($data);
    }

    /**
     * Escape html entities in url
     *
     * @param string $data
     * @return string
     */
    public function escapeUrl($data)
    {
        return $this->helper('core')->escapeUrl($data);
    }

    /**
     * Escape quotes inside html attributes
     * Use $addSlashes = false for escaping js that inside html attribute (onClick, onSubmit etc)
     *
     * @param  string $data
     * @param  bool $addSlashes
     * @return string
     */
    public function quoteEscape($data, $addSlashes = false)
    {
        return $this->helper('core')->quoteEscape($data, $addSlashes);
    }

    /**
     * Escape quotes in java scripts
     *
     * @param mixed $data
     * @param string $quote
     * @return mixed
     */
    public function jsQuoteEscape($data, $quote = '\'')
    {
        return $this->helper('core')->jsQuoteEscape($data, $quote);
    }


}//End of class