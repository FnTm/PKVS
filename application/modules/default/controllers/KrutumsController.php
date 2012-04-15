<?php
/**
 * User: Janis
 * Date: 12.15.4
 * Time: 08:55
 */
 
class KrutumsController extends JP_Controller_Action{

    public function indexAction(){

    $krutumsModel=new Model_Krutums();
$this->view->krutums=$krutumsModel->getAll();
    }
}
