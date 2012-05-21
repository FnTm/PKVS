<?php
/** Contains event adding form
 * @author Janis Peisenieks
 * @package Pasakumi
 * @subpackage Admin
 */
/**
 * Form for adding events
 * @author Janis Peisenieks
 * @package Pasakumi
 * @subpackage Admin
 */
class Admin_Form_Pasakumi_Pievienot extends JP_Form
{

    /**
     * Seting up the form and its fields
     * @param null $option
     */
    public function __construct($option = null)
    {
        $categories = $option;
        $option = null;
        //Pass the data back to the parent
        parent::__construct($option);
        $this->setName("PasakumiPievienotForm");
        //Add all the necessary elements
        $this->addElement($this->createElement('text', 'pasakumsTitle')->setLabel("Pasākuma nosaukums")->setRequired(true));
        $this->addElement($this->createElement('textarea', 'pasakumsDescription')->setLabel('Pasākuma apraksts')->setRequired(true));
        $this->addElement($typeParent = $this->createElement('select', 'pasakumsCategory')->setLabel('Pasākuma kategorija')->setRequired(true));
        $this->addElement($this->createElement('text', 'pasakumsLocation')->setLabel("Pasākuma norises vieta")->setRequired(true));

        $this->addElement($this->createElement('dateTime', 'pasakumsTime')->setLabel('Pasākuma norises datums un laiks')->setRequired(true));

        $this->addElement($this->createElement('submit', 'submit')->setLabel('Pievienot'));
        //Itereate throught categories and add them to the select element
        if (!is_null($categories)) {
            foreach ($categories as $category) {
                $typeParent->addMultiOption($category->typeId, $category->typeTitle);
            }
        }
    }
}