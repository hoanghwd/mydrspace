<?php

class Core_Page_Block_Html_Topmenu_Renderer extends Core_Page_Block_Html_Topmenu
{
    protected $_templateFile;


    /**
     * @return mixed
     * @throws Exception
     */
    public function getMenu()
    {
        return Virtual::getModel('menu/menu')->getCustomCollection();
    }

    /**
     * Renders block html
     * @return string
     * @throws Exception
     */
    protected function _toHtml()
    {
        $menuTree = $this->getMenuTree();
        $childrenWrapClass = $this->getChildrenWrapClass();
        if (!$this->getTemplate() || is_null($menuTree) || is_null($childrenWrapClass)) {
            throw new Exception("Top-menu renderer isn't fully configured.");
        }

        $includeFilePath = $this->getTemplateFile();

        if (strpos($includeFilePath, realpath(Virtual::getBaseDir('design'))) === 0 ) {
            $this->_templateFile = $includeFilePath;
        }
        else {
            throw new Exception('Not valid template file:' . $this->_templateFile);
        }

        return $this->render($menuTree, $childrenWrapClass);
    }

    /**
     * Fetches template. If template has return statement, than its value is used and direct output otherwise.
     * @param Varien_Data_Tree_Node $menuTree
     * @param $childrenWrapClass
     * @return string
     */
    public function render(Varien_Data_Tree_Node $menuTree, $childrenWrapClass)
    {
        ob_start();
        $html = include $this->_templateFile;
        $directOutput = ob_get_clean();

        if (is_string($html)) {
            return $html;
        }
        else {
            return $directOutput;
        }
    }

}//End of class