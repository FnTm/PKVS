<?php
class Sozfo_Service_Flickr_Tag extends Sozfo_Service_Flickr_Abstract
{
    protected $_owner;
    protected $_name;
    protected $_rawName;
    protected $_related;

    public function import ($data)
    {
        $this->setId($data['id'])
             ->setName($data['name'])
             ->setRawname($data['rawname']);

        if (isset($data['author'])) {
            $this->setOwner($this->getBroker()->factory('user', $data['author']));
        }
        return $this;
    }

    public function setOwner (Sozfo_Service_Flick_User $owner)
    {
        $this->_owner = $owner;
    }

    public function getOwner ()
    {
        return $this->_owner;
    }

    public function setName ($name)
    {
        $this->_name = (string) $name;
        return $this;
    }

    public function getName ()
    {
        return $this->_name;
    }

    public function setRawName ($rawName)
    {
        $this->_rawName = (string) $rawName;
        return $this;
    }

    public function getRawName ()
    {
        return $this->_rawName;
    }

    public function getRelated ()
    {
        if (null === $this->_related) {
            $options['tag'] = $this->getName();
            $response = $this->_request('tags.getRelated', $options)->tags;
            $this->_related = array();
            foreach ($response['tag'] as $item) {
                $tag= $this->getBroker()->factory('tag');
                $tag->setName($item);
                $this->_related[] = $tag;
            }
        }
        return $this->_related;
    }
}