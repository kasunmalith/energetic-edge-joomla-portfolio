<?php
/**
 * @version    5.0.1
 * @package    TAGZ
 * @author     roosterz.nl <roy@roosterz.nl>
 * @copyright  2024 roosterz.nl
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 */

// No direct access.
defined('_JEXEC') or die;

jimport('joomla.application.component.controllerform');

class TagzControllerTag extends JControllerForm
{
    public function save($key = null, $urlVar = null)
    {
        $return = parent::save($key, $urlVar);

        $input = JFactory::getApplication()->input;
        $formData = new JInput($input->get('jform', '', 'array'));
        $componentType = $formData->getString('component_type');

        $task = $input->getString('task');

        if ($task == "save") { // Only by Save and Close redirect, not Apply
            $redirect = 'index.php?option=com_tagz&view=tagz&component_type=' . $componentType;
            $this->setRedirect(JRoute::_($redirect, false));
        }

        return $return;
    }

    public function cancel($key = null, $urlVar = null)
    {
        $return = parent::cancel($key, $urlVar);

        $input = JFactory::getApplication()->input;
        $formData = new JInput($input->get('jform', '', 'array'));
        $componentType = $formData->getString('component_type');

        $redirect = 'index.php?option=com_tagz&view=tagz&component_type=' . $componentType;
        $this->setRedirect(JRoute::_($redirect, false));
        return $return;
    }
}
