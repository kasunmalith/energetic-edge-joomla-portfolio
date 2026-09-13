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
class TagzViewTagz extends JViewLegacy
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
        $this->state = $this->get('State');
        $this->items = $this->get('Items');
        $this->pagination = $this->get('Pagination');

        $this->filterForm = $this->get('FilterForm');

        // Get active filters.
        $this->activeFilters = $this->get('ActiveFilters');

        // Check for errors.
        if (count($errors = $this->get('Errors'))) {
            throw new Exception(implode("\n", $errors));
        }

        // Set the toolbar
        $this->addToolBar();

        parent::display($tpl);
    }

    /**
     *  Add Toolbar to layout
     */
    protected function addToolBar()
    {
        JToolBarHelper::title(JText::_('TAGZ'));
        JToolbarHelper::custom('tagz.lock', 'lock', 'lock', JText::_('COM_TAGZ_LOCK_SELECTED'), true);
        JToolbarHelper::custom('tagz.unlock', 'unlock', 'unlock', JText::_('COM_TAGZ_UNLOCK_SELECTED'), true);
        JToolbarHelper::custom('tagz.clear', 'unpublish', 'unpublish', JText::_('COM_TAGZ_CLEAR_SELECTED'), true);
    }
}
