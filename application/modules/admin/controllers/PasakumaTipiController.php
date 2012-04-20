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
class Admin_PasakumaTipiController extends JP_Controller_Action
{

    //TODO Display a list of all Pasakumi types
    public function indexAction()
    {
        $typeModel = new Model_Pasakumi_Type();

        $this->view->types = $typeModel->getAll();
    }

    public function pievienotAction()
    {
        $typeModel = new Model_Pasakumi_Type();
        $this->view->form = $form = new Admin_Form_PasakumaTipi_Pievienot($typeModel->getAll());
        $form->setAction("/admin/pasakuma-tipi/pievienot");
        if ($this->getRequest()->isPost()) {
            $data = $this->_getAllParams();
            if ($form->isValid($data)) {

                $typeModel->insert($form->getValidValues($data));
                $this->_redirect("/admin/pasakuma-tipi/");

            }
            else {
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
    public function redigetAction()
    {

    }

    public function dzestAction()
    {
        $id = $this->_getParam("id");
        $typeModel = new Model_Pasakumi_Type();
        $typeModel->deleteType($id);
        $this->_redirect("/admin/pasakuma-tipi/");
    }

    public function punktiAction()
    {
        $id = $this->_getParam("id", null);
        $this->view->type = null;

        $form = null;
        if (!is_null($id)) {
            $apmekletibaKrutumsModel=new Model_Apmekletiba_Krutums();
            $typeModel = new Model_Pasakumi_Type();
            $this->view->type = $typeModel->getType($id);
            $apmTipsModel = new Model_Apmekletiba_Tips();
            $form = new Admin_Form_PasakumaTipi_Punkti(array(Admin_Form_PasakumaTipi_Punkti::TYPE_NAMES => $apmTipsModel->getAll()->toArray()));
            $form->setAction('/admin/pasakuma-tipi/punkti/id/'.$id);
            if($this->getRequest()->isPost()){
                $data=$this->_getAllParams();
                if($form->isValid($data)){
                    $apmekletibaKrutumsModel->insertKrutumsValues($id,$form->getValidValues($data),Admin_Form_PasakumaTipi_Punkti::KEY_NAME);
                }
                else{
                    $form->populate($data);
                }
            }
            else{
                $form->populate($apmekletibaKrutumsModel->getKrutumsValues($id,Admin_Form_PasakumaTipi_Punkti::KEY_NAME));
            }
        }
        $this->view->form = $form;

    }

}
