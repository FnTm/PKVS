<?php
/**
 * Created by JetBrains PhpStorm.
 * User: Janis
 * Date: 12.23.4
 * Time: 17:26
 * To change this template use File | Settings | File Templates.
 */
class Admin_JaunumuKategorijasController extends JP_Controller_Action
{
    public function init()
    {

    }

    public function indexAction()
    {
        $catModel = new Model_Jaunumi_Kategorijas();
        $this->view->categories = $catModel->getAll();
    }

    public function pievienotAction()
    {
        $this->view->form = $form = new Admin_Form_Jaunumi_Pievienot();
        if ($this->getRequest()->isPost()) {
            $data = $this->_getAllParams();
            if ($form->isValid($data)) {
                $kategorijasModel = new Model_Jaunumi_Kategorijas();
                $kategorijasModel->createCategory($form->getValidValues($data));
                $this->_redirect("/admin/jaunumu-kategorijas");
            }
            else {
                $form->populate($data);
            }
        }
    }

    public function redigetAction()
    {
        $kategorijasModel = new Model_Jaunumi_Kategorijas();
        $id = $this->_getParam("id", null);
        if (!is_null($id)) {
            $this->view->form = $form = new Admin_Form_Jaunumi_Pievienot();
            $form->setAction("/admin/jaunumu-kategorijas/rediget/id/" . $id);
            if ($this->getRequest()->isPost()) {
                $data = $this->_getAllParams();
                if ($form->isValid($data)) {
                    $kategorijasModel->updateCategory($id, $form->getValidValues($data));
                    $this->_redirect("/admin/jaunumu-kategorijas");
                }
                else {
                    $form->populate($data);
                }
            }
            else {
                $form->populate($kategorijasModel->getCategory($id));
            }
        }
    }

    public function dzestAction()
    {
        $kategorijasModel = new Model_Jaunumi_Kategorijas();
        $id = $this->_getParam("id", null);
        if (!is_null($id)) {
            $kategorijasModel->deleteCategory($id);

        }
        $this->_redirect("/admin/jaunumu-kategorijas/");
    }

}
