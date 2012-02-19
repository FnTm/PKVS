<?php

class AdminController extends JP_Controller_Action {

    public function init() {
        if (!Zend_Auth::getInstance()->hasIdentity() || Zend_Auth::getInstance()->getIdentity()->role !== 'a') {
            $this->_redirect('/');
        }
    }

    public function indexAction() {

            $this->_redirect('/admin/lietotaji');

    }
    public function lietotajiAction(){
        $usermodel = new Model_Users();
        $this->view->users=$usermodel->getUsers();
    }

    public function bloketLietotajuAction() {
        if (Zend_Auth::getInstance()->getIdentity()->role === 'a') {
            $id = $this->_getParam('id', NULL);
            if($id!=NULL){
            $usermodel = new Model_Users();
            $usermodel->bloket($id);
            $this->log('Lietotājs bloķēts', 'success');
                $this->_redirect('/admin/lietotaji');
            }
            else{
                $this->log('Nav norādīts lietotāja identifikators','error');
            }
        } else {
            $this->log('Jums nav pieejas šādai funkcijai', 'error');
        }
    }

    public function atbloketLietotajuAction() {
        if (Zend_Auth::getInstance()->getIdentity()->role === 'a') {
            $id = $this->_getParam('id', NULL);
            if($id != NULL){
            $usermodel = new Model_Users();
            $usermodel->atbloket($id);
            $this->log('Lietotājs atbloķēts', 'success');
                $this->_redirect('/admin/lietotaji');
            }
            else{
                $this->log('Nav norādīts lietotāja identifikators','error');
            }
        } else {
            $this->log('Jums nav pieejas šādai funkcijai', 'error');
        }
    }

    public function dzestLietotajuAction() {
        if (Zend_Auth::getInstance()->getIdentity()->role === 'a') {
            $id = $this->_getParam('id', NULL);
            if($id != NULL){
            $usermodel = new Model_Users();
            $usermodel->deleteUser($id);
            $this->log('Lietotājs dzēsts', 'success');
            }
            else{
                $this->log('Nav norādīts lietotāja identifikators.', 'error');
            }
        } else {
            $this->log('Jums nav pieejas šādai funkcijai', 'error');
        }
    }

}

/* ● Administrators 
  ○ Var dzēst jebkuru lietotā
  ○ Var dzēst turnīrus
  ○ Var dzēst jaunumus
  ○ Var dzēst bildes
  ○ Var dzēst galerijas
  ○ Var dzēst pielikumus */
?>

