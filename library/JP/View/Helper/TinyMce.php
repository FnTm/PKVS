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
 * Sozfo View Helper TinyMce
 *
 * Used to render javascript for the TinyMce textarea conversion.
 * @author jurian
 *
 */
class JP_View_Helper_TinyMce extends Zend_View_Helper_Abstract
{
    protected $_enabled = false;
    protected $_defaultScript;
    protected $_supported = array(
    'mode' => array('textareas', 'specific_textareas', 'exact', 'none'),
    'theme' => array('simple', 'advanced'),
    'format' => array('html', 'xhtml'), 'languages' => array('en'),
    'plugins' => array('style', 'layer', 'table', 'save', 'advhr', 'advimage',
    'advlink', 'emotions', 'iespell', 'insertdatetime', 'preview', 'media',
    'searchreplace', 'print', 'contextmenu', 'paste', 'directionality',
    'fullscreen', 'noneditable', 'visualchars', 'nonbreaking', 'xhtmlxtras',
    'imagemanager', 'filemanager', 'template'));
    protected $_config = array('mode' => 'textareas', 'theme' => 'simple',
    'element_format' => 'html');
    protected $_scriptPath;
    protected $_scriptFile;
    protected $_useCompressor = false;
    public function __construct ()
    {
        $this->_defaultScript = BASE_URL . '/js/tiny_mce/tiny_mce.js';
    }
    public function __set ($name, $value)
    {
        $method = 'set' . $name;
        if (! method_exists($this, $method)) {
            throw new Sozfo_View_Exception('Invalid tinyMce property');
        }
        $this->$method($value);
    }
    public function __get ($name)
    {
        $method = 'get' . $name;
        if (! method_exists($this, $method)) {
            throw new Sozfo_View_Exception('Invalid tinyMce property');
        }
        return $this->$method();
    }
    public function setOptions (array $options)
    {
        $methods = get_class_methods($this);
        foreach ($options as $key => $value) {
            $method = 'set' . ucfirst($key);
            if (in_array($method, $methods)) {
                $this->$method($value);
            } else {
                $this->_config[$key] = $value;
            }
        }
        return $this;
    }
    public function TinyMce ()
    {
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
    public function setCompressor ($switch)
    {
        $this->_useCompressor = (bool) $switch;
        return $this;
    }
    public function render ()
    {
        if (false === $this->_enabled) {
            $this->_renderScript();
            $this->_renderCompressor();
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
    protected function _renderCompressor ()
    {
        if (false === $this->_useCompressor) {
            return;
        }
        if (isset($this->_config['plugins']) &&
         is_array($this->_config['plugins'])) {
            $plugins = $this->_config['plugins'];
        } else {
            $plugins = $this->_supported['plugins'];
        }
        $script = 'tinyMCE_GZ.init({' . PHP_EOL . 'themes: "' .
         implode(',', $this->_supported['theme']) . '",' . PHP_EOL . 'plugins: "' .
         implode(',', $plugins) . '",' . PHP_EOL . 'languages: "' .
         implode(',', $this->_supported['languages']) . '",' . PHP_EOL .
         'disk_cache: true,' . PHP_EOL . 'debug: false' . PHP_EOL . '});';
        $this->view->headScript()->appendScript($script);
        return $this;
    }
    protected function _renderEditor ()
    {
        $script = 'tinyMCE.init({' . PHP_EOL;
        $script .= "file_browser_callback: 'openKCFinder'," . PHP_EOL;
        $params = array();
        foreach ($this->_config as $name => $value) {
            if (is_array($value)) {
                $value = implode(',', $value);
            }
            if (! is_bool($value)) {
                $value = '"' . $value . '"';
            }
            $params[] = $name . ': ' . $value;
        }
        $script .= implode(',' . PHP_EOL, $params) . PHP_EOL;
        $script .= '});' . PHP_EOL;
        $script .= "function openKCFinder(field_name, url, type, win) {
	    tinyMCE.activeEditor.windowManager.open({
	        file: '" .
         APP_DOMAIN . "/js/kc/browse.php?opener=tinymce&type=' + type,
	        title: 'Attēli',
	        width: 700,
	        height: 500,
	        resizable: \"yes\",
	        inline: true,
	        close_previous: \"no\",
	        popup_css: false
	    }, {
	        window: win,
	        input: field_name
	    });
	    return false;
	}";
        $this->view->headScript()->appendScript($script);
        return $this;
    }
}