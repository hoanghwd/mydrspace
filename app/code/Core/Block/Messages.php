<?php

/**
 * Messages block
 * @package    Core
 */

class Core_Block_Messages extends Core_Block_Template
{
    /**
     * Messages collection
     *
     * @var Core_Model_Message_Collection
     */
    protected $_messages;

    /**
     * Store first level html tag name for messages html output
     *
     * @var string
     */
    protected $_messagesFirstLevelTagName = 'ul';

    /**
     * Store second level html tag name for messages html output
     *
     * @var string
     */
    protected $_messagesSecondLevelTagName = 'li';

    /**
     * Store content wrapper html tag name for messages html output
     *
     * @var string
     */
    protected $_messagesContentWrapperTagName = 'span';

    /**
     * Flag which require message text escape
     *
     * @var bool
     */
    protected $_escapeMessageFlag = false;

    /**
     * Storage for used types of message storages
     *
     * @var array
     */
    protected $_usedStorageTypes = array('core/session');

    public function _prepareLayout()
    {
        $this->addMessages(Virtual::getSingleton('core/session')->getMessages(true));
        parent::_prepareLayout();
    }

    /**
     * Add messages to display
     *
     * @param Core_Model_Message_Collection $messages
     * @return Core_Block_Messages
     */
    public function addMessages(Core_Model_Message_Collection $messages)
    {
        foreach ($messages->getItems() as $message) {
            $this->getMessageCollection()->add($message);
        }
        return $this;
    }


    /**
     * Retrieve messages collection
     *
     * @return Core_Model_Message_Collection
     */
    public function getMessageCollection()
    {
        if (!($this->_messages instanceof Core_Model_Message_Collection)) {
            $this->_messages = Virtual::getModel('core/message_collection');
        }
        
        return $this->_messages;
    }

    /**
     * Adding new message to message collection
     *
     * @param   Core_Model_Message_Abstract $message
     * @return  Core_Block_Messages
     */
    public function addMessage(Core_Model_Message_Abstract $message)
    {
        $this->getMessageCollection()->add($message);

        return $this;
    }

    /**
     * Adding new error message
     *
     * @param   string $message
     * @return  Core_Block_Messages
     */
    public function addError($message)
    {
        $this->addMessage(Virtual::getSingleton('core/message')->error($message));

        return $this;
    }

    /**
     * Adding new warning message
     *
     * @param   string $message
     * @return  Core_Block_Messages
     */
    public function addWarning($message)
    {
        $this->addMessage(Virtual::getSingleton('core/message')->warning($message));
        
        return $this;
    }

    /**
     * Adding new nitice message
     *
     * @param   string $message
     * @return  Core_Block_Messages
     */
    public function addNotice($message)
    {
        $this->addMessage(Mage::getSingleton('core/message')->notice($message));
        
        return $this;
    }

    /**
     * Adding new success message
     *
     * @param   string $message
     * @return  Core_Block_Messages
     */
    public function addSuccess($message)
    {
        $this->addMessage(Mage::getSingleton('core/message')->success($message));

        return $this;
    }

    /**
     * Retrieve messages in HTML format
     *
     * @param   string $type
     * @return  string
     */
    public function getHtml($type=null)
    {
        $html = '<' . $this->_messagesFirstLevelTagName . ' id="admin_messages">';
        foreach ($this->getMessages($type) as $message) {
            $html.= '<' . $this->_messagesSecondLevelTagName . ' class="'.$message->getType().'-msg">'
            . ($this->_escapeMessageFlag) ? $this->escapeHtml($message->getText()) : $message->getText()
                . '</' . $this->_messagesSecondLevelTagName . '>';
        }
        $html .= '</' . $this->_messagesFirstLevelTagName . '>';

        return $html;
    }

    /**
     * Retrieve messages array by message type
     *
     * @param   string $type
     * @return  array
     */
    public function getMessages($type=null)
    {
        return $this->getMessageCollection()->getItems($type);
    }

    /**
     * Retrieve messages in HTML format grouped by type
     *
     * @param   string $type
     * @return  string
     */
    public function getGroupedHtml()
    {
        $types = array(
            Core_Model_Message::ERROR,
            Core_Model_Message::WARNING,
            Core_Model_Message::NOTICE,
            Core_Model_Message::SUCCESS
        );
        $html = '';
        foreach ($types as $type) {
            if ( $messages = $this->getMessages($type) ) {
                if ( !$html ) {
                    $html .= '<' . $this->_messagesFirstLevelTagName . ' class="messages">';
                }
                $html .= '<' . $this->_messagesSecondLevelTagName . ' class="' . $type . '-msg">';
                $html .= '<' . $this->_messagesFirstLevelTagName . '>';

                foreach ( $messages as $message ) {
                    $html.= '<' . $this->_messagesSecondLevelTagName . '>';
                    $html.= '<' . $this->_messagesContentWrapperTagName . '>';
                    $html.= ($this->_escapeMessageFlag) ? $this->escapeHtml($message->getText()) : $message->getText();
                    $html.= '</' . $this->_messagesContentWrapperTagName . '>';
                    $html.= '</' . $this->_messagesSecondLevelTagName . '>';
                }
                $html .= '</' . $this->_messagesFirstLevelTagName . '>';
                $html .= '</' . $this->_messagesSecondLevelTagName . '>';
            }
        }
        if ( $html) {
            $html .= '</' . $this->_messagesFirstLevelTagName . '>';
        }
        return $html;
    }

    protected function _toHtml()
    {
        return $this->getGroupedHtml();
    }

    /**
     * Set messages first level html tag name for output messages as html
     *
     * @param string $tagName
     */
    public function setMessagesFirstLevelTagName($tagName)
    {
        $this->_messagesFirstLevelTagName = $tagName;
    }

    /**
     * Set messages first level html tag name for output messages as html
     *
     * @param string $tagName
     */
    public function setMessagesSecondLevelTagName($tagName)
    {
        $this->_messagesSecondLevelTagName = $tagName;
    }


}//End of class