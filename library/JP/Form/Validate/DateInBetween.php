<?php
class JP_Form_Validate_DateInBetween extends Zend_Validate_Abstract
{
    const DATE_LARGER_THAN_RANGE = 0x1;
    const DATE_SMALER_THAN_RANGE = 0x2;
    const VALUE_NOT_DATE = 0x3;
    public $options;
    public function __construct ($options)
    {
        $this->options = unserialize($options);
    }
    protected $_messageTemplates = array(
    self::DATE_LARGER_THAN_RANGE => 'Entered date is larger than the allowed range',
    self::DATE_SMALER_THAN_RANGE => 'Entered date is smaler than the allowed range',
    self::VALUE_NOT_DATE => 'Entered value is not a date');
    public function isValid ($value, $context = null)
    {
        //        var_dump($this->options);
        //        $subject = "3d-4m";
        //var_dump($matches);
//        var_dump(
//        date('Y-m-d H:i',
//        $this->getDateByRange($this->options['fromDate'])));
//        var_dump(
//        date('Y-m-d H:i',
//        $this->getDateByRange($this->options['toDate'])));
        //var_dump(strtotime($value));
//        //$this->_error(self::DATE_NOT_IN_RANGE);\
//        var_dump(strtotime($value));
//        var_dump($this->getDateByRange($this->options['toDate']));
        $return = true;
        if (strtotime($value) == false) {
            var_dump('false');
            $this->_error(self::VALUE_NOT_DATE);
            return false;
        }
            if (strtotime($value) < $this->getDateByRange(
            $this->options['fromDate'])) {
                $this->_error(self::DATE_SMALER_THAN_RANGE);
                //var_dump('smaller');
                return false;

            }
                if (strtotime($value) >
                 $this->getDateByRange($this->options['toDate'])) {
                    // var_dump('bigger');
                    $this->_error(self::DATE_LARGER_THAN_RANGE);
                    $return = false;
                }
        return $return;
    }
    public function getDateByRange ($value)
    {
        $pattern = '/(-?\d+)([dmyw])/';
        preg_match_all($pattern, $value, $dateArray);
        if (count($dateArray) > 0) {
            $date = strtotime('today');
            foreach ($dateArray[1] as $key => $value) {
                switch ($dateArray[2][$key]) {
                    case 'd':
                        $date = strtotime("+" . $value . "days", $date);
                        break;
                    case 'm':
                        $date = strtotime("+" . $value . "months", $date);
                        break;
                    case 'y':
                        $date = strtotime("+" . $value . "years", $date);
                        break;
                }
            }
        }
        return $date;
    }
}