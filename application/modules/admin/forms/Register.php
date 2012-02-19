<?php
class Form_Register extends Zend_Form
{
    public function __construct($option = null) {
        parent::__construct($option);
        
        $this->setName('register');

        $username = new Zend_Form_Element_Text('username');
        $username->setLabel('Lietotājvārds:')
                 ->setRequired()->addValidator(new JP_Form_Validate_UsernameExists());
        $name=$this->createElement('text','name')->setLabel('Vārds, Uzvārds:')->setRequired(TRUE);
        $password = new Zend_Form_Element_Password('password');
        $password->setLabel('Parole:')
                 ->setRequired(true);
         $email=new Zend_Form_Element_Text('email');
        $email->setLabel('E-pasts:');
        $email->addValidator(new Zend_Validate_EmailAddress());
        $email->setRequired(true);
        $login = new Zend_Form_Element_Submit('register');
        $login->setLabel('Register');

        $this->addElements(array($name,$username, $password,$email, $login));
        $this->setMethod('post');
        $this->setAction(Zend_Controller_Front::getInstance()->getBaseUrl().'/authentication/register');
    }
}
