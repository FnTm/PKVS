<?php
class Sozfo_Service_Flickr_Set extends Sozfo_Service_Flickr_Abstract
{
    protected $_primary;
    protected $_title;
    protected $_description;
    protected $_owner;
    protected $_photos;
    protected $_count = array('photos' => 0,
                              'videos' => 0);

    public function import ($data)
    {
        $this->setId($data['id'])
             ->setTitle($data['title'])
             ->setDescription($data['description']);

        if (isset($data['primary'])) {
            $this->setPrimary($this->getBroker()->factory('photo',
                                                           $data['primary']));
        }
        if (isset($data['photos'])) {
            $this->_count['photos'] = $data['photos'];
        }
        if (isset($data['videos'])) {
            $this->_count['videos'] = $data['videos'];
        }
        return $this;
    }

    public function getList (Sozfo_Service_Flickr_User $user)
    {
        $response = $this->_request('photosets.getList', array('user_id'=>$user->getId()))->photosets;
        $list = array();
        foreach ($response['photoset'] as $photoset) {
            $set = $this->getBroker()->factory('set');
            $set->import($photoset)
                ->setOwner($user);
            $list[] = $set;
        }
        return $list;
    }

    public function setPrimary (Sozfo_Service_Flickr_Photo $primary)
    {
        $this->_primary = $primary;
        return $this;
    }

    public function getPrimary ()
    {
        if (null === $this->_primary) {
            $this->_loadInfo();
        }
        return $this->_primary;
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

    public function setOwner (Sozfo_Service_Flickr_User $owner)
    {
        $this->_owner = $owner;
        return $this;
    }

    public function getOwner ()
    {
        return $this->_owner;
    }

    public function setPhotos (array $photos)
    {
        $this->_photos = $photos;
        return $this;
    }

    public function getPhotos ($privacy = null, $filter = null)
    {
        if (null === $this->_photos) {
            $options = array();
            if (null !== $privacy) {
                switch ($privacy) {
                    case 'private':
                        $options['privacy_filter'] = '5';
                        break;
                    case 'both':
                        $options['privacy_filter'] = '4';
                        break;
                    case 'family':
                        $options['privacy_filter'] = '3';
                        break;
                    case 'friends':
                        $options['privacy_filter'] = '2';
                        break;
                    default:
                        $options['privacy_filter'] = '1';
                }
            }

            if (null !== $filter) {
                switch ($filter) {
                    case 'photos':
                        $options['media'] = 'photos';
                        break;
                    case 'videos':
                        $options['media'] = 'videos';
                    default:
                        $options['media'] = 'all';
                }
            }

            $options['photoset_id'] = $this->getId();
            $response = $this->_request('photosets.getPhotos', $options)->photoset;
            $photos = array();
            foreach ($response['photo'] as $item) {
                $photo = $this->getBroker()->factory('photo');
                $photo->import($item);
                $photos[] = $photo;
            }
            $this->_photos = $photos;
        }

        return $this->_photos;
    }

    public function setCount (array $count)
    {
        $this->_count = $count;
        return $this;
    }

    public function getCount ()
    {
        if (null === $this->_count) {
            $this->_loadInfo();
        }
        return $this->_count;
    }

    public function getContext (Sozfo_Service_Flickr_Photo $photo)
    {
        $options = array(
            'photo_id'    => $photo->getId(),
            'photoset_id' => $this->getId());
        $response = $this->_request('photosets.getContext', $options);

        if(isset($response['prevphoto']['id'])){
            $prev = $this->getBroker()->factory('photo');
            $prev->import($response['prevphoto']);
        }
        if(isset($response['nextphoto']['id'])){
            $prev = $this->getBroker()->factory('photo');
            $next->import($response['nextphoto']);
        }
        return array(
            'prev' => $prev,
            'next' => $next);
    }

    protected function _loadInfo(){
        $response = $this->_request('photosets.getInfo', array('photoset_id'=>$this->getId()))->photoset;
        $this->import($response);
    }
}