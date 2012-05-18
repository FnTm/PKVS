<?php

class Admin_PunktiController extends JP_Controller_Action
{

    public function init()
    {
        /* Initialize action controller here */
    }

    public function indexAction()
    {
        $punktiModel=new Model_Punkti();
        $this->view->punkti=$punktiModel->getAll();
    }
    public function pievienotAction(){

        $form=null;
        $userModel=new Model_Users();
        $form=new Admin_Form_Punkti_Add($userModel->getUsers());
        if($this->getRequest()->isPost()){
            $data=$this->_getAllParams();
            if($form->isValid($data)){
                $punktiModel=new Model_Punkti();
                $data=$form->getValidValues($data);
                $data['punktiEvent']=$punktiModel::OTHER_EVENT;
                $data['punktiDate']=date("Y-m-d H:i:s");
                $punktiModel->insert($data);
                $this->_redirect("/admin/punkti");
            }
            else{
                $form->populate($data);
            }
        }


        $this->view->form=$form;

    }


}

