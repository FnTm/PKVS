<?php
/**

 * User: Janis
 * Date: 12.9.1
 * Time: 10:03
 */

class JP_Controller_Plugin_Language
    extends Zend_Controller_Plugin_Abstract
{
    public function routeShutdown(Zend_Controller_Request_Abstract $request)
    {


        $locale = new Zend_Locale();


        $options = array('scan' => Zend_Translate::LOCALE_FILENAME);
        $translate = new Zend_Translate('array', APPLICATION_PATH . '/translations/', 'auto', $options);

        $requestParams = $request->getParams();

            Zend_Registry::set('Zend_Locale', $locale);
            Zend_Registry::set('Zend_Translate', $translate);
        if (isset($_COOKIE['firsttime']) && $_COOKIE['firsttime'] = true) {
          Zend_Registry::set('firstlang', $language = $locale->getLanguage());
            setcookie('firstisavailable', $translate->isAvailable($locale->getLanguage()), 20, '/');


        }
        else {
            $language = (isset($requestParams['language'])) ? $requestParams['language'] : false;
        }
        if ($language == false) {
            $language = ($translate->isAvailable($locale->getLanguage())) ? $locale->getLanguage() : 'en';
        }
        if (!$translate->isAvailable($language)) {
            throw new Zend_Controller_Action_Exception('This page, in this language doesn\'t exist', 404);
        } else {
            //var_dump($_COOKIE);
            $locale->setLocale($language);
            $translate->setLocale($locale);

            Zend_Form::setDefaultTranslator($translate);
            setcookie('lang', $locale->getLanguage(), null, '/');
            Zend_Registry::set('lang', $language);
            Zend_Registry::set('Zend_Locale', $locale);
            Zend_Registry::set('Zend_Translate', $translate);
        }
    }
}
