<?php

class JP_View_Helper_FromElement
    extends Zend_View_Helper_FormElement
{

    protected $date_format="dateFormat: 'dd MM yy'";
    public function fromElement($name, $value = null, $attribs = null)
    {
    	 $html = '';
        if (is_array($attribs))
        {
        	//throw new Exception("stuff");
            $format = (isset($attribs['format'])) ? "dateFormat: '".$value['areanum']."'" : $this->date_format;
            $geonum = (isset($value['geonum'])) ? $value['geonum'] : '';
            //var_dump($attribs);
            $options=$attribs['options'];
            // $localnum = (isset($value['localnum'])) ? $value['localnum'] : '';
        }
        $areanum = $geonum = $localnum = '';
        /*
        if(!isset($script)){$script="";}
        $script.='$(function ()
{
var startbox="'.$rand.'_txtStartDate";
    var endbox="'.$rand.'_txtEndDate";

    //alert(startbox);
    $("#'.$rand.'_txtStartDate,#'.$rand.'_txtEndDate").datepicker(
    {
        showOn: "both",
        //beforeShow: customRange_'.$rand.',
        dateFormat: "dd M yy",
        firstDay: 1,
        changeFirstDay: false
    });

});

function customRange_'.$rand.'(input)
{

    var min = null;
    var startbox="'.$rand.'_txtStartDate";
    var endbox="'.$rand.'_txtEndDate";
        var dateMin = min;
        var dateMax = null;
        var dayRange = 6;  // Set this to the range of days you want to restrict to


        if (input.id == "startbox")
        {
            if ($("#"+endbox).datepicker("getDate") != null)
            {
                dateMax = $("#endbox").datepicker("getDate");
                dateMin = $("#endbox").datepicker("getDate");
                dateMin.setDate(dateMin.getDate() - dayRange);
                if (dateMin < min)
                {
                        dateMin = min;
                }
             }
             else
             {
                //dateMax = new Date(); //Set this to your absolute maximum date
             }
        }
        else if (input.id == "endbox")
        {
                dateMax = new Date(); //Set this to your absolute maximum date
                if ($("#startbox").datepicker("getDate") != null)
                {
                        dateMin = $("#startbox").datepicker("getDate");
                        var rangeMax = new Date(dateMin.getFullYear(), dateMin.getMonth(), dateMin.getDate() + dayRange);

                        if(rangeMax < dateMax)
                        {
                            dateMax = rangeMax;
                        }
                }
        }
    return {
                minDate: dateMin,
                maxDate: dateMax,
            };

}';
        //$this->view->headScript()->appendScript($script, $type = 'text/javascript', $attrs = array());
        //$this->view->headScript()->appendFile($this->view->baseUrl() . "/js/jquery-ui-1.7.2.custom.min.js");
        //$this->view->headScript()->appendFile($this->view->baseUrl() . "/js/daterangepicker.jQuery.js");
*/

        $helper = new Zend_View_Helper_FormText();
               // $helper = new ZendX_Jquery_View_Help();

        $helper->setView($this->view);

        if (is_array($value))
        {
            $areanum = (isset($value['areanum'])) ? $value['areanum'] : '';
            $geonum = (isset($value['geonum'])) ? $value['geonum'] : '';
           // $localnum = (isset($value['localnum'])) ? $value['localnum'] : '';
        }

        $html .= $helper->formText($name,$areanum,array('class'=>'dateInterval \''.$options.' \''));




        return $html;

    }

}

