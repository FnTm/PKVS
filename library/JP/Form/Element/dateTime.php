<?php
/**
 * Zend Framework
 *
 * LICENSE
 *
 * This source file is subject to the new BSD license that is bundled
 * with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * http://framework.zend.com/license/new-bsd
 * If you did not receive a copy of the license and are unable to
 * obtain it through the world-wide-web, please send an email
 * to license@zend.com so we can send you a copy immediately.
 *
 * @category  Sozfo
 * @package   Sozfo_Form
 * @copyright Copyright (c) 2009 Soflomo V.O.F. (http://www.soflomo.com)
 * @license   http://framework.zend.com/license/new-bsd     New BSD License
 */

/**
 * Extension to the Textarea to utilise jQuery datetime plugin
 *
 * @category   JP
 * @package    JP_Form
 * @subpackage Element
 * @copyright  Copyright (c) 2012 janis.peisenieks.lv
 * @license    http://framework.zend.com/license/new-bsd     New BSD License
 */
class JP_Form_Element_DateTime extends Zend_Form_Element_Textarea
{
    /**
     * Use formTextarea view helper by default
     * @var string
     */
    public $helper = 'dateTime';
}
