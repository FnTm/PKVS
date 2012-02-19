<?php

class JP_View_Helper_FromtoElement
    extends Zend_View_Helper_FormElement
{
    protected $html = '';
    protected $date_format="dateFormat: 'dd MM yy'";
    public function fromtoElement($name, $value = null, $attribs = null)
    {
        if (is_array($attribs))
        {
            $format = (isset($attribs['format'])) ? "dateFormat: '".$value['areanum']."'" : $this->date_format;
            $geonum = (isset($value['geonum'])) ? $value['geonum'] : '';
           // $localnum = (isset($value['localnum'])) ? $value['localnum'] : '';
        }
        $areanum = $geonum = $localnum = '';

        //$this->view->headScript()->appendFile($this->view->baseUrl() . "/js/date.js");

        $helper = new Zend_View_Helper_FormText();
               // $helper = new ZendX_Jquery_View_Help();

        $helper->setView($this->view);

        if (is_array($value))
        {
            $areanum = (isset($value['areanum'])) ? $value['areanum'] : '';
            $geonum = (isset($value['geonum'])) ? $value['geonum'] : '';
           // $localnum = (isset($value['localnum'])) ? $value['localnum'] : '';
        }

        $this->html .= $helper->formText('txtStartDate',$areanum,array('class'=>'dateInterval firstField fromto'));
        $this->html .= $helper->formText('txtEndDate',$geonum,array('class'=>'dateInterval secondField fromto'));



        return $this->html;
    }

}

