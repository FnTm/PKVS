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
        $galleryModel = new Model_Galerijas();
        $this->view->galleries = $galleryModel->getAllWithOneImage();

    }

    /**
     * Function that displays a particular gallery
     */
    public function skatitAction()
    {
        $id = $this->_getParam("id");
        if (!is_null($id)) {
            $jquery = $this->view->jQuery();

            $jquery->uiEnable();

            $jquery->addJavascriptFile(
                $this->view->baseUrl() . "/js/jquery.fancybox-1.3.4.pack.js");
            $this->view->headLink()->appendStylesheet($this->view->baseUrl() . '/css/jquery.fancybox-1.3.4.css');

            $pictureModel = new Model_Bildes();
            $this->view->pictures = $pictureModel->getPicturesByGallery($id);

            $r = '$(".category' . $id . '").fancybox({titlePosition:"over"});';

            $jquery->addOnLoad($r);

        } else {
            $this->log("Lūdzu izvēlieties galeriju", self::ERROR);
            $this->_redirect("/galerijas");
        }


    }

}

