<?php

class Admin_KonfiguracijaController extends JP_Controller_Action
{

    public function init()
    {
        /* Initialize action controller here */
    }

    public function indexAction()
    {
        $this->view->form = $form = new Admin_Form_Konfiguracija();
        if ($this->getRequest()->isPost()) {
            $data = $this->_getAllParams();
            if ($form->isValid($data)) {
                $data = $form->getValidValues($data);

            }
            else {
                $form->populate($data);
            }
        }

    }


}

