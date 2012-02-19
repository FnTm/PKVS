<?php

class TurniriController extends JP_Controller_Action
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
        $this->addLink('/turniri/pievienot/', 'Pievienot turnīru', 'u');
        $this->view->test = "Turniri";

        $turniriModel = new Model_Turniri();

        $results = $turniriModel->getAllTurniri($this->view->order = $this->_getParam('order', 'asc'));


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

    /*     * Jauna turnīra pievienošanas darbība
     * @return void
     */

    public function pievienotAction()
    {
        if (!Zend_Auth::getInstance()->hasIdentity()) {
            $this->log('Lai pievienotu turnīru, lūdzu ienāciet sistēmā!', 'error');
            $this->_redirect('/');
        }

        $this->view->test = "Turniri";
        $form = new Form_Turniri_Pievienot();
        $this->view->form = $form;
        $form->setAction('/' . Zend_Registry::get('lang') . '/turniri/pievienot');
        $uploadField = 'logo';
        if ($this->getRequest()->isPost()) {
            $data = $this->_getAllParams();
            if ($form->isValid($data)) {
                $turniriModel = new Model_Turniri();

                $upload = new Zend_File_Transfer_Adapter_Http ();
                $upload->setDestination(UPLOAD_PATH . "/");
                try {
                    // upload received file(s)
                    $upload->receive();
                } catch (Zend_File_Transfer_Exception $e) {
                    $e->getMessage();
                }
                $name = $upload->getFileName($uploadField);
                $ext = pathinfo($name, PATHINFO_EXTENSION);

                $renameFile = $this->genRandomString() . "." . $ext;

                /** @var $fullFilePath Links sistēmā, uz faila konkrēto atrašanās vietu */
                $fullFilePath = UPLOAD_PATH . "/" . $renameFile;
                /** @var $fullPropsPath Links izmantojot domēnu */
                $fullPropsPath = RESOURCE_PATH . "/uploaded/" . $renameFile;
                //echo $fullPropsPath;
                // Rename uploaded file using Zend Framework
                $filterFileRename = new Zend_Filter_File_Rename(array('target' => $fullFilePath, 'overwrite' => true));

                $filterFileRename->filter($name);

                $insertData = $form->getValidValues($data);
                $insertData[$uploadField] = $fullPropsPath;
                if (false !== $id = $turniriModel->createTurnirs($insertData)) {
                    $this->log('Turnīrs tika pievienots!', 'success');
                    $this->_redirect('/turniri/skatit/id/' . $id);
                }
            } else {

                $form->populate($data);
            }
        }
    }

    /*     * Esoša turnīra rediģēšanas darbība
     * @return void
     */

    public function redigetAction()
    {

        $form = new Form_Turniri_Pievienot();
        $form->removeElement('logo');
        $form->getElement('submit')->setLabel('Saglabāt');
        $turniriModel = new Model_Turniri();

        $id = $this->_getParam('id', null);
        if ($id == NULL) {
            $this->log('Nav norādīts turnīra identifikators!', 'error');
            $form = NULL;

        }
        else {
            $turnirs = $turniriModel->getTurnirs($id);
            if ($turnirs['tournamentOwner'] === Zend_Auth::getInstance()->getIdentity()->userId || Zend_Auth::getInstance()->getIdentity()->role === 'a') {
                $this->view->form = $form;
                $data = $turniriModel->getTurnirs($id);
                if ($this->getRequest()->isPost()) {
                    $data = $this->_getAllParams();
                    if ($form->isValid($data)) {

                        $turniriModel->updateTurnirs($id, $form->getValidValues($data));
                        $this->log('Turnīrs tika veiksmīgi rediģēts!', 'success');
                        $this->_redirect('/turniri/skatit/id/' . $id);
                    }
                    else {
                        $form->populate($data);
                    }

                }
                else {
                    $form->populate($data);
                }


            }
            else {
                $this->log('Jums nav tiesību labot šo bildi!', 'error');
                $this->_redirect('/turniri/');
            }

        }

    }

    public function dzestAction()
    {
        if (!Zend_Auth::getInstance()->hasIdentity()) {
            $this->log('Lai veiktu šo darbību jāienāk sistēmā', 'error');
            $this->_redirect('/');
        }
        $id = $this->_getParam('id', NULL);
        $turniriModel = new Model_Turniri();
        $turnirs = $turniriModel->getTurnirs($id);
        if (($turnirs['tournamentOwner'] === Zend_Auth::getInstance()->getIdentity()->userId || Zend_Auth::getInstance()->getIdentity()->role === 'a')) {
            $turniriModel->deleteTurnirs($id);
            $this->log('Turnīrs veiksmīgi dzēsts', 'success');
            $this->_redirect('/');
        }
    }

    public function skatitAction()
    {
        $id = $this->_getParam('id', NULL);
        $turniriModel = new Model_Turniri();
        $this->view->data = $data = $turnirs = $turniriModel->getTurnirs($id);
        if ($data == NULL || empty($data)) {
            $this->log('Šāds turnīrs neeksistē', 'error');
            $this->_redirect('/');
        }
        $isOnline = Zend_Auth::getInstance()->hasIdentity();
        if ($isOnline) {
            $userId = Zend_Auth::getInstance()->getIdentity()->userId;
            $this->view->isParticipating = $isParticipating = $turniriModel->isParticipating($userId, $id);
            $this->view->team=$turniriModel->getByUserAndTournament($userId, $id);
            $this->view->isOnline = $isOnline;
        }


        $this->addLink('/turniri/dzest/id/' . $id, 'Dzēst šo turnīru', 'a');
        if ($isOnline && ($turnirs['tournamentOwner'] === $userId || Zend_Auth::getInstance()->getIdentity()->role === 'a')) {
            $this->addLink('/galerija/pievienot/id/' . $turnirs['tournamentId'], 'add_gallery', 'u');
            $this->addLink('/turniri/rediget/id/' . $turnirs['tournamentId'], 'Rediģēt turnīru', 'u');
            $this->addLink('/jaunumi/pievienot/id/' . $turnirs['tournamentId'], 'add_news', 'u');
            $this->addLink('/turniri/pievienot/', 'Pievienot jaunu turnīru', 'u');
            $this->addLink('/pielikums/pievienot/id/' . $turnirs['tournamentId'], 'Pievienot jaunu pielikumu', 'u');
        }
        else {
            if ($isOnline) {
                if (!$isParticipating) {
                    $this->addLink('/komanda/pievienot/id/' . $id, 'Pieteikties turnīram', 'u');
                }
            }
            $this->addLink('/turniri/pievienot/', 'add_tournament', 'u');
        }
        $this->addLink('/galerija/skatit-turniram/id/' . $turnirs['tournamentId'], 'related_galleries', 'u');


        $pielikumsModel= new Model_Pielikums();

        $this->view->pielikumi=$pielikumsModel->getPielikumiByTournament($id);
        $jaunumimodel = new Model_Jaunumi();
        $this->view->jaunumi = $jaunumimodel->getJaunumsByTournament($id);

    }

    public function maniAction()
    {
        $this->addLink('/turniri/pievienot/', 'add_tournament', 'u');


        $turniriModel = new Model_Turniri();
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
        $turniriModel = new Model_Turniri();
        $this->view->turniri = $turniriModel->getTournamentsByUser($id);

    }

}
