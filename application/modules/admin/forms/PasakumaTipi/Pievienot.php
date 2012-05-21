<?php
/** Contains event type adding form
 * @author Janis Peisenieks
 * @package Pasakumi
 * @subpackage Admin
 */
/**
 * Form for adding event types
 * @author Janis Peisenieks
 * @package Pasakumi
 * @subpackage Admin
 */
class Admin_Form_PasakumaTipi_Pievienot extends Zend_Form
{

    /**
     * Constructs the form and adds the fields. Initialization
     * @param null|array $option array of paren-elemtns
     */
    public function __construct($option = null)
    {
        //Transfer the options to another variable, to resolve problems with parent, when passing data
        $parents = $option;
        $option = null;
        parent::__construct($option);
        //Set the form name
        $this->setName("PasakumaTipiPievienotForm");
        //Set up the elements and their atributes
        $this->addElement($this->createElement('text', 'typeTitle')->setLabel("Pasākuma tipa nosaukums")
            ->setRequired(true));
        $this->addElement($this->createElement('textarea', 'typeDescription')->setLabel('Pasākuma tipa apraksts')
            ->setRequired(true));
        $this->addElement($typeParent = $this->createElement('select', 'typeParent')->setLabel('Vecāk-tips')
            ->setRequired(true));
        $this->addElement($this->createElement('submit', 'submit')->setLabel('Pievienot'));
        //Add a static element to denote a single parent element
        $typeParent->addMultiOption(0, "Virskategorija");
        //Add the parent elements to the form
        if (!is_null($parents)) {
            foreach ($parents as $parent) {
                $typeParent->addMultiOption($parent->typeId, $parent->typeTitle);
            }
        }


    }
}