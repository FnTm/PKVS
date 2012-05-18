<?php
/**
 * User: Janis
 * Date: 12.15.4
 * Time: 08:55
 */
 
class PunktiController extends JP_Controller_Action{

    public function indexAction(){

    $punktiModel=new Model_Punkti();
$this->view->punkti=$punktiModel->getAll();
    }
}
