<?php

/**
 * Sidebarmenu FormField
 * @package roosterz.nl
 * @Copyright (C) 2014 roosterz.nl
 * @ All rights reserved
 * @ Joomla! is Free Software
 * @ Released under GNU/GPL v3.0 License : http://www.gnu.org/licenses/gpl-3.0.html
 */

defined('_JEXEC') or die;

class JFormFieldRH_SidebarMenu extends JFormField
{
    public $type = 'SidebarMenu';
    private $params = null;

    protected function getLabel()
    {
        return '';
    }

    protected function getInput()
    {
        $this->params = $this->element->attributes();

        $title = $this->get('label');
        $class = $this->get('class');

        $start = $this->get('start', 0);
        $end = $this->get('end', 0);

        $html = array();

        if (substr(JVERSION,0,1) == "2") // Joomla 2.5
        {
            if ($start || !$end)
            {
                $html[] = '<ul class="rh_sidebar-menu rh_tab-links">';
            }
            if ($end)
            {
                $html[] = '<div style="clear: both;"></div></ul>';
            }

            return implode('', $html);
        }
        else if ( (substr(JVERSION,0,1) == "3") || (substr(JVERSION,0,1) == "4") || (substr(JVERSION,0,1) == "5") ) // Joomla 3.x, 4.x or 5.x
        {
            if ($start || !$end)
            {
                $html[] = '</div>';
                $html[] = '<ul class="rh_sidebar-menu rh_tab-links">';
            }
            if ($end)
            {
                $html[] = '</div></ul>';
            }

            $html[] = '<div><div>';

            return '</div>' . implode('', $html);
        }

    }

    private function get($val, $default = '')
    {
        return (isset($this->params[$val]) && (string) $this->params[$val] != '') ? (string) $this->params[$val] : $default;
    }
}
