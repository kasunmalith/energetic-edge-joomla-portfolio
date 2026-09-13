<?php

/**
 * @version    5.0.1
 * @package    TAGZ
 * @author     roosterz.nl <roy@roosterz.nl>
 * @copyright  2024 roosterz.nl
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('JPATH_BASE') or die;
jimport('joomla.form.formfield');
class JFormFieldButton extends JFormField
{
    protected $type = 'Button';

    // protected function getLabel()
	// {
	// 	return '';
	// }

    protected function getInput()
    {
        $text  	= (string) $this->element['text'];
        $icon  	= (string) $this->element['icon'];
        $id     = (string) $this->element['id'];
        $class     = (string) $this->element['class'];

        return '<button id="'. $id .'" class="btn '. $class .'">'.(($icon != '') ? '<span class="icon-' . $icon . '"></span> ' : ''). JText::_($text) .'</button>';
    }
}
