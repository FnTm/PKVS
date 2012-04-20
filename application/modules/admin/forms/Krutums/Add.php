<?php
/**
 * Created by JetBrains PhpStorm.
 * User: Janis
 * Date: 12.20.4
 * Time: 07:49
 * To change this template use File | Settings | File Templates.
 */
class Admin_Form_Krutums_Add extends JP_Form
{
    public function __construct($config = array())
    {
        $users = $config;
        $config = null;
        parent::__construct($config);

        $this->addElement($userElement=$this->createElement('select', 'krutumsUserId')->setLabel('Lietotājs'));
        foreach($users as $user){
            $userElement->addMultiOption($user['userId'],$user['name']);
        }
        $this->addElement($this->createElement('text', 'krutumsValue')->setLabel("Punktu skaits")->setRequired(true)->addValidator(new Zend_Validate_Int()));
        $this->addElement($this->createElement('text', 'krutumsNote')->setLabel('Pamatojums')->setRequired(true));
        $this->addElement($this->createElement('submit', 'submit')->setLabel('Pievienot'));

    }

}
