<?php

class JP_View_Helper_FreemaskElement
    extends Zend_View_Helper_FormElement
{
    protected $html = '';
    protected $date_format="dateFormat: 'dd MM yy'";
    public function freemaskElement($name, $value = null, $attribs = null)
    {
        if (is_array($attribs))
        {
            $format = (isset($attribs['format'])) ? $attribs['format'] : "999/99";
            $geonum = (isset($value['geonum'])) ? $value['geonum'] : '';
           // $localnum = (isset($value['localnum'])) ? $value['localnum'] : '';
        }
        $areanum = $geonum = $localnum = '';
        $script='$(function ()
{

    $("#'.$name.'").mask("'.$format.'");
    

});';
        $this->view->headScript()->appendScript($script, $type = 'text/javascript', $attrs = array());
        $this->view->headScript()->appendFile($this->view->baseUrl() . "/js/jquery-ui-1.7.2.custom.min.js");
        $this->view->headScript()->appendFile($this->view->baseUrl() . "/js/jquery.maskedinput-1.2.2.min.js");
        
        
        $helper = new Zend_View_Helper_FormText();
               // $helper = new ZendX_Jquery_View_Help();
        
        $helper->setView($this->view);

        if (is_array($value))
        {
            $areanum = (isset($value['areanum'])) ? $value['areanum'] : '';
            $geonum = (isset($value['geonum'])) ? $value['geonum'] : '';
           // $localnum = (isset($value['localnum'])) ? $value['localnum'] : '';
        }

        $this->html .= $helper->formText($name,$areanum,array('class'=>'calendar'));
       
        

        return $this->html;
    }

}

