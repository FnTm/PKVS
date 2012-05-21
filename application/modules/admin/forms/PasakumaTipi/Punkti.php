<?php
/** Contains event type point form
 * @author Janis Peisenieks
 * @package Pasakumi
 * @subpackage Admin
 */
/**
 * Form for adding event type points
 * @author Janis Peisenieks
 * @package Pasakumi
 * @subpackage Admin
 */
class Admin_Form_PasakumaTipi_Punkti extends JP_Form
{
    /**
     *Type name
     */
    const TYPE_NAMES = "typeNames";
    /**
     * Prefix for type
     */
    const KEY_NAME = "type_";

    /**
     * The types for which to generate fields
     * @param array $config
     */
    public function __construct($config = array())
    {
        //Determine the fields for which to generate the input field
        $types = array();
        if (isset($config[self::TYPE_NAMES])) {
            $types = $config[self::TYPE_NAMES];
            unset($config[self::TYPE_NAMES]);
        }
        parent::__construct($config);
        //Loop through the array of input fields
        foreach ($types as $value) {
            $this->addElement($this->createElement('text', self::KEY_NAME . $value['apmekletibaTipsId'])->setLabel($value['apmekletibaTipsTitle'])->setRequired(true)->addValidator(new Zend_Validate_Int()));
        }
        $this->addElement($this->createElement('submit', 'submit')->setLabel('Pievienot'));

    }

}
