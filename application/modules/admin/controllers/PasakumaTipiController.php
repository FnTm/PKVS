<?php
/**
 * Contains the controller for the event types.
 * @author Janis Peisenieks
 * @package Pasakumi
 * @subpackage Admin
 */

/**
 * Controller for the event types. Each event can have one and only one type
 * @author Janis Peisenieks
 * @package Pasakumi
 * @subpackage Admin
 */
class Admin_PasakumaTipiController extends JP_Controller_Action
{

    public function indexAction()
    {
        $typeModel = new Model_Pasakumi_Type();
        //Get the complete list of event types and pass that to the view
        $this->view->types = $typeModel->getAll();
    }

    public function pievienotAction()
    {
        $typeModel = new Model_Pasakumi_Type();
        //Add the complete list of event types to the form
        $this->view->form = $form = new Admin_Form_PasakumaTipi_Pievienot($typeModel->getAll());
        //Set the form action attribute
        $form->setAction("/admin/pasakuma-tipi/pievienot");
        //Check if the type of request is post, if so, continue
        if ($this->getRequest()->isPost()) {
            //Get all of the parameters passed
            $data = $this->_getAllParams();
            //Check if the form is valid, with the provided data
            if ($form->isValid($data)) {
                //If the form is valid, insert it into the db, and display the success message
                $typeModel->insert($form->getValidValues($data));
                $this->log("Pasākuma tips veiksmīgi pievienots", self::SUCCESS);
                $this->_redirect("/admin/pasakuma-tipi");

            }
            else {
                $form->populate($data);
            }
        }
    }


    public function redigetAction()
    {
        //Try to retrieve the events Id from the URL
        $id = $this->_getParam("id");
        //if the Id is set
        if (!is_null($id)) {
            $typeModel = new Model_Pasakumi_Type();
            $this->view->form = $form = new Admin_Form_PasakumaTipi_Pievienot($typeModel->getAll());
            $form->setAction("/admin/pasakuma-tipi/pievienot");
            if ($this->getRequest()->isPost()) {
                $data = $this->_getAllParams();
                if ($form->isValid($data)) {

                    $typeModel->updateType($form->getValidValues($data), $id);
                    $this->log("Pasākuma tips veiksmīgi pievienots", self::SUCCESS);
                    $this->_redirect("/admin/pasakuma-tipi");

                }
                else {
                    $form->populate($data);
                }
            }
        }
    }

    public function dzestAction()
    {
        //Try to get the Id param from the URL
        $id = $this->_getParam("id");
        //if id is set, continue
        if (!is_null($id)) {
            $typeModel = new Model_Pasakumi_Type();
            //Try to delete the type specified
            $count = $typeModel->deleteType($id);
            //Check if anything was deleted
            if ($count > 0) {
                $this->log("Pasākuma tips veiksmīgi dzēsts", self::SUCCESS);
            }
            else {
                $this->log("Pasākuma tips, ko mēģinājāt dzēst, neeksistē", self::ERROR);
            }
        }
        else {
            $this->log("Lūdzu izvēlieties pasākuma tipu, ko dzēst", self::ERROR);
        }
        //Redirect to the index page
        $this->_redirect("/admin/pasakuma-tipi");
    }

    /**
     * TODO Pievienot visu punktu tipu inicializāciju
     */
    public function punktiAction()
    {
        $id = $this->_getParam("id", null);
        $this->view->type = null;

        $form = null;
        if (!is_null($id)) {
            $apmekletibaPunktiModel = new Model_Apmekletiba_Punkti();
            $typeModel = new Model_Pasakumi_Type();
            $this->view->type = $typeModel->getType($id);
            $apmTipsModel = new Model_Apmekletiba_Tips();
            $form = new Admin_Form_PasakumaTipi_Punkti(array(Admin_Form_PasakumaTipi_Punkti::TYPE_NAMES => $apmTipsModel->getAll()->toArray()));
            $form->setAction('/admin/pasakuma-tipi/punkti/id/' . $id);
            if ($this->getRequest()->isPost()) {
                $data = $this->_getAllParams();
                if ($form->isValid($data)) {
                    $this->log("Datu saglabāšana bīja veiksmīga!", self::SUCCESS);
                    $apmekletibaPunktiModel->insertPunktiValues($id, $form->getValidValues($data), Admin_Form_PasakumaTipi_Punkti::KEY_NAME);
                    $this->_redirect("/admin/pasakuma-tipi/");
                }
                else {
                    $this->log("Lūdzu aizpildiet pareizi!", self::ERROR);
                    $form->populate($data);
                }
            }
            else {
                $form->populate($apmekletibaPunktiModel->getPunktiValues($id, Admin_Form_PasakumaTipi_Punkti::KEY_NAME));
            }
        }
        $this->view->form = $form;

    }

}
