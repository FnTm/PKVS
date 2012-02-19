<?php
class Form_Koncerti_Add extends Zend_Form
{

    public function __construct($option = null)
    {
        /**@todo Pievienot faila augšupielādi */
        parent::__construct($option);
        $this->setName("KoncertiAddForm");
        $this->addElement($this->createElement('text','title')->setLabel("Koncerta nosaukums")->setRequired(true));
        $this->addElement($this->createElement('textarea','description')->setLabel('Koncerta apraksts'));
        /**@todo pievienot jquery lauka tūli */
        $this->addElement($this->createElement('text','location')->setLabel("Koncerta nosaukums")->setRequired(true));
        $this->addElement($this->createElement('text','time')->setLabel('Koncerta norises datums un laiks'));
        $this->addElement($this->createElement('file','image')->setLabel('Koncerta logo')->setRequired(TRUE));

        $this->addElement($this->createElement('submit','submit')->setLabel('Pievienot'));
    }
}