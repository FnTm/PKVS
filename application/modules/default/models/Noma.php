<?php
/**
 * User: Janis
 * Date: 11.12.12
 * Time: 09:37
 */
 
class Model_Noma extends Zend_Db_Table_Abstract{
    public $_name="rent";
    public function createNoma($data){
       return $this->insert($data);
    
    }
    public function getNoma($id){
        $select=$this->select()->where('rentId=?',$id);
        $result=$this->fetchRow($select);
        if($result===NULL){
            /** @todo Add logging to email */
            throw new Exception('No row by this Id has been found');
        }
        else{
            return $result->toArray();
        }
    }
    public function updateNoma($data,$id){
        $where = $this->getAdapter()->quoteInto('rentId = ?', $id);
        $this->update($data, $where);
    }
    public function deleteNoma($id){
        /* @todo delete galleries, attachments and news along with tournament*/
        $where = $this->getAdapter()->quoteInto("rentId = ?", $id);
        $this->delete($where);
    }
    public function get_all_Noma(){
        return $this->fetchAll();
    }

}
