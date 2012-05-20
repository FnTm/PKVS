<?php
/** Contains picture management controller
 * @author Janis Peisenieks
 * @package Bildes
 * @subpackage Admin
 */
/**
 * A controller for picture magament
 * @author Janis Peisenieks
 * @package Bildes
 * @subpackage Admin
 */
class Admin_BildesController extends JP_Controller_Action
{
    /**
     * Name of the db table to use
     * @var string
     */
    public $_name = "pictures";

    /** Name of the upload field in the form
     * @var string
     */
    public $_uploadField = "picture";
    /**
     * Maximum width of image
     * @var int
     */
    private $maxx = 1024;
    /**
     * Maximum height of an image
     * @var int
     */
    private $maxy = 768;

    /**
     * Displays a list of all pictures
     */
    public function indexAction()
    {

        $pictureModel = new Model_Bildes();
        $this->view->bildes = $pictureModel->getAll();


    }

    /**
     * Picture adding function.
     */
    public function pievienotAction()
    {
        $pictureModel = new Model_Bildes();
        //We create a new form, and pass the reference to the view
        $galleryModel = new Model_Galerijas();
        $galleries = $galleryModel->getAll();
        $this->view->form = $form = new Admin_Form_Bildes_Pievienot($galleries);
        if ($this->getRequest()->isPost()) {
            $data = $this->_getAllParams();
            if ($form->isValid($data)) {
                $upload = new Zend_File_Transfer_Adapter_Http ();
                $upload->setDestination(UPLOAD_PATH . "/");
                try {
                    // upload received file(s)
                    $upload->receive();
                } catch (Zend_File_Transfer_Exception $e) {
                    $e->getMessage();
                }
                $insertData = $form->getValidValues($data);


                //$this->log('Izdevās pievienot bildi', 'success');
                $name = $upload->getFileName($this->_uploadField);
                $ext = pathinfo($name, PATHINFO_EXTENSION);
                $insertData['pictureExt'] = $ext;
                $insertData['pictureTitle'] = htmlspecialchars($insertData['pictureTitle']);
                $id = $pictureModel->createPicture($insertData);
                $renameFile = $id . "." . $ext;
                $renameFullFile = $id . "_full." . $ext;
                $thumbName = $id . "_small." . $ext;

                /** @var $fullFilePath Links sistēmā, uz faila konkrēto atrašanās vietu */
                $fullFilePath = UPLOAD_PATH . "/" . $renameFile;
                $fullSizeFilePath = UPLOAD_PATH . "/" . $renameFullFile;
                /** @var $fullPropsPath Links izmantojot domēnu */
                $fullPropsPath = RESOURCE_PATH . "/uploaded/" . $renameFile;
                //echo $fullPropsPath;
                // Rename uploaded file using Zend Framework
                $filterFileRename = new Zend_Filter_File_Rename (array('target' => $fullSizeFilePath, 'overwrite' => true));

                $filterFileRename->filter($name);

                $this->resizeImage($fullSizeFilePath, $fullFilePath);
                $thumb = PhpThumbFactory::create($fullSizeFilePath);
                $thumb->adaptiveResize(150, 150);

                $thumb->save(UPLOAD_PATH . "/" . $thumbName);

                //Adding the success message
                $this->log("Bilde veiksmīgi izveidota", self::SUCCESS);
                //Reditecting to the index action
                $this->_redirect("/admin/bildes");
            }
            else {
                //Repopulate the form, if the input data is not valid
                $form->populate($data);
            }
        }
    }

    /**
     * Picture editing action. id parameter in the URL has to be set
     */
    public function redigetAction()
    {
        $id = $this->_getParam("id");
        //Check if the id param in the URL has been set
        if (!is_null($id)) {
            $pictureModel = new Model_Bildes();
            $galleryModel = new Model_Galerijas();
            $galleries = $galleryModel->getAll();
            //We create a new form, and pass the reference to the view
            $this->view->form = $form = new Admin_Form_Bildes_Pievienot($galleries);
            //We remove the unnecessary picture upload field.
            $form->removeElement("picture");
            $form->setAction("/admin/bildes/rediget/id/" . $id);
            if ($this->getRequest()->isPost()) {
                $data = $this->_getAllParams();
                if ($form->isValid($data)) {


                    $pictureModel->updatePicture($id, $form->getValidValues($data));
                    //Adding the success message
                    $this->log("Bilde veiksmīgi rediģēta", self::SUCCESS);
                    //Reditecting to the index action
                    $this->_redirect("/admin/bildes");
                }
                else {
                    //Repopulate the form, if the input data is not valid
                    $form->populate($data);
                }
            }
            else {
                //Populate the form from the db, if this is not a post
                $form->populate($pictureModel->getPicture($id));
            }
        }
        else {
            //Showing error message and redirecting to the list page
            $this->log("Nav norādīts rediģējamās bildes identifikators", self::ERROR);
            $this->_redirect("/admin/bildes");
        }
    }

    /**
     *Picture deletion function. The picture Id must be set in an URL parameter
     */
    public function dzestAction()
    {
        //Getting the Id from the URL
        $id = $this->_getParam("id", null);
        //Checking if an Id has been set
        if (!is_null($id)) {
            $pictureModel = new Model_Bildes();
            //Deleting the picture
            $pictureModel->deletePicture($id);
            //Adding the success message
            $this->log("Bilde veiksmīgi dzēst", self::SUCCESS);
            //Reditecting to the index action
            $this->_redirect("/admin/bildes");

        } else {
            $this->log("Nav norādīts dzēšamās bildes identifikators", self::ERROR);
            $this->_redirect("/admin/bildes");
        }

    }
    /**
     * Function to resize uploaded image
     * @param $path string path to the image to resize
     * @param $newName string path to the new location
     */
    private function resizeImage($path,$newName)
    {
        $thumb = PhpThumbFactory::create($path);
        $thumb->setOptions(array('jpegQuality' => 75));
        $thumb->resize($this->maxx, $this->maxy);
        if($newName!=$path){
            $path=$newName;
        }
        $thumb->save($path);

    }


}

