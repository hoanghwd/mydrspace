<?php
/**
 * Created by PhpStorm.
 * User: hdo
 * Date: 3/31/2019
 * Time: 5:24 PM
 */

class Core_Model_Layout_Element extends Varien_Simplexml_Element
{
    /**
     * @param $args
     * @return Core_Model_Layout_Element
     */
    public function prepare($args)
    {
        switch ($this->getName()) {
            case 'layoutUpdate':
                break;

            case 'layout':
                break;

            case 'update':
                break;

            case 'remove':
                break;

            case 'block':
                $this->prepareBlock($args);
                break;

            case 'reference':
                $this->prepareReference($args);
                break;

            case 'action':
                $this->prepareAction($args);
                break;

            default:
                $this->prepareActionArgument($args);
                break;
        }
        $children = $this->children();
        foreach ($this as $child) {
            $child->prepare($args);
        }

        return $this;
    }

    /**
     * @return bool|string
     */
    public function getBlockName()
    {
        $tagName = (string)$this->getName();
        if ('block'!==$tagName && 'reference'!==$tagName || empty($this['name'])) {
            return false;
        }

        return (string)$this['name'];
    }

    /**
     * @param $args
     * @return Core_Model_Layout_Element
     */
    public function prepareBlock($args)
    {
        $type = (string)$this['type'];
        $name = (string)$this['name'];

        $className = (string)$this['class'];
        if (!$className) {
            $className = Virtual::getConfig()->getBlockClassName($type);
            $this->addAttribute('class', $className);
        }

        $parent = $this->getParent();
        if (isset($parent['name']) && !isset($this['parent'])) {
            $this->addAttribute('parent', (string)$parent['name']);
        }

        return $this;
    }

    /**
     * @param $args
     * @return Core_Model_Layout_Element
     */
    public function prepareReference($args)
    {
        return $this;
    }

    /**
     * @param $args
     * @return Core_Model_Layout_Element
     */
    public function prepareAction($args)
    {
        $parent = $this->getParent();
        $this->addAttribute('block', (string)$parent['name']);

        return $this;
    }

    /**
     * @param $args
     * @return Core_Model_Layout_Element
     */
    public function prepareActionArgument($args)
    {
        return $this;
    }

}//End of class