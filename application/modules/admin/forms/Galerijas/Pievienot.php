<?php
class Admin_Form_Galerijas_Pievienot extends JP_Form
{

    public function __construct($option = null)
    {

        /**@todo Pievienot faila augšupielādi */
        parent::__construct($option);
        $this->setName("PasakumiPievienotForm");
        $this->addElement($this->createElement('text', 'galleryTitle')->setLabel("Galerijas nosaukums")->setRequired(true));
        $this->addElement($this->createElement('textarea', 'galleryDescription')->setLabel('Galerijas apraksts'));


        $this->addElement($this->createElement('submit', 'submit')->setLabel('Pievienot'));

    }
}