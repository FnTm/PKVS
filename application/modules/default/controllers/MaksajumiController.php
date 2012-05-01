<?php

class MaksajumiController extends JP_Controller_Action
{

    public function init()
    {
        /* Initialize action controller here */
    }

    //TODO Parādīt katra kopējo bilanci
    //TODO iekrāsot negatīvo, pozitīvo, neitrālo bilanci
    public function indexAction()
    {
        $maksajumsModel=new Model_Maksajumi();
        $this->view->maksajumi=$maksajumsModel->getBilanceForAll();

    }


}

