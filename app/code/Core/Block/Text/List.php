<?php


class Core_Block_Text_List extends Core_Block_Text
{
    protected function _toHtml()
    {
        $this->setText('');

        foreach ($this->getSortedChildren() as $name) {
            $block = $this->getLayout()->getBlock($name);
            if (!$block) {
                Virtual::throwException('Invalid block: '.$name);
            }
            $this->addText($block->toHtml());
        }

        return parent::_toHtml();
    }

}//End of class