<?php

class Model_Jaunumi_Kategorijas extends Zend_Db_Table_Abstract
{
    public $_name = "jaunumukategorijas";

    public function createCategory($data)
    {
        $data['kategorijaCreated'] = date("Y-m-d H:i:s");
        $this->insert($data);
    }

    public function updateCategory($categoryId, $data)
    {
        $this->update($data, $this->getAdapter()->quoteInto("kategorijaId=?", $categoryId));
    }

    /**Atgriež vienu kategoriju kā masīvu, vai null vērtību, ja šāda kategorija neeksistē.
     * @param $categoryId
     * @return null|array
     */
    public function getCategory($categoryId)
    {
        $row = $this->fetchRow($this->select()->where("kategorijaId=?", $categoryId));
        if ($row != null) {
            $row = $row->toArray();
        }
        return $row;
    }

    public function getAll()
    {
        return $this->fetchAll()->toArray();
    }

    public function deleteCategory($categoryId)
    {
        $this->delete($this->getAdapter()->quoteInto("kategorijaId=?", $categoryId));
    }
}
