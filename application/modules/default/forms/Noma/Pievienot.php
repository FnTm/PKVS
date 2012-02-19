<?php
class Form_Noma_Pievienot extends Zend_Form
{

    public function __construct($option = null)
    {
        parent::__construct($option);
        $this->setName("JaunumiPievienotForm");
                $this->addElement($this->createElement('textarea','description')->setLabel('Nomas piedāvājuma apraksts'));
        $this->addElement($this->createElement('text','place')->setLabel("Adrese")->setRequired(true));
        $this->addElement($this->createElement('text','link')->setLabel("Mājaslapas adrese"));
        $this->addElement($this->createElement('text','phone')->setLabel("Nomas tālruņa numurs")->setRequired(true));
        $this->addElement($this->createElement('text','email')->setLabel("Nomas e-pasta adrese")->setRequired(true));
        $this->addElement($this->createElement('submit','submit')->setLabel('Pievienot'));
    }
}