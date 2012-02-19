<?php
class Sozfo_Service_Flickr_Collection extends Sozfo_Service_Flickr_Abstract
{
    protected $_title;
    protected $_description;
    protected $_iconsmall;
    protected $_iconlarge;
    protected $_sets;
    protected $_secret;
    protected $_server;
    protected $_owner;

    public function import ($data)
    {
        $this->setId($data['id'])
             ->setTitle($data['title'])
             ->setDescription($data['description'])
             ->setIconSmall($data['iconsmall'])
             ->setIconLarge($data['iconlarge']);

        if (isset($data['set'])) {
            $this->_sets = array();
            foreach ($data['set'] as $item) {
                $set = $this->getBroker()->factory('set');
                $set->import($item);
                $this->_sets[] = $set;
            }
        }

        return $this;
    }

    public function getTree (Sozfo_Service_Flickr_User $user = null)
    {
        if (null === $user) {
            $options = array('user_id' => $this->getOwner()->getId());
        } else {
            $options = array('user_id' => $user->getId());
        }
        $response = $this->_request('collections.getTree', $options)->collections;

        $tree = array();
        foreach ($response['collection'] as $item) {
            $collection = $this->getBroker()->factory('collection');
            if (null !== $user) {
                $item = array_merge($item, array('owner' => $user));
            }
            $collection->import($item);
            $tree[] = $collection;
        }
        return $tree;
    }

    public function setTitle ($title)
    {
        $this->_title = (string) $title;
        return $this;
    }

    public function getTitle ()
    {
        if (null === $this->_title) {
            $this->_loadInfo();
        }
        return $this->_title;
    }

    public function setDescription ($description)
    {
        $this->_description = (string) $description;
        return $this;
    }

    public function getDescription ()
    {
        if (null === $this->_description) {
            $this->_loadInfo();
        }
        return $this->_description;
    }

    public function setIconSmall ($icon)
    {
        $this->_iconsmall = (string) $icon;
        return $this;
    }

    public function getIconSmall ()
    {
        if (null === $this->_iconsmall) {
            $this->_loadInfo();
        }
        return $this->_iconsmall;
    }

    public function setIconLarge ($icon)
    {
        $this->_iconlarge = (string) $icon;
        return $this;
    }

    public function getIconLarge ()
    {
        if (null === $this->_iconlarge) {
            $this->_loadInfo();
        }
        return $this->_iconlarge;
    }

    public function setSets (array $sets)
    {
        $this->_sets = $sets;
        return $this;
    }

    public function getSets ()
    {
        if (null === $this->_sets) {
            $tree = $this->getTree($this->getOwner());
            foreach($tree as $collection){
                if( $collection->getId() == $this->getId()){
                    $this->setSets($collection->getSets());
                    break;
                }
            }
        }
        return $this->_sets;
    }

    public function setSecret ($secret)
    {
        $this->_secret = (int) $secret;
        return $this;
    }

    public function getSecret ()
    {
        if (null === $this->_secret) {
            $this->_loadInfo();
        }
        return $this->_secret;
    }

    public function setServer ($server)
    {
        $this->_server = (int) $server;
        return $this;
    }

    public function getServer ()
    {
        if (null === $this->_server) {
            $this->_loadInfo();
        }
        return $this->_server;
    }

    public function setOwner (Sozfo_Service_Flickr_User $owner)
    {
        $this->_owner = $owner;
        return $this;
    }

    public function getOwner ()
    {
        return $this->_owner;
    }

    protected function _loadInfo ()
    {
        $response = $this->_request('collections.getInfo', array('collection_id' => $this->getId()))->collection;
        $this->import($response);
    }
}