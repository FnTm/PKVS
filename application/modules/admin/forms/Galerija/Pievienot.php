<?php
class Form_Galerija_Pievienot extends Zend_Form
{

    public function __construct($option = null)
    {
        parent::__construct($option);
        $this->setName("GalerijaPievienotForm");
        $this->addElement($this->createElement('text','title')->setLabel("Galerijas nosaukums")->setRequired(true));
        $this->addElement($this->createElement('textarea','description')->setLabel('Galerijas apraksts'));
        $this->addElement($this->createElement('submit','submit')->setLabel('Pievienot'));
    }
}