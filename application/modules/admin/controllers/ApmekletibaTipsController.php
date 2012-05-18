<?php
/**
 * @author Janis Peisenieks
 * @package ApamekletibaTips
 * @subpackage Admin
 */
 
class Admin_ApmekletibaTipsController extends JP_Controller_Action{
    /**
     * Displays the default action for the controller
     */
    public function indexAction(){
        $typeModel=new Model_Apmekletiba_Tips();
        // Passes the Types to the view
        $this->view->types=$typeModel->getAll();
    }
    /**
     * Type adding action
     */
    public function pievienotAction(){
        $apmTipsModel=new Model_Apmekletiba_Tips();
        //Passes the form to the view
        $this->view->form=$form=new Admin_Form_ApmekletibaTips_Pievienot();
        //Sets the form action
        $form->setAction("/admin/apmekletiba-tips/pievienot");
        if($this->getRequest()->isPost()){

            $data=$this->_getAllParams();
            //Checks if the form has all the parameters
            if($form->isValid($data)){
                //Inserts the valid form values in the
                $apmTipsModel->insert($form->getValidValues($data));
                $this->_redirect("/admin/apmekletiba-tips/");

            }
            else{
                //Populates the form with the data, if the first posting wasn't valid
                $form->populate($data);
            }
        }
    }
    /**
     * Type editing action
     *
     * @todo implement this function
     */
    public function redigetAction(){


    }

}
