<?php
class Admin_Form_PasakumaTipi_Pievienot extends Zend_Form
{

    public function __construct($option = null)
    {
        $parents = $option;
        $option = null;
        /**@todo Pievienot faila augšupielādi */
        parent::__construct($option);
        $this->setName("TurniriPievienotForm");
        $this->addElement($this->createElement('text', 'typeTitle')->setLabel("Pasākuma tipa nosaukums")->setRequired(true));
        $this->addElement($this->createElement('textarea', 'typeDescription')->setLabel('Pasākuma tipa apraksts'));
        /**@todo pievienot jquery lauka tūli */
        $this->addElement($typeParent = $this->createElement('select', 'typeParent')->setLabel('Vecāk-tips'));
        $typeParent->addMultiOption(0, "Virskategorija");
        if (!is_null($parents)) {
            foreach ($parents as $parent) {
                $typeParent->addMultiOption($parent->typeId, $parent->typeTitle);
            }
        }

        $this->addElement($this->createElement('submit', 'submit')->setLabel('Pievienot'));
    }
}