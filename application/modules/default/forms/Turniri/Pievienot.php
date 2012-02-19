<?php
class Form_Turniri_Pievienot extends Zend_Form
{

    public function __construct($option = null)
    {
        /**@todo Pievienot faila augšupielādi */
        parent::__construct($option);
        $this->setName("TurniriPievienotForm");
        $this->addElement($this->createElement('text','title')->setLabel("Turnīra nosaukums")->setRequired(true));
        $this->addElement($this->createElement('textarea','description')->setLabel('Turnīra apraksts'));
        /**@todo pievienot jquery lauka tūli */
        $this->addElement($this->createElement('text','time')->setLabel('Turnīra norises datums un laiks'));
        $this->addElement($this->createElement('file','image')->setLabel('Turnīra logo')->setRequired(TRUE));

        $this->addElement($this->createElement('submit','submit')->setLabel('Pievienot'));
    }
}