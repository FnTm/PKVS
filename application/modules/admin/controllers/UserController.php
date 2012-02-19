<?php

class Admin_UserController extends JP_Controller_Action
{
    /** TODO Implement forcibly logging someone out. */
    /** @var Model_Users */
    private $_userModel;

    public function init()
    {

        $this->_userModel = new Model_Users();
        /* Initialize action controller here */
    }

    /**
     * Turniru kopskatu darbība
     * @return void
     */
    public function indexAction()
    {

        $this->view->users = $this->_userModel->getUsers();
    }

    public function enableAction()
    {
        $id = $this->_getParam("id", null);
        if (!is_null($id)) {
            $this->_userModel->approve($id);
        }
        $this->_redirect("/admin/user");

    }

    public function disableAction()
    {
$id = $this->_getParam("id", null);
        if (!is_null($id)) {
            $this->_userModel->disapprove($id);
        }
        $this->_redirect("/admin/user");
    }

    public function profilsAction()
    {
        if (!Zend_Auth::getInstance()->hasIdentity()) {
            $this->log('Lai varētu rediģēt profilu, lūdzu, ienāciet sistēmā', 'error');
            $this->_redirect('/');
        }
        else {
            $this->addLink('/turniri/pievienot/', 'Pievienot turnīru', 'u');
            $this->addLink('/turniri/mani/', 'Skatīt manus turnīrus', 'u');
            $this->addLink('/turniri/piedalos/', 'Skatīt manu dalību turnīriem', 'u');
            $this->addLink('/turniri/skatit/', 'see_all_tournaments', 'a');
            $id = Zend_Auth::getInstance()->getIdentity()->userId;
            $userModel = new Model_Users();
            $user = $userModel->getUser($id);
            $form = new Form_Lietotajs_Profils();

            if ($this->getRequest()->isPost()) {
                $data = $this->_getAllParams();
                if ($form->isValid($data)) {

                    $userModel->updateUser($id, $form->getValidValues($data));
                    $this->log('Datu maiņa veikta sekmīgi!', 'success');

                }
                else {
                    $form->populate($data);

                }


            }
            else {
                $form->populate($user);
            }
            $this->view->form = $form;

        }
    }


}

?>
