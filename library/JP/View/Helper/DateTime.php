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
 * The rendering of the view element. Using the TinyMce view helper javascript
 * initiazion.
 * @author jurian
 *
 */
class JP_View_Helper_DateTime extends Zend_View_Helper_FormElement
{
    protected $_dateTime;

    public function DateTime ($name, $value = null, $attribs = null)
    {
        $info = $this->_getInfo($name, $value, $attribs);


        if (isset($attribs['editorOptions'])) {
            if ($attribs['editorOptions'] instanceof Zend_Config) {
                $attribs['editorOptions'] = $attribs['editorOptions']->toArray();
            }
            $this->view->tinyMce()->setOptions($attribs['editorOptions']);
            unset($attribs['editorOptions']);
        }
        $this->view->dateTimeHelper($name)->render();
        $helper = new Zend_View_Helper_FormText();
        $helper->setView($this->view);
        $value=($value!=null)?$value:"";
        $xhtml = $helper->formText($name,$value,$attribs);

        return $xhtml;
    }
}
