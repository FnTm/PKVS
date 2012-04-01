<?php
class Admin_Form_PasakumaTipi_Pievienot extends Zend_Form
{

    public function __construct($option = null)
    {
        $parents=$option;
        $parents[0]="Virskategorija";

        $option=null;
        /**@todo Pievienot faila augšupielādi */
        parent::__construct($option);
        $this->setName("TurniriPievienotForm");
        $this->addElement($this->createElement('text','typeTitle')->setLabel("Pasākuma tipa nosaukums")->setRequired(true));
        $this->addElement($this->createElement('textarea','typeDescription')->setLabel('Pasākuma tipa apraksts'));
        /**@todo pievienot jquery lauka tūli */
        $this->addElement($typeParent=$this->createElement('select','typeParent')->setLabel('Vecāk-tips'));

        if(!is_null($parents) && is_array($parents)){
            sort($parents);
            foreach($parents as $id=>$title){
                $typeParent->addMultiOption($id,$title);
            }
        }

        $this->addElement($this->createElement('submit','submit')->setLabel('Pievienot'));
    }
}