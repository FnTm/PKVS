<?php
/**
 * Contains authentication controller
 * @package Authentication
 * @subpackage Default
 */
/**
 * Allows for the authentication of users credentials. Without authentication, admin section cannot be accessed.
 * @package Authentication
 * @subpackage Default
 */
class AuthenticationController extends JP_Controller_Action
{
    /**
     * User model
     * @var Model_Users
     */
    public $userModel;

    /**
     * Redirects a user to the login section, if the controller is called directly
     */
    public function indexAction()
    {
        $this->_forward('login');
    }

    /**
     * Login action
     */
    public function loginAction()
    {
        //Set the view title
        $this->view->title = 'Login';
        //If user logged in, redirect to root
        if (Zend_Auth::getInstance()->hasIdentity()) {
            // $role = Zend_Registry::get('role');
            $this->_redirect('/');
        }
        //Receive draugiem.lv options
        $draugiem = Zend_Registry::get("draugiemOptions");
        // Create the authentication adapter.
        $adapter = new JP_Auth_Adapter_Draugiem($draugiem->appId, $draugiem->secret, $draugiem->redirectUri);
        //echo new ReflectionClass($adapter);
        //exit();
        // Get an authenticator instance
        $auth = Zend_Auth::getInstance();

        // This call will automatically redirect to facebook with the passed parameters.
        $result = $auth->authenticate($adapter);

        //Check if user is valid
        if ($result->isValid()) {

            // Get the messages
            $messages = $result->getMessages();

            // Get the user object from the returned messages.
            $fbUser = $messages['user'];
            $token = $messages['token'];
            // var_dump($fbUser);
            $userModel = new Model_Users();
            $register = false;
            //Process  the retrieved user info
            $user = $userModel->processUserFromDraugiem($fbUser, $register, $token);

            //Log the user out, if not approved
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
            //Redirect to root page
            $this->_redirect('/');


        }
    }

    /**
     * Logout action
     */
    public function logoutAction()
    {
        //Clear the session and techincally log user out
        Zend_Auth::getInstance()->clearIdentity();
        $this->_redirect('/');
    }
}





