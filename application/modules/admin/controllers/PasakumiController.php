<?php
/** Contains admin events controller
 * @author Janis Peisenieks
 * @package Pasakumi
 * @subpackage Admin
 */
/**
 * A controller for operating with events
 * @author Janis Peisenieks
 * @package Pasakumi
 * @subpackage Admin
 */

class Admin_PasakumiController extends JP_Controller_Action
{

    /**
     * The default viewing action
     */
    public function indexAction()
    {
        $pasakumiModel = new Model_Pasakumi();
        //Get a list of events and pass them to the view
        $this->view->pasakumi = $pasakumiModel->getAllPasakumi();

    }

    /**
     * Adding action
     */
    public function pievienotAction()
    {
        //Initialize the event type model
        $pasakumaTypes = new Model_Pasakumi_Type();
        //Pass the types to the form
        $this->view->form = $form = new Admin_Form_Pasakumi_Pievienot($pasakumaTypes->getAll());
        $form->setAction("/admin/pasakumi/pievienot");
        //Check if this is a post
        if ($this->getRequest()->isPost()) {
            //If so, get all of the data
            $data = $this->_getAllParams();
            //Check if they are valid
            if ($form->isValid($data)) {
                $typeModel = new Model_Pasakumi();
                //And create a new event and redirect
                $typeModel->insert($form->getValidValues($data));
                $this->_redirect("/admin/pasakumi");
            }
            else {
                //If data not valid, repopulate the form
                $form->populate($data);
            }
        }

    }

    /**
     * Editing action
     */
    public function redigetAction()
    {

        $id = $this->_getParam("id");
        if (!is_null($id)) {
            $pasakumiModel = new Model_Pasakumi();
            //Initialize the event type model
            $pasakumaTypes = new Model_Pasakumi_Type();
            //Pass the types to the form
            $this->view->form = $form = new Admin_Form_Pasakumi_Pievienot($pasakumaTypes->getAll());
            $form->setAction("/admin/pasakumi/rediget/id/" . $id);
            //Check if this is a post
            if ($this->getRequest()->isPost()) {
                //If so, get all of the data
                $data = $this->_getAllParams();
                //Check if they are valid
                if ($form->isValid($data)) {

                    //Update event and redirect
                    $pasakumiModel->updatePasakums($id,$form->getValidValues($data));
                    $this->log("Pasākums veiksmīgi rediģēts", self::SUCCESS);
                    $this->_redirect("/admin/pasakumi");
                }
                else {
                    //If data not valid, repopulate the form
                    $form->populate($data);
                }
            }
            else {
                $data = $pasakumiModel->getPasakums($id);
                if (!is_null($data)) {
                    $data = $data->toArray();
                }
                $form->populate($data);
            }
        }
        else {
            $this->log("Lūdzu izvēlieties pasākumu, ko rediģēt", self::ERROR);
            $this->_redirect("/admin/pasakumi");
        }

    }

    /**
     * Deleting action
     */
    public function dzestAction()
    {

        $id = $this->_getParam("id");
        //Check if the Id parameter in the URL is set
        if (!is_null($id)) {
            //If it is, we delete the event, and redirect the user
            $pasakumiModel = new Model_Pasakumi();
            if ($pasakumiModel->deletePasakums($id) > 0) {
                $this->log("Pasākums veiksmīgi rediģēts", self::SUCCESS);
                $this->_redirect("/admin/pasakumi");
            }

        }
        //If any of those checks fail, we display an error message and redirect accordingly
        $this->log("Lūdzu izvēlieties pasākumu, ko dzēst", self::ERROR);
        $this->_redirect("/admin/pasakumi");

    }


}
