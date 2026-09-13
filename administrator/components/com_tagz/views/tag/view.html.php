<?php

/**
 * @version    5.0.1
 * @package    Com_Tagz
 * @author     roosterz.nl <roy@roosterz.nl>
 * @copyright  2016 roosterz.nl
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 */
// No direct access
defined('_JEXEC') or die;

jimport('joomla.application.component.view');

/**
 * View class for a list of Tagz.
 *
 * @since  1.6
 */
class TagzViewTag extends JViewLegacy
{
	protected $items;

	protected $pagination;

	protected $state;

	/**
	 * Display the view
	 *
	 * @param   string  $tpl  Template name
	 *
	 * @return void
	 *
	 * @throws Exception
	 */
	public function display($tpl = null)
	{
		// get the Data
        $form = $this->get('Form');
        $item = $this->get('Item');

        // Check for errors.
        if (count($errors = $this->get('Errors')))
        {
            JFactory::getApplication()->enqueueMessage($errors, 'error');
            return false;
        }

        // Assign the Data
        $this->form = $form;
        $this->item = $item;

		// Set the toolbar
		if ($this->getLayout() !== 'edit_modal')
		{
			$this->addToolBar();
		}

		parent::display($tpl);
	}

	/**
     * Setting the toolbar
     */
    protected function addToolBar()
    {
        $input = JFactory::getApplication()->input;
        $input->set('hidemainmenu', true);

        JToolBarHelper::title(JText::_('COM_TAGZ_EDIT'));

        JToolbarHelper::apply('tag.apply');
        JToolBarHelper::save('tag.save');
        JToolBarHelper::cancel('tag.cancel');
    }
}
