<?php
/**
 *
 * @author Janis
 * @version 
 */

/**
 * {1} Action Helper 
 * 
 * @uses actionHelper {0}
 */
class Zend_Controller_Action_Helper_Multiples extends
                Zend_Controller_Action_Helper_Abstract{
function direct($a)
    {
        return $a * 2;
    }

    function thrice($a)
    {
        return $a * 3;
    }   
}

