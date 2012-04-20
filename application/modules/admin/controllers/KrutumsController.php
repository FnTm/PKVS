<?php

class Admin_KrutumsController extends JP_Controller_Action
{

    public function init()
    {
        /* Initialize action controller here */
    }

    public function indexAction()
    {
        $krutumsModel=new Model_Krutums();
        $this->view->krutums=$krutumsModel->getAll();
    }
    public function pievienotAction(){

        $form=null;
        $userModel=new Model_Users();
        $form=new Admin_Form_Krutums_Add($userModel->getUsers());
        if($this->getRequest()->isPost()){
            $data=$this->_getAllParams();
            if($form->isValid($data)){
                $krutumsModel=new Model_Krutums();
                $data=$form->getValidValues($data);
                $data['krutumsEvent']=$krutumsModel::OTHER_EVENT;
                $data['krutumsDate']=date("Y-m-d H:i:s");
                $krutumsModel->insert($data);
                $this->_redirect("/admin/krutums");
            }
            else{
                $form->populate($data);
            }
        }


        $this->view->form=$form;

    }


}

