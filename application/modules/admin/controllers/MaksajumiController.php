<?php
/** Contains payment management controller
 * @author Janis Peisenieks
 * @package Maksajumi
 * @subpackage Admin
 */
/**
 * Controller for payment magament
 * @author Janis Peisenieks
 * @package Maksajumi
 * @subpackage Admin
 */
class Admin_MaksajumiController extends JP_Controller_Action
{

    /**
     * Displays a list of all payments, and each users balance
     */
    public function indexAction()
    {
        $maksajumsModel = new Model_Maksajumi();
        $this->view->bilance = $maksajumsModel->getBilanceForAll();
        $this->view->maksajumi = $maksajumsModel->getMaksajumi();

    }

    /**
     * Single payment adding action
     */
    public function pievienotMaksajumuAction()
    {
        //Retrieve user model
        $userModel = new Model_Users();
        //Pass the users to the form and the form to the view
        $this->view->form = $form = new Admin_Form_Maksajumi_Pievienot($userModel->getUsers(true, array('name asc')));
        //Check if the action is a post
        if ($this->getRequest()->isPost()) {
            //Get all of the parameters
            $data = $this->_getAllParams();
            // Check if the form is valid with the data provided
            if ($form->isValid($data)) {
                //If the data is valid, create a new payment
                $maksajumsModel = new Model_Maksajumi();
                $maksajumsModel->createMaksajums($form->getValidValues($data));
                //Set the success message and redirect the user
                $this->log("Maksājums veiksmīgi pievienots", self::SUCCESS);
                $this->_redirect("/admin/maksajumi");

            }
            else {
                //If form is not valid, populate the fields
                $form->populate($data);
            }
        }
    }

    /**
     * Payment editing action
     */
    public function redigetMaksajumuAction()
    {
        //Create the needed models
        $userModel = new Model_Users();
        $maksajumsModel = new Model_Maksajumi();
        // Try to get the payment Id from the URL
        $id = $this->_getParam("id");
        //Pass the form to the view with the user list
        $this->view->form = $form = new Admin_Form_Maksajumi_Pievienot($userModel->getUsers(true, array('name asc')));
        //Remove elements from the form, that are not needed for editing
        $form->removeElement("maksajumsUserId");
        $form->removeElement("maksajumsValue");
        //Only proceed if we have the Id
        if (!is_null($id)) {
            //Set the form action
            $form->setAction("/admin/maksajumi/rediget-maksajumu/id/" . $id);
            //Only proceed, if the request is a post
            if ($this->getRequest()->isPost()) {
                //Get all of the paramaters
                $data = $this->_getAllParams();
                //Check if the form is valid with these parameters
                if ($form->isValid($data)) {
                    //If the form is valid, edit the payment in the db
                    $maksajumsModel->editMaksajums($form->getValidValues($data), $id);
                    //Show the success message
                    $this->log("Maksājums veiksmīgi rediģēts", self::SUCCESS);
                    //Redirect the user to the index page
                    $this->_redirect("/admin/maksajumi");
                }
                else {
                    //If the input values are not valid, repopulate the form
                    $form->populate($data);
                }
            }
            else {
                //If the request is not a post, populate the fields from DB
                $form->populate($maksajumsModel->getMaksajums($id));
            }
        }
        else {
            //If no ID is set, show an error message and redirect to index page
            $this->log("Lūdzu norādiet maksājumu, ko rediģēt", self::ERROR);
            $this->_redirect("/admin/maksajumi");
        }
    }

    /**
     * Multiple payment creation function
     */
    public function pievienotMaksajumuVairakiemAction()
    {
        $userModel = new Model_Users();
        $maksajumsModel = new Model_Maksajumi();
        //Create the form, pass the user list to it, and send the form to the view
        $this->view->form = $form = new Admin_Form_Maksajumi_Pievienot_Vairakiem($userModel->getUsers(true, array('name asc')));
      //Check if the request is a post
        if ($this->getRequest()->isPost()) {
            //Get all of the parameters from the post
            $data = $this->_getAllParams();
            // check if the data is valid for the form
            if ($form->isValid($data)) {

                //Get the valid values form the form
                $data = $form->getValidValues($data);
                //Pass the valid values to the model, to split the payment
                $maksajumsModel->createMultiMaksajums($data);
                //Display success message
                $this->log("Maksājums veiksmīgi pievienots", self::SUCCESS);
                //Redirect to the index page
                $this->_redirect("/admin/maksajumi");

            }
            else {
                //Populate the form with the passed data, if the form is not valid
                $form->populate($data);
            }
        }
    }

    /**
     * Payment approval function
     */
    public function apstiprinatAction()
    {
        // Try to get the payment Id from the URL
        $id = $this->_getParam("id", null);
        //Only proceed if we have the Id
        if (!is_null($id)) {
            //Initialize the model, and try to change the state
            $maksajumsModel = new Model_Maksajumi();
            //If the number of impacted rows is >0, we have succeeded!
            if ($maksajumsModel->changeMaksajums($id, 1) > 0) {
                $this->log("Maksājums apstiprināts", self::SUCCESS);
            } else {
                $this->log("Lūdzu norādiet maksājumu, ko apstiprināt", self::ERROR);
            }
        }
        else {
            $this->log("Lūdzu norādiet maksājumu, ko apstiprināt", self::ERROR);

        }
        $this->_redirect("/admin/maksajumi");
    }

    /**
     * Payment un-approval function
     */
    public function noraiditAction()
    {
        // Try to get the payment Id from the URL
        $id = $this->_getParam("id", null);
        //Only proceed if we have the Id
        if (!is_null($id)) {
            //Initialize the model, and try to change the state
            $maksajumsModel = new Model_Maksajumi();
            //If the number of impacted rows is >0, we have succeeded!
            if ($maksajumsModel->changeMaksajums($id, 0) > 0) {
                $this->log("Maksājums noraidīts", self::SUCCESS);
            } else {
                $this->log("Lūdzu norādiet maksājumu, ko noraidīt", self::ERROR);
            }
        }
        else {
            $this->log("Lūdzu norādiet maksājumu, ko noraidīt", self::ERROR);

        }
        $this->_redirect("/admin/maksajumi");
    }

    /**
     * Payment deletion action
     */
    public function dzestAction()
    {
        // Try to get the payment Id from the URL
        $id = $this->_getParam("id", null);
        //Only proceed if we have the Id
        if (!is_null($id)) {
            //Initialize the model, and try to delete
            $maksajumsModel = new Model_Maksajumi();
            //If the number of impacted rows is >0, we have succeeded!
            if ($maksajumsModel->deleteMaksajums($id) > 0) {
                $this->log("Maksājums dzēsts", self::SUCCESS);
            } else {
                $this->log("Lūdzu norādiet maksājumu, ko dzēst", self::ERROR);
            }
        }
        else {
            //Show an error message, if no Id has been set
            $this->log("Lūdzu norādiet maksājumu, ko dzēst", self::ERROR);

        }
        //Redirect in either way.
        $this->_redirect("/admin/maksajumi");
    }


}

