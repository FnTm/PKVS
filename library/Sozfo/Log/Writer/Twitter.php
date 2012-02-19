<?php
/**
 * Zend Framework
 *
 * LICENSE
 *
 * This source file is subject to the new BSD license that is bundled
 * with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * http://framework.zend.com/license/new-bsd
 * If you did not receive a copy of the license and are unable to
 * obtain it through the world-wide-web, please send an email
 * to license@zend.com so we can send you a copy immediately.
 *
 * @category  Sozfo
 * @package   Sozfo_Form
 * @copyright Copyright (c) 2009 Soflomo V.O.F. (http://www.soflomo.com)
 * @license   http://framework.zend.com/license/new-bsd     New BSD License
 */

/**
 * Sozfo Log Writer Twitter
 *
 * Used to write log messages as updates for a Twitter account.
 * @author jurian
 *
 */
class Sozfo_Log_Writer_Twitter extends Zend_Log_Writer_Abstract
{
    protected $_username;
    protected $_password;
    protected $_twitter;

    public function __construct ($username, $password)
    {
        $this->_username = $username;
        $this->_password = $password;

        $this->_formatter = new Zend_Log_Formatter_Simple();
    }

    protected function _getTwitter ()
    {
        if (null === $this->_twitter) {
            $this->_twitter = new Zend_Service_Twitter($this->_username, $this->_password);
            $response = $this->_twitter->account->verifyCredentials();
            if ($response->isError()) {
                throw new Zend_Log_Exception('Provided credentials for Twitter log writer are wrong');
            }
        }
        return $this->_twitter;
    }

    public function _write ($event)
    {
        $line = $this->_formatter->format($event);
        $this->_getTwitter()->status->update($line);
    }
}