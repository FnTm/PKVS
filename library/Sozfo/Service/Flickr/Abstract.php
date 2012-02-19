<?php
abstract class Sozfo_Service_Flickr_Abstract implements Sozfo_Service_Flickr_Interface
{
    const URI_BASE = 'http://www.flickr.com';

    protected $_id;
    protected $_broker;
    protected $_restClient;

    public function __construct ($id = null)
    {
        $this->_id = $id;
    }

    public function __set($name, $value)
    {
        $method = 'set' . $name;
        if (!method_exists($this, $method)) {
            throw new Sozfo_Service_Flickr_Exception('Invalid Flickr object property');
        }
        $this->$method($value);
    }

    public function __get($name)
    {
        $method = 'get' . $name;
        if (!method_exists($this, $method)) {
            throw new Sozfo_Service_Flickr_Exception('Invalid Flickr object property');
        }
        return $this->$method();
    }

    public function setOptions(array $options)
    {
        $methods = get_class_methods($this);
        foreach ($options as $key => $value) {
            $method = 'set' . ucfirst($key);
            if (in_array($method, $methods)) {
                $this->$method($value);
            }
        }
        return $this;
    }

    public function setId ($id)
    {
        $this->_id = $id;
        return $this;
    }

    public function getId ()
    {
        return $this->_id;
    }

    public function setBroker (Sozfo_Service_Flickr $broker)
    {
        $this->_broker = $broker;
        return $this;
    }

    public function getBroker ()
    {
        return $this->_broker;
    }

    protected function _request ($method, array $options = array())
    {
        //It is allowed to omit the first 'flickr.' part of the method
        if (substr($method, 0, 7) != 'flickr.') {
            $method = 'flickr.' . $method;
        }
        $defaultOptions = array(
            'method'  => $method,
            'format'  => 'php_serial',
            'api_key' => $this->getBroker()->getKey() );
        $options = $this->_prepareOptions($method, $options, $defaultOptions);

        if (null !== $this->getBroker()->getSecret()) {
            $options = $this->_sign(
                $options,
                $this->getBroker()->getSecret(),
                $this->getBroker()->getToken());
        }

        $cacheName = 'SozfoServiceFlickrRequest' . md5(implode('',$options));
        if (!$this->getBroker()->hasCache()
         || !$response = $this->getBroker()->getCache()->load($cacheName)) {
            $restClient = $this->_getRestClient();
            $restClient->getHttpClient()->resetParameters();
            $response = $restClient->restGet('/services/rest/', $options);

            if ($response->isError()) {
                throw new Sozfo_Service_Flickr_Exception('An error occurred sending request. Status code: '
                                                          . $response->getStatus());
            }

            $response = $response->getBody();
            if ($this->getBroker()->hasCache()) {
                $this->getBroker()->getCache()->save($response, $cacheName);
            }

        }

        $response = (object) unserialize($response);
        $this->_checkErrors($response);
        return $this->_stripContent($response);
    }

    protected function _getRestClient ()
    {
        if (null === $this->_restClient) {
            $this->_restClient = new Zend_Rest_Client(self::URI_BASE);
        }

        return $this->_restClient;
    }

    /**
     * Prepare options for the request
     *
     * @param  string $method         Flickr Method to call
     * @param  array  $options        User Options
     * @param  array  $defaultOptions Default Options
     * @return array Merged array of user and default/required options
     */
    protected function _prepareOptions ($method, array $options, array $defaultOptions)
    {
        $options['method']  = (string) $method;
        $options['api_key'] = (string) $this->getBroker()->getKey();

        return array_merge($defaultOptions, $options);
    }

    protected function _sign (array $options, $secret, $token)
    {
        $signature = '';
        $options['auth_token'] = $token;
        ksort($options);
        foreach ($options as $key=>$value) {
            $signature .= $key . $value;
        }
        $options['api_sig'] = md5($secret . $signature);
        return $options;
    }

    protected function _checkErrors ($response)
    {
        if ($response->stat === 'fail') {
            throw new Sozfo_Service_Flickr_Exception('Search failed due to error: '
                                                      . $response->message
                                                      . ' (error #' . $response->code . ')');
        }
    }

    protected function _stripContent( $response ){
        if (is_object($response)) {
            foreach (get_object_vars($response) as $key => $value) {
                $response->$key = $this->_stripContent($value);
            }
            return $response;
        } elseif (is_array($response) && !isset($response['_content'])) {
            foreach ($response as $key => $value) {
                $response[$key] = $this->_stripContent($value);
            }
            return $response;
        } elseif (is_array($response)) {
            return $response['_content'];
        } else {
            return $response;
        }
    }
}