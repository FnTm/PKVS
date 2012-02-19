<?php
class JP_Auth_Adapter_Draugiem implements Zend_Auth_Adapter_Interface
{
    /**
     * The Authentication URI, used to bounce the user to the facebook redirect uri.
     *
     * @var string
     */
    const AUTH_URI = 'http://api.draugiem.lv/authorize/?app=%s&hash=%s&redirect=%s';

    /**
     * The token URI, used to retrieve the OAuth Token.
     *
     * @var string
     */
    const API_URI = 'http://api.draugiem.lv/php/';

    /**
     * The application ID
     *
     * @var string
     */
    private $_appId = null;

    /**
     * The application secret
     *
     * @var string
     */
    private $_secret = null;
    /**
     * The redirect uri
     *
     * @var string
     */
    private $_redirectUri = null;

    private $_hash = null;

    /**
     * Constructor
     *
     * @param string $appId the application ID
     * @param string $secret the application secret
     * @param string $scope the application scope
     * @param string $redirectUri the URI to redirect the user to after successful authentication
     */
    public function __construct($appId, $secret, $redirectUri)
    {
        $this->_appId = $appId;
        $this->_secret = $secret;
        $this->_redirectUri = $redirectUri;
    }

    /**
     * Sets the value to be used as the application ID
     *
     * @param  string $appId The application ID
     * @return JP_Auth_Adapter_Draugiem Provides a fluent interface
     */
    public function setAppId($appId)
    {
        $this->_appId = $appId;
        return $this;
    }

    /**
     * Sets the value to be used as the application secret
     *
     * @param  string $secret The application secret
     * @return JP_Auth_Adapter_Draugiem Provides a fluent interface
     */
    public function setSecret($secret)
    {
        $this->_secret = $secret;
        return $this;
    }

    /**
     * Sets the value to be used as the application scope (array())
     *
     * @param  string $scope The application scope
     * @return JP_Auth_Adapter_Draugiem Provides a fluent interface
     */
    /**
     * Sets the redirect uri after successful authentication
     *
     * @param  string $redirectUri The redirect URI
     * @return JP_Auth_Adapter_Draugiem Provides a fluent interface
     */
    public function setRedirectUri($redirectUri)
    {
        $this->_redirectUri = $redirectUri;
        return $this;
    }

    public function  setHash()
    {
        $this->_hash=md5($this->_secret.$this->_redirectUri);
    }

    /**
     * Authenticates the user against Draugiem passport
     * Defined by Zend_Auth_Adapter_Interface.
     *
     * @throws Zend_Auth_Adapter_Exception If answering the authentication query is impossible
     * @return Zend_Auth_Result
     */
    public function authenticate()
    {
        // Get the request object.
        $frontController = Zend_Controller_Front::getInstance();
        $request = $frontController->getRequest();

        // First check to see wether we're processing a redirect response.
        $code = $request->getParam('dr_auth_status');
        $authcode = $request->getParam('dr_auth_code');
            $this->setHash();
        //var_dump($this->_hash);
        //exit;
        if (empty ($code)) {
            // Create the initial redirect
            $loginUri = sprintf(self::AUTH_URI, $this->_appId,$this->_hash, $this->_redirectUri);


            header('Location: ' . $loginUri);
            exit(0);
        }
        else if($code=="ok")
        {
            // Looks like we have a code. Let's get ourselves an access token
            $client = new Zend_Http_Client(self::API_URI);

            $client->setParameterGet('code', $authcode);
            $client->setParameterGet('app', $this->_secret);
            $client->setParameterGet('action', "authorize");
            $result = $client->request('GET');
            $data = unserialize($result->getBody());
            $user=$data['users'][$data['uid']];

            return new Zend_Auth_Result(Zend_Auth_Result::SUCCESS, $data['uid'], array('user' => $user, 'token' => $data['apikey']));
        }

        return new Zend_Auth_Result(Zend_Auth_Result::FAILURE, null, 'Lietotājs neatļāva piekļuvi saviem datiem');
    }
}