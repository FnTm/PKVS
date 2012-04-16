<?php
class Admin_Form_ApmekletibaTips_Pievienot extends Zend_Form
{

    public function __construct($option = null)
    {
        $parents = $option;
        $option = null;
        /**@todo Pievienot faila augšupielādi */
        parent::__construct($option);
        $this->setName("ApmekletibaTipsPievienotForm");
        $this->addElement($this->createElement('text', 'apmekletibaTipsTitle')->setLabel("Apmeklētības tipa nosaukums")
            ->setRequired(true));


        $this->addElement($this->createElement('submit', 'submit')->setLabel('Pievienot'));
    }
}