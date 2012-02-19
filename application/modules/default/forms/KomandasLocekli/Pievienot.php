<?php
class Form_KomandasLocekli_Pievienot extends Zend_Form
{

    public function __construct($option = null)
    {

        parent::__construct($option);
        $this->setName("komandasId");
        $this->addElement($this->createElement('text','hash')->setLabel("Komandas Identifikators")->setRequired(true));

        $this->addElement($this->createElement('submit','submit')->setLabel('Pievienoties'));
    }
}