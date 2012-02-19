<?php
class Sozfo_Service_Flickr_Photo extends Sozfo_Service_Flickr_Abstract
{
    const format = 'http://farm%d.static.flickr.com/%d/%s_%s%s.%s';

    protected $_id;
    protected $_title;
    protected $_description;
    protected $_sets = array();
    protected $_tags;
    protected $_owner;
    protected $_media;
    protected $_thumb;
    protected $_url;
    protected $_secret;
    protected $_originalsecret;
    protected $_originalformat;
    protected $_farm;
    protected $_server;

    protected $_sizes = array(
            'square' => '_s',
            'thumbnail' => '_t',
            'small' => '_m',
            'medium' => '',
            'large' => '_b',
            'original' => '_o' );

    public function location ($size = 'medium')
    {
        if (!$this->getFarm()) {
            $this->loadInfo();
        }

        $size = (array_key_exists($size, $this->_sizes)) ? $size : 'medium';
        $format = ($size == 'original') ? $this->getOriginalFormat() : 'jpg';
        $secret = ($size == 'original') ? $this->getOriginalSecret() : $this->getSecret();
        $url = sprintf(self::format,
                       $this->getFarm(),
                       $this->getServer(),
                       $this->getId(),
                       $secret,
                       $this->_sizes[$size],
                       $format);
        return $url;
    }

    public function import ($data)
    {
        $this->setId($data['id'])
             ->setTitle($data['title'])
             ->setSecret($data['secret'])
             ->setServer($data['server'])
             ->setFarm($data['farm']);

        if (isset($data['description'])) {
            $this->setDescription($data['description']);
        }
        if (isset($data['owner'])) {
            $this->setOwner($this->getBroker()->factory('user', $data['owner']));
        }
        return $this;
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

    public function setSets (array $sets)
    {
        $this->_sets = $sets;
        return $this;
    }

    public function appendSet (Sozfo_Service_Flickr_Set $set)
    {
        $this->_sets[] = $set;
        return $this;
    }

    public function getSets ()
    {
        if (null === $this->_sets) {
            $response = $this->_request('photos.getAllContexts', array('photo_id'=>$this->getId()));
            $sets = array();
            foreach ($response['set'] as $set) {
                $set = $this->getBroker()->factory('set');
                $set->import($set);
                $sets[] = $set;
            }
            $this->setSets($sets);
        }
        return $this->_sets;
    }

    public function setOwner (Sozfo_Service_Flickr_User $owner)
    {
        $this->_owner = $owner;
        return $this;
    }

    public function getOwner ()
    {
        if (null === $this->_owner) {
            $this->_loadInfo();
        }
        return $this->_owner;
    }

    public function setMedia ($media)
    {
        $this->_media = (string) $media;
        return $this;
    }

    public function getMedia ()
    {
        if (null === $this->_media) {
            $this->_loadInfo();
        }
        return $this->_media;
    }

    public function setThumb ($thumb)
    {
        $this->_thumb = (string) $thumb;
        return $this;
    }

    public function getThumb ()
    {
        if (null === $this->_thumb) {
            $this->_loadInfo();
        }
        return $this->_thumb;
    }

    public function setUrl ($url)
    {
        $this->_url = (string) $url;
        return $this;
    }

    public function getUrl ()
    {
        if (null === $this->_url) {
            $this->_loadInfo();
        }
        return $this->_url;
    }

    public function setSecret ($secret)
    {
        $this->_secret = (string) $secret;
        return $this;
    }

    public function getSecret ()
    {
        if (null === $this->_secret) {
            $this->_loadInfo();
        }
        return $this->_secret;
    }

    public function setOriginalSecret ($secret)
    {
        $this->_originalsecret = (string) $secret;
        return $this;
    }

    public function getOriginalSecret ()
    {
        if (null === $this->_originalsecret) {
            $this->_loadInfo();
        }
        return $this->__originalsecret;
    }

    public function setFarm ($farm)
    {
        $this->_farm = (int) $farm;
        return $this;
    }

    public function getFarm ()
    {
        if (null === $this->_farm) {
            $this->_loadInfo();
        }
        return $this->_farm;
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

    public function getTags ()
    {
        if (null === $this->_tags) {
            $options['photo_id'] = $this->getId();
            $response = $this->_request('tags.getListPhoto', $options)->photo;
            $this->_tags = array();
            foreach ($response['tags']['tag'] as $item) {
                $tag= $this->getBroker()->factory('tag');
                $tag->setName($item);
                $this->_tags[] = $tag;
            }
        }
        return $this->_tags;
    }

    protected function _loadInfo()
    {
        $response = $this->_request('photos.getInfo', array('photo_id'=>$this->getId()))->photo;
        $this->import($response);
    }
}