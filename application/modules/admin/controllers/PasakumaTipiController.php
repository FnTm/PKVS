<?php
/**
 * User: Janis
 * Date: 12.22.3
 * Time: 18:05
 */
 
class Admin_PasakumaTipiController  extends JP_Controller_Action{

    //TODO Display a list of all Pasakumi types
    public function indexAction(){
    $typeModel=new Model_Pasakumi_Type();
        var_dump($typeModel->getAll());
    }
    public function pievienotAction(){
        $this->view->form=$form=new Admin_Form_PasakumaTipi_Pievienot(array('1'=>'sdf'));
        $form->setAction("/admin/pasakuma-tipi/pievienot");
        if($this->getRequest()->isPost()){
            $data=$this->_getAllParams();
            if($form->isValid($data)){
            $typeModel=new Model_Pasakumi_Type();
                $typeModel->insert($form->getValidValues($data));
                $this->_redirect("/admin/pasakuma-tipi/");

            }
            else{
                $form->populate($data);
            }
        }
    }

}
