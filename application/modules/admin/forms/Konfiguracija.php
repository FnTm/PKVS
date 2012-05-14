<?php
/**
 * Created by JetBrains PhpStorm.
 * User: Janis
 * Date: 12.2.5
 * Time: 07:34
 * To change this template use File | Settings | File Templates.
 */
class Admin_Form_Konfiguracija extends JP_Form
{
    public function __construct($options = null)
    {

        parent::__construct($options);
        $this->addElement($this->createElement('text', 'collectiveTitle')->setLabel("Kolektīva nosaukums")->setRequired(true));
        $this->addElement($this->createElement('file', 'collectiveLogo')->setLabel("Kolektīva logo"));

        $this->addElement($this->createElement('submit', 'submit')->setLabel('Pievienot'));
    }
}
