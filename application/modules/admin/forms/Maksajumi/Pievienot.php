<?php
/**
 * Created by JetBrains PhpStorm.
 * User: Janis
 * Date: 12.28.4
 * Time: 18:06
 * To change this template use File | Settings | File Templates.
 */
class Admin_Form_Maksajumi_Pievienot extends JP_Form
{

    public function __construct($options = null)
    {
        $users = array();
        if (!is_null($options)) {
            $users = $options;
            $options = null;
        }
        parent::__construct($options);

        $this->addElement($this->createElement("text", 'maksajumsTitle')->setLabel("Maksājuma apraksts")->setRequired(true));
        $this->addElement($this->createElement("text", 'maksajumsValue')->setLabel("Maksājuma summa")->addValidator(new Zend_Validate_Float())->setRequired(true));
        $this->addElement($userSelect = $this->createElement("select", 'maksajumsUserId')->setLabel("Maksātājs")->setRequired(true));
        foreach ($users as $user) {
            $userSelect->addMultiOption($user['userId'], $user['name']);
        }
        $this->addElement($this->createElement("checkbox", 'maksajumsCompleted')->setLabel("Vai maksājums ir pabeigts?"));

        $this->addElement($this->createElement("submit", 'Apstiprināt'));
    }

}
