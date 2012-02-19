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


    /**
     *
     * @return void
     * TODO Ik pa laikam pārbaudīt vai lietotājs nav atteicies no aplikācijas
     */
    public function loginAction()
    {
        $this->view->title = 'Login';
        if (Zend_Auth::getInstance()->hasIdentity()) {
            // $role = Zend_Registry::get('role');
            $this->_redirect('/');
        }

        $draugiem = Zend_Registry::get("draugiemOptions");
        // Create the authentication adapter.
        $adapter = new JP_Auth_Adapter_Draugiem($draugiem->appId, $draugiem->secret, $draugiem->redirectUri);
        //echo new ReflectionClass($adapter);
        //exit();
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
            $userModel = new Model_Users();
            $register = false;

            $user = $userModel->processUserFromDraugiem($fbUser, $register);

            if ($user->isApproved == 0) {
                $auth->getStorage()->clear();
                if ($register === true) {
                    $this->log('Paldies, esat veiksmīgi reģistrējies mūsu lapā! Administrators apskatīs jūsu pieteikumu, un piešķirs tiesības jau tuvākajā laikā!', 'success');
                }
                else {

                    $auth->getStorage()->clear();
                    $this->log('Jūsu profils vēl nav apstiprināts! Tas notiks jau tuvākajā laikā!', 'error');

                }
            }
            else {
                $auth->getStorage()->clear();
                $auth->getStorage()->write($user);
                Zend_Registry::set('role', $user->role);

            }
            $this->_redirect('/');


        }
    }

    public function logoutAction()
    {
        Zend_Auth::getInstance()->clearIdentity();
        $this->_redirect('/');
    }
}





