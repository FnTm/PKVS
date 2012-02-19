<?php
class Sozfo_Service_Flickr_Group extends Sozfo_Service_Flickr_Abstract
{
    protected $_name;
    protected $_description;
    protected $_memberCount;
    protected $_members;
    protected $_photos;

    public function import ($data)
    {
        $this->setName($data['name']);

        if (isset($data['id'])) {
            $this->setId($data['id']);
        } elseif (isset($data['nsid'])) {
            $this->setId($data['nsid']);
        }

        if (isset($data['description'])) {
            $this->setDescription($data['description'])
                 ->setMemberCount($data['members']);
        }

        return $this;
    }

    public function getList ($page = 1, $perPage = 400)
    {
        $options = array(
            'page'      => $page,
            'per_page'  => $perPage
        );
        $response = $this->_request('groups.pools.getGroups', $options)->groups;
        $groups = array();
        foreach ($response['group'] as $data) {
            $group = $this->getBroker()->factory('group');
            $group->import($data);
            $groups[] = $group;
        }
        return $groups;
    }

    public function setName ($name)
    {
        $this->_name = (string) $name;
        return $this;
    }

    public function getName ()
    {
        if (null === $this->_name) {
            $this->_loadInfo();
        }
        return $this->_name;
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

    public function setMemberCount ($count)
    {
        $this->_memberCount = (int) $count;
        return $this;
    }

    public function getMemberCount ()
    {
        if (null === $this->_memberCount) {
            $this->_loadInfo();
        }
        return $this->_memberCount;
    }

    public function setMembers (array $members)
    {
        $this->_members = $members;
        return $this;
    }

    public function getMembers ($page = 1, $perPage = 100)
    {
        if (null === $this->_members) {
            if (intval($perPage) > 500) {
                $perPage = 500;
            }
            $options = array(
                'group_id'  => $this->getId(),
                'page'      => $page,
                'per_page'  => $perPage
            );

            $response = $this->_request('groups.members.getList', $options)->members;
            $members = array();
            foreach ($response['member'] as $item) {
                $info   = array(
                    'id'       => $item['nsid'],
                    'username' => $item['username']
                );
                $member = $this->getBroker()->factory('user');
                $member->import($info);
                $members[] = $member;
            }
            $this->_members = $members;
        }

        return $this->_members;
    }

    public function setPhotos (array $photos)
    {
        $this->_photos = $photos;
        return $this;
    }

    public function getPhotos ($tag = null, Sozfo_Service_Flickr_User $user = null, $page = 1, $perPage = 100)
    {
        if (null === $this->_photos) {
            $options = array(
                'page'      => $page,
                'per_page'  => $perPage
            );
            if (null !== $tag) {
                $options['tags'] = $tag;
            }
            if (null !== $user) {
                $options['user_id'] = $user->getId();
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

    public function getContext (Sozfo_Service_Flickr_Photo $photo)
    {
        $options = array(
            'photo_id' => $photo->getId(),
            'group_id' => $this->getId());
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
        $response = $this->_request('groups.getInfo', array('group_id'=>$this->getId()))->group;
        $this->import($response);
    }
}