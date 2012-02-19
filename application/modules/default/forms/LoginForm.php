<?php
class Form_LoginForm extends Zend_Form 
{
    public function __construct($option = null) {
        parent::__construct($option);		
        $this->setName('login');
       
        $username = new Zend_Form_Element_Text('username');
        $username->setLabel('Lietotājvārds:')
                 ->setRequired();
        
                 
        $password = new Zend_Form_Element_Password('password');
        $password->setLabel('Parole:')
                 ->setRequired(true);
                 
        $login = new Zend_Form_Element_Submit('login');
        $login->setLabel('Login');
        
        $this->addElements(array($username, $password, $login));
        $this->setMethod('post');
        $this->setAction(Zend_Controller_Front::getInstance()->getBaseUrl().'/'.Zend_Registry::get('lang').'/authentication/login');
    }
}
