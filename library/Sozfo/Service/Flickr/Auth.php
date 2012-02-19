<?php
class Sozfo_Service_Flickr_Auth extends Sozfo_Service_Flickr_Abstract
{
    const format = 'http://flickr.com/services/auth/?api_key=%s&perms=%s&extra=%s&api_sig=%s';
    const perms = array('read', 'write', 'delete');

    protected $_perms = 'read';

    public function setPerms ($perms)
    {
        if (!in_array($perms, self::perms)) {
            throw new Sozfo_Service_Flickr_Exception('Permission must be one of the following: ' . implode(', ', self::perms));
        }
        $this->_perms = (string) $perms;
        return $this;
    }

    public function getPerms ()
    {
        return $this->_perms;
    }

    public function getFrobUrl(){
        $redirect = 'http://' . $_SERVER[ 'SERVER_NAME' ] . $_SERVER[ 'REQUEST_URI' ];
        $url = sprintf(self::format,
                       $this->getBroker()->getKey(),
                       $this->getPerms(),
                       urlencode($redirect),
                       md5($this->getBroker()->getSecret()
                        . 'api_key'
                        . $this->getBroker()->getKey()
                        . 'extra'
                        . $redirect
                        . 'permsread'));
        return $url;
    }

    public function getTokenFromFrob ($frob)
    {
        $response = $this->_request('auth.getToken', array('frob'=>$frob));
        return $response['auth']['token'];
    }
}