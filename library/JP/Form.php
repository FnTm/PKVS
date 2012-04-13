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
 * JP Form.
 *
 * It's the extended version of Zend_Form to enable various features of JP form
 * elements.
 * @author Janis Peisenieks
 */
class JP_Form extends Zend_Form
{
    public function __construct($options = null)
    {
        $this->addPrefixPath('JP_Form_', 'JP/Form/');
        parent::__construct($options);
    }
}