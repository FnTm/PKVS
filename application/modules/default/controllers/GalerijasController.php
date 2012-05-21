<?php
/** Contains gallery controller
 * @author Janis Peisenieks
 * @package Galerijas
 * @subpackage Default
 */
/**
 * A controller for viewing galleries
 * @author Janis Peisenieks
 * @package Galerijas
 * @subpackage Default
 */
class GalerijasController extends JP_Controller_Action
{


    /**
     * Function that displays all of the available galleries
     */
    public function indexAction()
    {
        //Get the gallery model and retrieve every gallery with at least 1 picture in it
        $galleryModel = new Model_Galerijas();
        $this->view->galleries = $galleryModel->getAllWithOneImage();
        /*
        $draugiem = Zend_Registry::get("draugiemOptions");

$api=new Draugiem_Api($draugiem->appId, $draugiem->secret,'b293d09ab77bea9608e16a7dcd7095b8');
       var_dump($api->addNotification("Nenokārtoti maksājumi!"));
        var_dump($api);
        */
    }

    /**
     * Function that displays a particular gallery
     */
    public function skatitAction()
    {
        //Check if id param in URL is set
        $id = $this->_getParam("id");
        if (!is_null($id)) {
            //Instantiate gallery model and pass the gallery info to the view
            $galleryModel=new Model_Galerijas();
            $this->view->gallery=$galleryModel->getGallery($id);
            //Enable jQuery
            $jquery = $this->view->jQuery();

            $jquery->uiEnable();

            //Add fancybox scripts and stylesheets
            $jquery->addJavascriptFile(
                $this->view->baseUrl() . "/js/jquery.fancybox-1.3.4.pack.js");
            $this->view->headLink()->appendStylesheet($this->view->baseUrl() . '/css/jquery.fancybox-1.3.4.css');

            //Get the gallery model and retrieve every picture in the gallery
            $pictureModel = new Model_Bildes();
            $this->view->pictures = $pictureModel->getPicturesByGallery($id);

            //Set up the fancybox
            $r = '$(".category' . $id . '").fancybox({titlePosition:"over"});';

            $jquery->addOnLoad($r);

        } else {
            //IF no Id is set, redirect to the main page
            $this->log("Lūdzu izvēlieties galeriju", self::ERROR);
            $this->_redirect("/galerijas");
        }


    }

}

