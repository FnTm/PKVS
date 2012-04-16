<?php
/**
 * User: Janis
 * Date: 12.15.4
 * Time: 09:49
 */

class Admin_ApmekletibaController extends JP_Controller_Action
{
    protected $_userModel;

    public function init()
    {
        $this->_userModel = new Model_Users();
    }

    public function indexAction()
    {
        $typeModel = new Model_Apmekletiba_Tips();


    }

    public function redigetAction()
    {
        $apmModel = new Model_Apmekletiba();
        $apmTipsModel = new Model_Apmekletiba_Tips();
        $this->view->id = $id = $this->_getParam("id");
        $this->view->types = $apmTipsModel->getAll();
        $this->view->users = $this->_userModel->getUsers();
        $this->view->form = $form = new Admin_Form_ApmekletibaTips_Pievienot();
        $form->setAction("/admin/apmekletiba-tips/pievienot");
        if ($this->getRequest()->isPost()) {
            $data = $this->_getAllParams();
            var_dump($data);
            foreach ($data as $key => $user) {
                if (strpos($key, "user_") !== false) {

                    $split = explode("user_", $key);
                    $userId = $split[1];

                    $apmModel->insertApmeklejums($id, $userId, $user);
                }

            }
            /*if($form->isValid($data)){

                $apmTipsModel->insert($form->getValidValues($data));
                $this->_redirect("/admin/apmekletiba-tips/");

            }
            else{
                $form->populate($data);
            }
            */
        }
    }

}
