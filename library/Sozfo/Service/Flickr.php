<?php
class Sozfo_Service_Flickr
{
    protected $_loader;
    protected $_prefixPath = array('Sozfo_Service_Flickr_' => 'Sozfo/Service/Flickr/');
    protected $_store = array();
    protected $_key;
    protected $_secret;
    protected $_token;
    protected $_cache;

    public function setKey ($key)
    {
        $this->_key = (string) $key;
        return $this;
    }

    public function getKey()
    {
        if (!isset($this->_key)) {
            throw new Sozfo_Service_Flickr_Exception('The api key must first be set');
        }
        return $this->_key;
    }

    public function setSecret ($secret)
    {
        $this->_secret = (string) $secret;
        return $this;
    }

    public function getSecret()
    {
        if (!isset($this->_secret)) {
            throw new Sozfo_Service_Flickr_Exception('The api secret must first be set');
        }
        return $this->_secret;
    }

    public function setToken ($token)
    {
        $this->_token = (string) $token;
        return $this;
    }

    public function getToken()
    {
        if (!isset($this->_token)) {
            throw new Sozfo_Service_Flickr_Exception('The api token must first be set');
        }
        return $this->_token;
    }

    public function setCache (Zend_Cache_Core $cache)
    {
        $this->_cache = $cache;
        return $this;
    }

    public function hasCache ()
    {
        if (null !== $this->_cache) {
            return true;
        } else {
            return false;
        }
    }

    public function getCache ()
    {
        return $this->_cache;
    }

    public function factory ($name, $id = null)
    {
        $name = ucfirst($name);
        if(!isset($this->_store[$name])) {
            $class = $this->_getLoader()->load($name);
            $this->_store[$name] = new $class($id);

            if (!$this->_store[$name] instanceof Sozfo_Service_Flickr_Interface) {
                throw new Sozfo_Service_Flickr_Exception('The clas should implement Sozfo_Service_Flickr_Interface');
            }
            $this->_store[$name]->setBroker($this);
        }
        $object = clone $this->_store[$name];
        return $object->setId($id);
    }

    public function addPluginPath ($prefix, $path)
    {
        if (isset($this->_loader)) {
            $this->_loader->addPrefixPath($prefix, $path);
        } elseif(!isset($this->_prefixPath[$prefix])) {
            $this->_prefixPath[$prefix] = $path;
        }
    }

    protected function _getLoader()
    {
        if (!isset($this->_loader)) {
            $this->_loader = new Zend_Loader_PluginLoader($this->_prefixPath);
        }
        return $this->_loader;
    }
}