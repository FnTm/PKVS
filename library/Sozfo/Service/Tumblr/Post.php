<?php
// File: library/Sozfo/Service/Tumblr/Post.php
//
class Sozfo_Service_Tumblr_Post
{
    protected $_id;
    protected $_url;
    protected $_type;
    protected $_date;

    public static function import(SimpleXMLElement $xml)
    {
        $attr = $xml->attributes();
        $options = array(
            'id'   => $attr->id,
            'url'  => $attr->url,
            'type' => $attr->type,
            'date' => $attr->date
        );
        switch ($attr->type) {
            case 'photo':
                $options['caption'] = $xml->children()->{'photo-caption'};
                #FIXME: Now only the first, biggest, photo is copied
                $options['photos']  = $xml->children()->{'photo-url'};
                break;
            case 'link':
                $options['text']    = $xml->children()->{'link-text'};
                $options['url']     = $xml->children()->{'link-url'};
                break;
            case 'regular':
                $options['title']   = $xml->children()->{'regular-title'};
                $options['body']   = $xml->children()->{'regular-body'};
                break;
            default:
                throw new Exception('Type ' . $attr->type . ' not supported!');
        }
        $class = 'Sozfoo_Service_Tumblr_Post_' . ucfirst( $attr->type );
        return new $class($options);
    }

    public function __construct($options = null)
    {
        if (isset($options)) {
            $this->setOptions($options);
        }
    }

    public function __get($property)
    {
        if (isset($this->$property)) {
            return $this->$property;
        } else {
            $property = '_' . $property;
            if (isset($this->$property)) {
                return $this->$property;
            } else {
                return false;
            }
        }
    }

    public function setOptions(array $options)
    {
        foreach ($options as $option=>$value) {
            $option = '_' . $option;
            if (isset($option)) {
                $this->$option = (string) $value;
            }
        }
    }

    public function toArray()
    {
        $array = array();
        $properties = get_object_vars($this);
        foreach ( $properties as $key=>$value)
        {
            $key = substr($key,1);
            $array[$key] = $value;
        }
        return $array;
    }
}