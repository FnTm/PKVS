<?php
/**
 * @category   JP
 */

/**
 * @see Zend_Session
 */
require_once 'Zend/Session.php';

/**
 * @see Zend_View_Helper_Abstract
 */
require_once 'Zend/View/Helper/Abstract.php';

/**
 * Priority Messenger - implement session-based messages at the view level
 *
 * @uses       Zend_View_Helper_Abstract
 */
class JP_View_Helper_PriorityMessenger extends Zend_View_Helper_Abstract
{

    /**
     * $_session - Zend_Session storage object
     *
     * @var Zend_Session
     */
    static protected $_session = null;

    /**
     * Add a message to the collection of priority messages or retrieve the
     * priority messages. If $messages is left null then all the messages are
     * returned, unless $severity is set (as a string or array), causing just
     * the indicated messages to be returned; in either case, the returned
     * messages are cleared from the session cache. If a message or messages
     * are provided, then store them in the indicated severity; $messages may
     * be an array of string all to be stored in the indicated severity OR it
     * may be an associative array of severity-to-message pairs. In any case,
     * if $severity is not set but message is, then 'info' is the assumed
     * default.
     *
     * @param  string|array|null $message
     * @param  string|array|null $severity
     * @return Zend_Session_Namespace
     */
    function priorityMessenger($message = null, $severity = null)
    {
        //echo "adding";
        $session = $this->_getSession();
        // if page messages has not been set o the session, then initialize it.
        if (!isset($session->page_messages)) {
            $this->_resetMessageArray();
        }

        if (is_null($message)) {
            // return all the messages or just those indicated by $severity
            return $this->_resetMessageArray($severity);
        } else {
            //add message to the collection

            // default severity to 'info'
            if (is_null($severity)) {
                $severity = 'info';
            }

            // if severity is an array, then assume this was done in error and use
            // only the first value of the array as the severity.
            if (is_array($severity)) {
                reset($severity);
                $severity = $severity[key($severity)];
            }

            // if this is the first message of this severity, then initialize
            // the message array for that severity.
            if (!isset($session->page_messages[$severity])) {
                $session->page_messages[$severity] = array();
            }

            // if message is an array then assume it is a group of messages to be
            // added with the given severity. However, it is possible to pass in an
            // array of arrays of severity-to-messageArray sets, going as deep as
            // one might wish, so long as the deepest level provides the correct
            // severity-to-message relationship.
            if (is_array($message)) {
                foreach ($message as $sev => $mes) {
                    $this->priorityMessenger($mes, $sev);
                }
            } else {
                $session->page_messages[$severity][] = $message;
            }
        }
    }

    /**
     * Reset the session object's collection of messages. If severity provided,
     * then return that severity and clear only that severity. If an array of
     * severities are provided, then return an array in the form of
     * $severity=>$messages.
     *
     * @param  string|array $severity
     * @return void
     */
    private function _resetMessageArray($severity = null)
    {
        $messages = array();
        if (is_null($severity)) {
            $messages = $this->_getSession()->page_messages;
            $this->_getSession()->page_messages = array();
        } elseif (is_string($severity) && isset($this->_getSession()->page_messages[$severity])) {
            $messages = $this->_getSession()->page_messages[$severity];
            unset($this->_getSession()->page_messages[$severity]);
        } elseif (is_array($severity)) {
            foreach ($severity as $sev) {
                $messages[$sev] = $this->_resetMessageArray($sev);
            }
        }
        return $messages;
    }

    /**
     * Return the static session object, initiating it if needs be.
     *
     * @return Zend_Session_Namespace
     */
    private function _getSession()
    {
        if (!self::$_session instanceof Zend_Session_Namespace) {
            $className = get_class($this);
            $className = (strpos($className, '_') !== false) ? ltrim(strrchr($className, '_'), '_') : $className;
            self::$_session = new Zend_Session_Namespace($className);
        }
        return self::$_session;
    }
}