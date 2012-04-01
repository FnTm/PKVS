<?php
/**
 * User: Janis
 * Date: 12.22.3
 * Time: 18:01
 */

/**
 * A model for all the Pasakumi Type actions.
 * @package Admin
 * @subpackage Pasakumi
 */
class Model_Pasakumi_Type extends Zend_Db_Table_Abstract
{
    public $_name = "pasakumiType";
    public $_rowClass = "Model_Pasakumi_Type_Row";

    public function getAll(){
        return $this->fetchAll();
    }
    public function createType($data){
        return $this->insert($data);
    }

}