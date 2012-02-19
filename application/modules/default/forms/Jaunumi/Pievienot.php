<?
class Form_Jaunumi_Pievienot extends Zend_Form
{

    public function __construct($option = null)
    {
        parent::__construct($option);
        $this->setName("JaunumiPievienotForm");
        $this->addElement($this->createElement('text','title')->setLabel("Jaunuma nosaukums")->setRequired(true));
        $this->addElement($this->createElement('textarea','content')->setLabel('Jaunuma apraksts'));
        $this->addElement($this->createElement('submit','submit')->setLabel('Pievienot'));
    }
}