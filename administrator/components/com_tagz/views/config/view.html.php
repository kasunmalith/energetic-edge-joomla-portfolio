<?php

/**
 * @version    5.0.1
 * @package    TAGZ
 * @author     roosterz.nl <roy@roosterz.nl>
 * @copyright  2024 roosterz.nl
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die('Restricted access');

// import Joomla view library
jimport('joomla.application.component.view');



/**
 * Config View
 */
class TagzViewConfig extends JViewLegacy
{
    /**
     * Items view display method
     *
     * @param   string  $tpl  The name of the template file to parse; automatically searches through the template paths.
     *
     * @return  mixed  A string if successful, otherwise a JError object.
     */
    function display($tpl = null)
    {
        // Check for errors.
        if (count($errors = $this->get('Errors')))
        {
            JFactory::getApplication()->enqueueMessage(implode("\n", $errors), 'error');
            return false;
        }

        $this->form    = $this->get('Form');
        // Set the toolbar
        $this->addToolBar();

        // Display the template
        parent::display($tpl);
    }

    /**
     *  Add Toolbar to layout
     */
    protected function addToolBar()
    {
        JToolBarHelper::title(JText::_('COM_TAGZ_CONFIGURATION'));

        JToolbarHelper::apply('config.apply');
    }
}
