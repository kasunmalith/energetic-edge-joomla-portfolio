<?php
/**
 * Gopro FormField
 * @package TAGZ
 * @Copyright (C) 2013 JooMarketer.com
 * @ All rights reserved
 * @ Joomla! is Free Software
 * @ Released under GNU/GPL v3.0 License : hhttp://www.gnu.org/licenses/gpl-3.0.html
 **/

defined('JPATH_BASE') or die;
jimport('joomla.form.formfield');
class JFormFieldGopro extends JFormField
{
    protected $type = 'Gopro';

    protected function getLabel()
    {
    	return '';
    }

    protected function getInput()
    {
    	return '<a href="https://www.roosterz.nl/joomla-extensions/tagz" target="_blank"><div class="btn label-warning" style="background-color: #f89406; ">PRO ONLY <span class="icon-out-2"></span></div></a>';
    }

}
