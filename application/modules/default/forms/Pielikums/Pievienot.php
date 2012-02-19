<?php
class Form_Pielikums_Pievienot extends Zend_Form
{

    public function __construct($option = null)
    {
        /**@todo Pievienot faila augšupielādi */
        parent::__construct($option);
        $this->setName("PielikumsPievienotForm");
        $this->addElement($this->createElement('text','title')->setLabel("Pielikuma nosaukums")->setRequired(true));
        $this->addElement($this->createElement('textarea','description')->setLabel('Pielikuma apraksts'));
        $this->addElement($this->createElement('file','file')->setLabel('Pielikuma fails')->setRequired(TRUE));

        $this->addElement($this->createElement('submit','submit')->setLabel('Pievienot'));
    }
}