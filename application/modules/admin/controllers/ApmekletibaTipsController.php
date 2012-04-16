<?php
/**
 * User: Janis
 * Date: 12.15.4
 * Time: 09:49
 */
 
class Admin_ApmekletibaTipsController extends JP_Controller_Action{
    public function indexAction(){
        $typeModel=new Model_Apmekletiba_Tips();

        $this->view->types=$typeModel->getAll();
    }
    public function pievienotAction(){
        $apmTipsModel=new Model_Apmekletiba_Tips();
        $this->view->form=$form=new Admin_Form_ApmekletibaTips_Pievienot();
        $form->setAction("/admin/apmekletiba-tips/pievienot");
        if($this->getRequest()->isPost()){
            $data=$this->_getAllParams();
            if($form->isValid($data)){

                $apmTipsModel->insert($form->getValidValues($data));
                $this->_redirect("/admin/apmekletiba-tips/");

            }
            else{
                $form->populate($data);
            }
        }
    }

}
