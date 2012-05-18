<?php
date_default_timezone_set("Europe/Helsinki");
require_once 'Zend/Application.php';
require_once 'Zend/Test/PHPUnit/ControllerTestCase.php';
abstract class ControllerTestCase extends Zend_Test_PHPUnit_ControllerTestCase
{
    protected $application;
    public $elementData;
    public $simpleData;
    public function setUp ()
    {
        $this->bootstrap = array($this, 'appBootstrap');
        parent::setUp();
    }
    public function appBootstrap ()
    {
        $this->application = new Zend_Application(APPLICATION_ENV,
        APPLICATION_PATH . '/configs/application.ini');

        $this->application->bootstrap();

        $front = Zend_Controller_Front::getInstance();

        if ($front->getParam('bootstrap') === null) {
            $front->setParam('bootstrap', $this->application->getBootstrap());
        }
    }
    /**

    @param string $expectedCode
    @param string $message
     */
    public function assertResponseCode($expectedCode, $message = '') { $this->assertEquals($expectedCode, $this->getResponse()->getHttpResponseCode(), $message); }
    /**

    @param string $expectedUrl
    @param string $message
     */
    public function assertRedirectTo($expectedUrl, $message = '')
    {
        $headers = $this->getResponse()->getHeaders();
        $actualUrl = '';

        foreach($headers as $header) {
            if ($header['name'] === 'Location') { $actualUrl = $header['value']; }
        }

        $this->assertEquals($expectedUrl, $actualUrl, $message);
    }

}