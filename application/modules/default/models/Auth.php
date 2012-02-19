<?php
/**
 * User: Janis
 * Date: 11.26.12
 * Time: 21:59
 */
 
class Model_Auth {

    public function getAuthAdapter(){

        $authAdapter = new Zend_Auth_Adapter_DbTable(
                Zend_Db_Table::getDefaultAdapter());
                $authAdapter->setTableName('users')->setIdentityColumn(
               'username')->setCredentialColumn('password')->setCredentialTreatment('MD5(?)');
                return $authAdapter;
    }
}
