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
 * Sozfo Form.
 *
 * It's the extended version of Zend_Form to enable direct of Sozfo form
 * elements.
 * @author jurian
 */
class Sozfo_Form extends Zend_Form
{
    public function __construct($options = null)
    {
        $this->addPrefixPath('Sozfo_Form_', 'Sozfo/Form/');
        parent::__construct($options);
    }
}