﻿<?php

class Admin_IndexController extends JP_Controller_Action
{

    public function init()
    {
        /* Initialize action controller here */
    }

    public function indexAction()
    {
        $pasakumiModel=new Model_Pasakumi();
        $this->view->pasakumi=$pasakumiModel->getClosestPasakumi();


    }


}

