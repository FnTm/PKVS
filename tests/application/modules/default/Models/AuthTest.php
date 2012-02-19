<?php
/**
 * User: Janis
 * Date: 12.19.3
 * Time: 18:17
 */
require_once '../application/modules/default/models/Auth.php';
class AuthTest extends ControllerTestCase{

public function testIsAdapterInterface(){
    $authModel=new Model_Auth();
    $this->assertTrue($authModel->getAuthAdapter() instanceof Zend_Auth_Adapter_Interface );
}
}
