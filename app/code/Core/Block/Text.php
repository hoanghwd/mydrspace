<?php

class Core_Block_Text extends Core_Block_Abstract
{
    /**
     * @param $text
     * @return Core_Block_Text
     */
    public function setText($text)
    {
        $this->setData('text', $text);

        return $this;
    }

    /**
     * @return mixed
     */
    public function getText()
    {
        return $this->getData('text');
    }

    /**
     * @param $text
     * @param bool $before
     */
    public function addText($text, $before=false)
    {
        if ($before) {
            $this->setText($text.$this->getText());
        }
        else {
            $this->setText($this->getText().$text);
        }
    }

    /**
     * @return mixed|string
     */
    protected function _toHtml()
    {
        if (!$this->_beforeToHtml()) {
            return '';
        }

        return $this->getText();
    }

}//End of class