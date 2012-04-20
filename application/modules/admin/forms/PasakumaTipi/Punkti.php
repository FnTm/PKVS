<?php
/**
 * Created by JetBrains PhpStorm.
 * User: Janis
 * Date: 12.18.4
 * Time: 23:47
 * To change this template use File | Settings | File Templates.
 */
class Admin_Form_PasakumaTipi_Punkti extends JP_Form
{
    const TYPE_NAMES = "typeNames";
    const KEY_NAME="type_";

    public function __construct($config = array())
    {
        $types = array();
        if (isset($config[self::TYPE_NAMES])) {
            $types = $config[self::TYPE_NAMES];
            unset($config[self::TYPE_NAMES]);
        }
        parent::__construct($config);
        foreach ($types as $value) {
            //TODO pievienot regexp tikai uz integeriem
            $this->addElement($this->createElement('text', self::KEY_NAME . $value['apmekletibaTipsId'])->setLabel($value['apmekletibaTipsTitle'])->setRequired(true)->addValidator(new Zend_Validate_Int()));
        }
        $this->addElement($this->createElement('submit', 'submit')->setLabel('Pievienot'));

    }

}
