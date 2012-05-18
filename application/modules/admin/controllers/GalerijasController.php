<?php
/**
 * A controller for gallery magament
 * @author Janis Peisenieks
 * @package Galerijas
 * @subpackage Admin
 */
class Admin_GalerijasController extends JP_Controller_Action
{
    /**
     * @var string Name of the db table to use
     */
    public $_name = "galleries";

    /**
     * Displays a list of all galleries
     */
    public function indexAction()
    {

        $galleryModel = new Model_Galerijas();
        $this->view->galerijas = $galleryModel->getAll();


    }

    /**
     * Gallery adding function.
     */
    public function pievienotAction()
    {
        $galleryModel = new Model_Galerijas();
        //We create a new form, and pass the reference to the view
        $this->view->form = $form = new Admin_Form_Galerijas_Pievienot();
        if ($this->getRequest()->isPost()) {
            $data = $this->_getAllParams();
            if ($form->isValid($data)) {
                $galleryModel->createGallery($form->getValidValues($data));
                //Adding the success message
                $this->log("Galerija veiksmīgi izveidota", self::SUCCESS);
                //Reditecting to the index action
                $this->_redirect("/admin/galerijas");
            }
            else {
                //Repopulate the form, if the input data is not valid
                $form->populate($data);
            }
        }
    }

    /**
     * Gallery editing action. id parameter in the URL has to be set
     */
    public function redigetAction()
    {
        $id = $this->_getParam("id");
        //Check if the id param in the URL has been set
        if (!is_null($id)) {
            $galleryModel = new Model_Galerijas();

            //We create a new form, and pass the reference to the view
            $this->view->form = $form = new Admin_Form_Galerijas_Pievienot();
            $form->setAction("/admin/galerijas/rediget/id/" . $id);
            if ($this->getRequest()->isPost()) {
                $data = $this->_getAllParams();
                if ($form->isValid($data)) {
                    $galleryModel->updateGallery($id, $form->getValidValues($data));
                    //Adding the success message
                    $this->log("Galerija veiksmīgi rediģēta", self::SUCCESS);
                    //Reditecting to the index action
                    $this->_redirect("/admin/galerijas");
                }
                else {
                    //Repopulate the form, if the input data is not valid
                    $form->populate($data);
                }
            }
            else {
                //Populate the form from the db, if this is not a post
                $form->populate($galleryModel->getGallery($id));
            }
        }
        else {
            //Showing error message and redirecting to the list page
            $this->log("Nav norādīts rediģējamās galerijas identifikators", self::ERROR);
            $this->_redirect("/admin/galerijas");
        }
    }

    /**
     *Gallery deletion function. The gallery Id must be set in an URL parameter
     */
    public function dzestAction()
    {
        //Getting the Id from the URL
        $id = $this->_getParam("id");
        //Checking if an Id has been set
        if (!is_null($id)) {
            $galleryModel = new Model_Galerijas();
            //Deleting the gallery
            $galleryModel->deleteGallery($id);
            //Adding the success message
            $this->log("Galerija veiksmīgi dzēst", self::SUCCESS);
            //Reditecting to the index action
            $this->_redirect("/admin/galerijas");

        } else {
            $this->log("Nav norādīts dzēšamās galerijas identifikators", self::ERROR);
            $this->_redirect("/admin/galerijas");
        }

    }


}

