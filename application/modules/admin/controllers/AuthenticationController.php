<?php
/**
 *
 * @package Authentication
 * @subpackage Front
 */
/**
 * Allows for the authentication of users credentials. Without authentication, admin section cannot be accessed.
 * @package Authentication
 * @subpackage Front
 */
class AuthenticationController extends JP_Controller_Action
{
    public $userModel;
    public $bossName = 'boss';
    public $userName = 'user';
    public $authmodel;

    public function init()
    {
        $this->authModel = new Model_Auth();
    }

    public function indexAction()
    {
        $this->_forward('login');
    }

    public function loginAction()
    {
        $this->view->title = 'Login';
        if (Zend_Auth::getInstance()->hasIdentity()) {
            // $role = Zend_Registry::get('role');
            $this->_redirect('/');
        }
        $request = $this->getRequest();
        $form = new Form_LoginForm();
		$form->setAction($this->view->route."/authentication/login");
        if ($request->isPost()) {
            if ($form->isValid($this->_request->getPost())) {
                $username = $form->getValue('username');
                $password = $form->getValue('password');
                $authAdapter = $this->authModel->getAuthAdapter();
                $authAdapter->setIdentity($username)->setCredential($password);
                $auth = Zend_Auth::getInstance();
                $result = $auth->authenticate($authAdapter);
                $userModel = new Model_Users();
                $identity = $userModel->getUserByUsername($username);
                if ($result->isValid() && $identity->isBlocked=='0') {
                    $authStorage = $auth->getStorage();
                    $authStorage->clear();
                    $authStorage->write($identity);
                    if (Zend_Auth::getInstance()->hasIdentity()) {
                        $this->_redirect('/');
                    }
                } else {
                    $this->view->errorMessage = "Lietotājvārds un/vai parole ir nepareiza.";
                }
            }
        }
        $this->view->form = $form;
    }

    public function floginAction()
    {
        $this->view->title = 'Login';
        if (Zend_Auth::getInstance()->hasIdentity()) {
            // $role = Zend_Registry::get('role');
            $this->_redirect('/');
        }
        $appId = '212393152179326';
        $secret = 'f482cc1d3f0d9c48aa73d04b07515a5e';
        $redirectUri = APP_DOMAIN.'/'.$this->view->lang."/authentication/flogin/";
        $scope = 'email';

        // Create the authentication adapter.
        $adapter = new JP_Auth_Adapter_Facebook($appId, $secret, $redirectUri, $scope);

        // Get an authenticator instance
        $auth = Zend_Auth::getInstance();

        // This call will automatically redirect to facebook with the passed parameters.
        $result = $auth->authenticate($adapter);

        if ($result->isValid()) {

            // Get the messages
            $messages = $result->getMessages();

            // Get the user object from the returned messages.
            $fbUser = $messages['user'];

           // var_dump($fbUser);
            $userModel=new Model_Users();
            $register=false;
            $user=$userModel->processUserFromFaceBook($fbUser,$register);
            if($user->isBlocked==0){
            $auth->getStorage()->clear();
            $auth->getStorage()->write($user);
            if($register===true){
                $this->log('Paldies, esat veiksmīgi reģistrējies mūsu lapā!','success');
            }
            }
            else{
                $auth->getStorage()->clear();
                $this->log('Ienākšana faur facbook nebija veiksmīga!','error');

            }
            $this->_redirect('/');
            
           
        }
    }

    public function registerAction()
    {
        if (Zend_Auth::getInstance()->hasIdentity()) {
            $this->_redirect('/');
        }
        $form = new Form_Register();
		$form->setAction($this->view->route.'/authentication/register');
        $this->view->form = $form;
        if ($this->getRequest()->isPost()) {
            $data = $this->_request->getPost();
            if ($form->isValid($data)) {
                $userModel = new Model_Users();
                $userModel->createUser($form->getValidValues($data));
                /** @todo Jāpievieno paziņojums, ka veiksmīga reģistrācija */
                $this->_forward('login');
            }
            else {
                $form->populate($data);

            }

        }
    }

    public function logoutAction()
    {
        Zend_Auth::getInstance()->clearIdentity();
        $this->_redirect('/');
    }
}





