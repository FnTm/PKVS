<?php
/**
 * User: Janis
 * Date: 12.14.3
 * Time: 18:10
 */
 
class DalibniekiController  extends JP_Controller_Action{

    public function indexAction(){
        $userModel=new Model_Users();
$this->view->users=$userModel->fetchAll();
    }
}
