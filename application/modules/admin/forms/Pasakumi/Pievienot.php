<?php
class Admin_Form_Pasakumi_Pievienot extends JP_Form
{

    public function __construct($option = null)
    {
        $parents = $option;
        $option = null;
        /**@todo Pievienot faila augšupielādi */
        parent::__construct($option);
        $this->setName("PasakumiPievienotForm");
        $this->addElement($this->createElement('text', 'pasakumsTitle')->setLabel("Pasākuma nosaukums")->setRequired(true));
        $this->addElement($this->createElement('textarea', 'pasakumsDescription')->setLabel('Pasākuma apraksts'));
        $this->addElement($typeParent = $this->createElement('select', 'pasakumsCategory')->setLabel('Pasākuma kategorija'));
        /**@todo pievienot jquery lauka tūli */
        $this->addElement($this->createElement('text', 'pasakumsLocation')->setLabel("Pasākuma norises vieta")->setRequired(true));

        $this->addElement($this->createElement('dateTime', 'pasakumsTime')->setLabel('Pasākuma norises datums un laiks'));
        $this->addElement($this->createElement('file', 'pasakumsImage')->setLabel('Pasākuma logo'));

        $this->addElement($this->createElement('submit', 'submit')->setLabel('Pievienot'));
        if (!is_null($parents)) {
            foreach ($parents as $parent) {
                $typeParent->addMultiOption($parent->typeId, $parent->typeTitle);
            }
        }
    }
}