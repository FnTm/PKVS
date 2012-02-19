<?php

class LietotajsController extends JP_Controller_Action
{

    public function init()
    {
        /* Initialize action controller here */
    }

    /**
     * Turniru kopskatu darbība
     * @return void
     */
    public function indexAction()
    {
        if (Zend_Auth::getInstance()->hasIdentity()) {
            $this->_redirect('/lietotajs/profils');
        }
        else {
            $this->_redirect('/');
        }
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
