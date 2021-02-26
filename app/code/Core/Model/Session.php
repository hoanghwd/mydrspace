<?php
/**
 * Created by PhpStorm.
 * User: hdo
 * Date: 1/17/2019
 * Time: 3:59 PM
 */

/**
 * Core session model
 *
 * @todo extend from Core_Model_Session_Abstract
 *
 * @method null|bool getCookieShouldBeReceived()
 * @method Core_Model_Session setCookieShouldBeReceived(bool $flag)
 * @method Core_Model_Session unsCookieShouldBeReceived()
 */

class Core_Model_Session extends Core_Model_Session_Abstract
{
    public function __construct($data = array())
    {
        $name = isset($data['name']) ? $data['name'] : null;
        $this->init('core', $name);
    }

    /**
     * Retrieve Session Form Key
     *
     * @return string A 16 bit unique key for forms
     */
    public function getFormKey()
    {
        if (!$this->getData('_form_key')) {
            $this->renewFormKey();
        }
        return $this->getData('_form_key');
    }

    /**
     * Creates new Form key
     */
    public function renewFormKey()
    {
        $this->setData('_form_key', Virtual::helper('core')->getRandomString(16));
    }

    /**
     * Validates Form key
     *
     * @param string|null $formKey
     * @return bool
     */
    public function validateFormKey($formKey)
    {
        return ($formKey === $this->getFormKey());
    }

    /**
     * Adding new message to message collection
     *
     * @param   Core_Model_Message_Abstract $message
     * @return  Core_Model_Session_Abstract
     */
    public function addMessage(Core_Model_Message_Abstract $message)
    {
        $this->getMessages()->add($message);
        //Mage::dispatchEvent('core_session_abstract_add_message');
        return $this;
    }

    /**
     * Adding new error message
     *
     * @param   string $message
     * @return  Core_Model_Session_Abstract
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
     * @return  Core_Model_Session_Abstract
     */
    public function addWarning($message)
    {
        $this->addMessage(Virtual::getSingleton('core/message')->warning($message));
        return $this;
    }

    /**
     * Adding new notice message
     *
     * @param   string $message
     * @return  Core_Model_Session_Abstract
     */
    public function addNotice($message)
    {
        $this->addMessage(Virtual::getSingleton('core/message')->notice($message));
        return $this;
    }

    /**
     * Adding new success message
     *
     * @param   string $message
     * @return  Core_Model_Session_Abstract
     */
    public function addSuccess($message)
    {
        $this->addMessage(Virtual::getSingleton('core/message')->success($message));
        return $this;
    }

    /**
     * Adds messages array to message collection, but doesn't add duplicates to it
     *
     * @param   array|string|Core_Model_Message_Abstract $messages
     * @return  Core_Model_Session_Abstract
     */
    public function addUniqueMessages($messages)
    {
        if (!is_array($messages)) {
            $messages = array($messages);
        }
        if (!$messages) {
            return $this;
        }

        $messagesAlready = array();
        $items = $this->getMessages()->getItems();
        foreach ($items as $item) {
            if ($item instanceof Core_Model_Message_Abstract) {
                $text = $item->getText();
            } else if (is_string($item)) {
                $text = $item;
            } else {
                continue; // Some unknown object, do not put it in already existing messages
            }
            $messagesAlready[$text] = true;
        }

        foreach ($messages as $message) {
            if ($message instanceof Core_Model_Message_Abstract) {
                $text = $message->getText();
            } else if (is_string($message)) {
                $text = $message;
            } else {
                $text = null; // Some unknown object, add it anyway
            }

            // Check for duplication
            if ($text !== null) {
                if (isset($messagesAlready[$text])) {
                    continue;
                }
                $messagesAlready[$text] = true;
            }
            $this->addMessage($message);
        }

        return $this;
    }

    /**
     * Not Mage exception handling
     *
     * @param   Exception $exception
     * @param   string $alternativeText
     * @return  Core_Model_Session_Abstract
     */
    public function addException(Exception $exception, $alternativeText)
    {
        // log exception to exceptions log
        $message = sprintf('Exception message: %s%sTrace: %s',
            $exception->getMessage(),
            "\n",
            $exception->getTraceAsString());
        $file    = Virtual::getStoreConfig(self::XML_PATH_LOG_EXCEPTION_FILE);
        Virtual::log($message, Zend_Log::DEBUG, $file);

        $this->addMessage(Mage::getSingleton('core/message')->error($alternativeText));

        return $this;
    }


}//End of class