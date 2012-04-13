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
 * JP View Helper DateTime
 *
 * Used to render javascript for the jQuery input field conversion.
 * @author Janis Peisenieks
 *
 */
class JP_View_Helper_DateTimeHelper extends Zend_View_Helper_Abstract
{
    protected $_enabled = false;
    protected $_defaultScript;
    protected $_scriptPath;
    protected $_scriptFile;
    protected $_id;
    public function __construct ()
    {

        $this->_defaultScript = BASE_URL . '/js/jquery-ui-timepicker-addon.js';
    }

    public function DateTimeHelper ($id=null)
    {$this->_id=$id;
        return $this;
    }
    public function setScriptPath ($path)
    {
        $this->_scriptPath = rtrim($path, '/');
        return $this;
    }
    public function setScriptFile ($file)
    {
        $this->_scriptFile = (string) $file;
    }

    public function render ()
    {
        if (false === $this->_enabled) {
            $this->_renderScript();
            $this->_renderEditor();
        }
        $this->_enabled = true;
    }
    protected function _renderScript ()
    {
        if (null === $this->_scriptFile) {
            $script = $this->_defaultScript;
        } else {
            $script = $this->_scriptPath . '/' . $this->_scriptFile;
        }
        $this->view->headScript()->appendFile($this->view->baseUrl() . $script);
        return $this;
    }
    protected function _renderEditor(){
        if($this->_id!==null){
        $script="$(function(){ $('#".$this->_id."').datetimepicker({timeFormat: 'hh:mm:ss', dateFormat:'yy-mm-dd'});});";
        $this->view->headScript()->appendScript($script);
        }
    }
}