<?php
/**
 * User: Janis
 * Date: 12.11.1
 * Time: 21:13
 */

class Form_Bildes_Pievienot extends Zend_Form
{
    public function __construct($option = array())
    {
        parent::__construct($option);
        $this->setName('PievienotBildi');
        $this->addElement($this->createElement('file', 'picture')->setLabel("Bilde")->setRequired(true));
        $this->addElement($this->createElement('text', 'title')->setLabel("Bildes nosaukums")->setRequired(true));
        $this->addElement($this->createElement('textarea', 'description')->setLabel('Bildes apraksts'));
        $this->addElement($this->createElement('submit', 'submit')->setLabel('Pievienot'));
    }

}
