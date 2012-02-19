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
 * Sozfo View Helper FormTinyMce
 *
 * The rendering of the view element. Using the TinyMce view helper javascript
 * initiazion.
 * @author jurian
 *
 */
class JP_View_Helper_FormTinyMce extends Zend_View_Helper_FormTextarea
{
    protected $_tinyMce;

    public function FormTinyMce ($name, $value = null, $attribs = null)
    {
       // print_r($attribs);
    	 //print_r($attribs);
        $info = $this->_getInfo($name, $value, $attribs);
        extract($info); // name, value, attribs, options, listsep, disable

        $disabled = '';
        if ($disabled) {
            $disabled = ' disabled="disabled"';
        }

        if (empty($attribs['rows'])) {
            $attribs['rows'] = (int) $this->rows;
        }
        if (empty($attribs['cols'])) {
            $attribs['cols'] = (int) $this->cols;
        }
        $vars="";
        $namedVars="";
//unset($attribs['formFields']);
if(!empty($attribs['formFields'])){
    $vars.="<div class='ssp_mail_form_field_container ssp_mail_form_field_container_unnamedVars'><h2>Definēto elementu vērtības ar nosaukumiem</h2>";
$namedVars.="<div class='ssp_mail_form_field_container ssp_mail_form_field_container_namedVars'><h2>Īpašie elementi, ar nosaukumiem</h2>";

//print_r($attribs['formFields']);
	foreach($attribs['formFields'] as $key=> $field){
	    if(is_int($field['id'])){
	$vars.="<a class='ssp_mail_form_field_link' id='".$field['id']."' rel='".$field['title']."'>[".$field['title']."]</a>";
	    }
	    else{
	$namedVars.="<a class='ssp_mail_form_field_link' id='".$field['id']."' rel='".$field['title']."'>[".$field['title']."]</a>";

	    }
	    }
	//print_r($vars);
	$vars.="</div>";
$namedVars.="</div>";
	unset($attribs['formFields']);
}


        if (isset($attribs['editorOptions'])) {
            if ($attribs['editorOptions'] instanceof Zend_Config) {
                $attribs['editorOptions'] = $attribs['editorOptions']->toArray();
            }
            $this->view->tinyMce()->setOptions($attribs['editorOptions']);
            unset($attribs['editorOptions']);
        }
        $this->view->tinyMce()->render();
//$attribs=array('cols'=>'40','rows'=>'70');
        $xhtml = '<textarea name="' . $this->view->escape($name) . '"'
                . ' id="' . $this->view->escape($id) . '"'
                . $disabled
                . $this->_htmlAttribs($attribs) . '>'
                . $this->view->escape($value) . '</textarea>'.$vars.$namedVars;

        return $xhtml;
    }
}
