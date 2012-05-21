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

    /**
     * Index function, that displays the full list of event types
     */
    public function indexAction()
    {
        $typeModel = new Model_Pasakumi_Type();
        //Get the complete list of event types and pass that to the view
        $this->view->types = $typeModel->getAll();
    }

    /**
     * Event type adding function
     */
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


    /**
     * Event type editing function
     */
    public function redigetAction()
    {
        //Try to retrieve the events Id from the URL
        $id = $this->_getParam("id");
        //if the Id is set
        if (!is_null($id)) {
            $typeModel = new Model_Pasakumi_Type();
            //Retrieve the full set of event types, and pass them to the form, excluding this event
            $this->view->form = $form = new Admin_Form_PasakumaTipi_Pievienot($typeModel->getAll($id));
            //Set the forms action param.
            $form->setAction("/admin/pasakuma-tipi/rediget/id/" . $id);
            //Check if the request is a post
            if ($this->getRequest()->isPost()) {
                //Retreive all of the parameters and check, if the form is valid with them
                $data = $this->_getAllParams();
                if ($form->isValid($data)) {
                    //Try to update the event type
                    $typeModel->updateType($form->getValidValues($data), $id);
                    //Show the success message and redirect to the index page
                    $this->log("Pasākuma tips veiksmīgi rediģēts", self::SUCCESS);
                    $this->_redirect("/admin/pasakuma-tipi");

                }
                else {
                    //In case of error, we re-populate the form
                    $form->populate($data);
                }
            }
            else {
                //If no post has yet occured, we retrieve the type defined by the id, and populate the form with it
                $type = $typeModel->getType($id);
                if (!is_null($type)) {
                    $type = $type->toArray();
                }
                $form->populate($type);
            }
        }
        else {
            //if no Id is set, display an error message and redirect to the index page
            $this->log("Lūdzu norādiet pasākuma tipu, ko rediģēt", self::ERROR);
            $this->_redirect("/admin/pasakuma-tipi");
        }
    }

    /**
     * Action to delete an event
     */
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
     * Point editing action
     *
     * TODO Pievienot visu punktu tipu inicializāciju
     */
    public function punktiAction()
    {
        //Try to get the Id param from the URL
        $id = $this->_getParam("id", null);
        $this->view->type = null;
        //if id is set, continue
        $form = null;
        if (!is_null($id)) {
            //Initialize the needed modesl
            $apmekletibaPunktiModel = new Model_Apmekletiba_Punkti();
            $typeModel = new Model_Pasakumi_Type();
            $apmTipsModel = new Model_Apmekletiba_Tips();
            //Get the event type
            $this->view->type = $typeModel->getType($id);
            //Set up the point editing form
            $form = new Admin_Form_PasakumaTipi_Punkti(array(Admin_Form_PasakumaTipi_Punkti::TYPE_NAMES => $apmTipsModel->getAll()->toArray()));
            $form->setAction('/admin/pasakuma-tipi/punkti/id/' . $id);
            //Check if request is a post
            if ($this->getRequest()->isPost()) {
                //Retrieve all post data
                $data = $this->_getAllParams();
                //Check if the form is valid with the provided data
                if ($form->isValid($data)) {

                    //Insert the point values in the db and redirect with success message
                    $apmekletibaPunktiModel->insertPunktiValues($id, $form->getValidValues($data), Admin_Form_PasakumaTipi_Punkti::KEY_NAME);
                    $this->log("Datu saglabāšana bīja veiksmīga!", self::SUCCESS);
                    $this->_redirect("/admin/pasakuma-tipi/");
                }
                else {
                    //Display an error message if not completed right
                    $this->log("Lūdzu aizpildiet pareizi!", self::ERROR);
                    $form->populate($data);
                }
            }
            else {
                //If no post yet, populate with values from db
                $form->populate($apmekletibaPunktiModel->getPunktiValues($id, Admin_Form_PasakumaTipi_Punkti::KEY_NAME));
            }
        }
        //Pass the form to the view
        $this->view->form = $form;

    }

}
