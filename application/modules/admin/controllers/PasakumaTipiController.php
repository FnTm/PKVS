<?php
/**
 * User: Janis
 * Date: 12.22.3
 * Time: 18:05
 *
 */

/**
 * Pasākuma tipu kontrolieris. Katram pasākumam ir tieši viens tips.
 * @package Admin
 * @subpackage Pasakumi
 */
class Admin_PasakumaTipiController  extends JP_Controller_Action{

    //TODO Display a list of all Pasakumi types
    public function indexAction(){
    $typeModel=new Model_Pasakumi_Type();

    $this->view->types=$typeModel->getAll();
    }
    public function pievienotAction(){
        $typeModel=new Model_Pasakumi_Type();
        $this->view->form=$form=new Admin_Form_PasakumaTipi_Pievienot($typeModel->getAll());
        $form->setAction("/admin/pasakuma-tipi/pievienot");
        if($this->getRequest()->isPost()){
            $data=$this->_getAllParams();
            if($form->isValid($data)){

                $typeModel->insert($form->getValidValues($data));
                $this->_redirect("/admin/pasakuma-tipi/");

            }
            else{
                $form->populate($data);
            }
        }
    }

    /**
     * Pasākumu tipa rediģēšanas funkcija
     * @return void

     *
     * @internal param $id int Rediģējamā tipi identifikators
     * TODO Ieviest
     */
    public function redigetAction(){

    }

}
