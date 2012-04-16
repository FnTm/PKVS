<?php

class PasakumiController extends JP_Controller_Action
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

        $pasakumiModel = new Model_Pasakumi();

        $results = $pasakumiModel->getAllTurniri($this->view->order = $this->_getParam('order', 'asc'));


        /**
         * Setup the paginator if $results is set.
         */
        if (isset($results)) {
            $paginator = Zend_Paginator::factory($results);
            $paginator->setItemCountPerPage(2);
            $paginator->setCurrentPageNumber($this->_getParam('page', 0));
            $this->view->paginator = $paginator;
            /**
             * We will be using $this->view->paginator to loop thru in our view ;-)
             */

            Zend_Paginator::setDefaultScrollingStyle('Sliding');
            Zend_View_Helper_PaginationControl::setDefaultViewPartial(
                'pagination.phtml' //Take note of this, we will be creating this file
            );
        }

    }


    public function skatitAction()
    {
        $id = $this->_getParam('id', NULL);
        $pasakumsModel=new Model_Pasakumi();
        $this->view->pasakums=$pasakumsModel->getPasakums($id)->toArray();
        $apmModel=new Model_Apmekletiba();
        $this->view->apmeklejums=$apmModel->getApmeklejumsByEventId($id);


    }

    public function maniAction()
    {
        $this->addLink('/turniri/pievienot/', 'add_tournament', 'u');


        $turniriModel = new Model_Pasakumi();
        $turniri = $turniriModel->getTurniriByUser(Zend_Auth::getInstance()->getIdentity()->userId);
        $this->view->turniri = $turniri;
        $this->view->test = "Mani turnīri";
    }

    public function piedalosAction(){
$this->addLink('/turniri/pievienot/', 'add_tournament', 'u');

        $this->addLink('/turniri/mani/', 'see_my_tournaments', 'u');
        $this->addLink('/turniri/piedalos/', 'my_tournament_activity', 'u');
        $this->addLink('/turniri/', 'see_all_tournaments', 'a');
        if (!Zend_Auth::getInstance()->hasIdentity()) {
            $this->log('Lai redzētu šos datus, jāienāk sistēmā!', 'error');
            $this->_redirect('/');
        }
        $id = Zend_Auth::getInstance()->getIdentity()->userId;
        $turniriModel = new Model_Pasakumi();
        $this->view->turniri = $turniriModel->getTournamentsByUser($id);

    }

}
