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

}