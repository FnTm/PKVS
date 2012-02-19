<?php
class Form_Komanda_Pievienot extends Zend_Form
{

    public function __construct($option = null)
    {
        /**@todo Pievienot faila augšupielādi */
        parent::__construct($option);
        $this->setName("KomandaPievienotForm");
        $this->addElement($this->createElement('text','teamName')->setLabel("Komandas nosaukums")->setRequired(true));
        $this->addElement($this->createElement('submit','submit')->setLabel('Pievienot'));
    }
}