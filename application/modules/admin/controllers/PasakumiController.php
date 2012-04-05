<?php
/**
 * User: Janis
 * Date: 12.19.3
 * Time: 18:39
 */
 
class Admin_PasakumiController  extends JP_Controller_Action{

    public function indexAction(){

    }
    public function pievienotAction(){
        $pasakumaTypes=new Model_Pasakumi_Type();
        $this->view->form=$form=new Admin_Form_Pasakumi_Pievienot($pasakumaTypes->getAll());
        $form->setAction("/admin/pasakumi/pievienot");
        if($this->getRequest()->isPost()){
            $data=$this->_getAllParams();

            if($form->isValid($data)){
            $typeModel=new Model_Pasakumi();
                $typeModel->insert($form->getValidValues($data));
                $this->_redirect("/admin/pasakumi/");
            }
            else{
                $form->populate($data);
            }
        }
        
    }
    
}
