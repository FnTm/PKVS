<?php
// File: library/Sozfo/Service/Tumblr.php
//
class Sozfo_Service_Tumblr
{
    const format = 'http://?.tumblr.com/api/read';
    const defaultNumberOfPosts = 10;

    protected static $_cache;

    public static function setCache(Zend_Cache $cache)
    {
        self::$_cache = $cache;
    }

    public static function getCache()
    {
        return self::$_cache;
    }

    public static function hasCache()
    {
        if (isset(self::$_cache)) return true;
        else return false;
    }

    public static function getPosts( $user, $numberOfPosts = null )
    {
        if (!isset($numberOfPosts) || !is_int($numberOfPosts)) $numberOfPosts = self::defaultNumberOfPosts;
        if (!is_string($user)) throw new Sozfo_Service_Tumblr_Exception('User of Tumblr must be in string format');

        $path = str_replace('?',$user,self::format);
        $config = self::loadXml($path);

        $entries = array();
        foreach($config->posts->children() as $post){
            $entries[] = Sozfo_Service_Tumblr_Post::import($post);
        }
        $entries = array_slice( $entries, 0, self::defaultNumberOfPosts );
        return $entries;
    }

    private static function loadXml($path)
    {
        if (self::hasCache()) {
            $cache = self::getCache();
            //Check if cache has the file
            #TODO: Remove the false statement to be able to have cache
            if (false && $cache->hasFile($path)) {
                return self::loadXmlFromCache($path);
            } else {
                return self::loadXmlFromTumblr($path);
            }
        } else {
            return self::loadXmlFromTumblr($path);
        }
    }

    private static function loadXmlFromCache($path)
    {

    }

    private static function loadXmlFromTumblr($path)
    {
        $client = new Zend_Http_Client($path);
        $response = $client->request();
        $xml = new SimpleXMLElement($response->getBody());
        return $xml;
    }
}