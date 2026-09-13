<?php

/**
 * @version    5.0.1
 * @package    TAGZ
 * @author     roosterz.nl <roy@roosterz.nl>
 * @copyright  2024 roosterz.nl
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;

class JFormFieldCallout extends JFormField
{
	public $type = 'Callout';
	private $params = null;

	protected function getLabel()
	{
		return '';
	}

	protected function getInput()
    {
        $this->params = $this->element->attributes();

        $title = JText::_($this->get('label'));
        $class = $this->get('class');
		$location_link = $this->get('location_link');

        $start = $this->get('start', 0);
        $end = $this->get('end', 0);

        $html = array();

        if (substr(JVERSION,0,1) == "2") // Joomla 2.5
        {
            if ($start || !$end) {
                if ($start || !$title) {
                    $html[] = '<div ';
					if ($location_link != "") {
						$html[] = 'id="'. $location_link .'" style="display:none;" ';
					}
					//$location_link == "" ?: $html[] = 'id="'. $location_link .'" style="display:none;" ';
                    $html[] = ' class="bs-callout subHeader ' . $class . '">';
                }
                if ($start) {
                    $html[] = '<ul class="adminformlist"><li>';
                }
            }
            if ($title)
            {
                $html[] = '<h4>' . $title . '</h4>';
            }
            if ($end || !$start) {
                if ($end) {
                    $html[] = '<div style="clear: both;"></div></li></ul>';
                }
                if ($end || !$title) {
                    $html[] = '<div style="clear: both;"></div>';
                    $html[] = '</div>';
                }
            }

            return implode('', $html);
        }
        else if ( (substr(JVERSION,0,1) == "3") || (substr(JVERSION,0,1) == "4") || (substr(JVERSION,0,1) == "5") ) // Joomla 3.x, 4.x or 5.x
        {
            if ($start || !$end)
            {
                $html[] = '</div><div ';
				if ($location_link != "") {
					$html[] = 'id="'. $location_link .'" style="display:none;" ';
				}
				//$location_link == "" ?: $html[] = 'id="'. $location_link .'" style="display:none;" ';
                $html[] = ' class="bs-callout subHeader ' . $class . '">';

                if ($title)
                {
                    $html[] = '<h4>' . $title . '</h4>';
                }
                $html[] = '<div><div>';
            }
            if (!$start && !$end)
            {
                $html[] = '</div>';
            }

            return '</div>' . implode('', $html);
        }

    }

	private function get($val, $default = '')
	{
		return (isset($this->params[$val]) && (string) $this->params[$val] != '') ? (string) $this->params[$val] : $default;
	}
}
