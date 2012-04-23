<?
class Admin_Form_Jaunumi_Pievienot extends Zend_Form
{

    public function __construct($option = null)
    {
        parent::__construct($option);
        $this->setName("JaunumuKategorijasPievienotForm");
        $this->addElement($this->createElement('text','kategorijaTitle')->setLabel("Kategorijas nosaukums")->setRequired(true));
        $this->addElement($this->createElement('textarea','kategorijaDescription')->setLabel('Kategorijas apraksts'));
        $this->addElement($this->createElement('submit','submit')->setLabel('Pievienot'));
    }
}