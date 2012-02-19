<?php class Form_Lietotajs_Profils extends Zend_Form{
    
public function __construct($option = null) {
        parent::__construct($option);

        $this->setName('profils');

        $password = new Zend_Form_Element_Password('password');
        $password->setLabel('Parole:')
                 ->setRequired(true);
         $email=new Zend_Form_Element_Text('email');
        $email->setLabel('E-pasts:');
        $email->addValidator(new Zend_Validate_EmailAddress());
        $email->setRequired(true);
        $login = new Zend_Form_Element_Submit('save');
        $login->setLabel('Saglabāt');

        $this->addElements(array($password,$email, $login));
        $this->setMethod('post');
    }

}