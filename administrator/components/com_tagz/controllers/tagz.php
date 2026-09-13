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

jimport('joomla.application.component.controlleradmin');

/**
 * Tagz list controller class.
 *
 * @since  1.6
 */
class TagzControllerTagz extends JControllerAdmin
{
    /**
     * Proxy for getModel.
     *
     * @param   string  $name    Optional. Model name
     * @param   string  $prefix  Optional. Class prefix
     * @param   array   $config  Optional. Configuration array for model
     *
     * @return  object    The Model
     *
     * @since    1.6
     */
    public function getModel($name = '', $prefix = 'TagzModel', $config = array())
    {
        $model = parent::getModel($name, $prefix, array('ignore_request' => true));

        return $model;
    }

    /**
     *  Lock tagz so TAGZ can't be edited
     *
     *  @return  void
     */
    public function lock()
    {
        $component_type = JFactory::getApplication()->input->get->get('component_type', '', 'STRING');

        $ids           = JFactory::getApplication()->input->get('cid', array(), 'array');
        $ids_count     = 0;
        $non_ids_count = 0;

        // Get the model.
        $model = $this->getModel('Tag');

        foreach ($ids as $id) {
            if ($model->lock($component_type, $id)) {
                $ids_count++;
            } else {
                $non_ids_count++;
            }
        }

        $app = JFactory::getApplication();

        if ($ids_count > 0) {
            $msg = JText::sprintf('COM_TAGZ_NR_ITEMS_LOCKED', $ids_count);
            $app->enqueueMessage($msg, 'message');
        }
        if ($non_ids_count > 0) {
            $msg_not_locked = JText::_("COM_TAGZ_NOT_LOCKED");
            $app->enqueueMessage($msg_not_locked, 'notice');
        }

        $redirect_url = 'index.php?option=com_tagz&view=tagz&component_type=' . $component_type;
        $app->redirect($redirect_url);
    }

    /**
     *  Unlock Locked tagz
     *
     *  @return  void
     */
    public function unlock()
    {
        $component_type = JFactory::getApplication()->input->get->get('component_type', '', 'STRING');
        $ids            = JFactory::getApplication()->input->get('cid', array(), 'array'); // 1 or more items
        $ids_count      = 0;
        $non_ids_count  = 0;

        if (empty($ids)) { // no locked items selected
            $msg = JText::sprintf('COM_TAGZ_SELECT_LOCKED_ITEMS');
        } else { // there are locked id(s)
            $model = $this->getModel('Tag');

            foreach ($ids as $id) {
                if ($model->unlock($component_type, $id)) {
                    $ids_count++;
                } else {
                    $non_ids_count++;
                }
            }
        }

        $app = JFactory::getApplication();
        $msg = "";

        if ($ids_count > 0) {
            $msg = JText::sprintf('COM_TAGZ_NR_ITEMS_UNLOCKED', $ids_count);
            $app->enqueueMessage($msg, 'message');
        }
        if ($non_ids_count > 0) {
            $msg_not_locked = JText::_("COM_TAGZ_NOT_UNLOCKED");
            $app->enqueueMessage($msg_not_locked, 'notice');
        }

        $redirect_url = 'index.php?option=com_tagz&view=tagz&component_type=' . $component_type;
        JFactory::getApplication()->redirect($redirect_url);
    }

    /**
     *  Clear Selected tagz
     *
     *  @return  void
     */
    public function clear()
    {
        $component_type = JFactory::getApplication()->input->get('component_type', '', 'STRING');
        $ids            = JFactory::getApplication()->input->get('cid', array(), 'array'); // 1 or more items
        $ids_count      = 0;
        $non_ids_count  = 0;

        if (empty($ids)) { // no locked items selected
            $msg = JText::sprintf('COM_TAGZ_SELECT_ITEMS_TO_BE_CLEARED');
        } else { // there are id(s) to be cleared
            $model = $this->getModel('Tag');

            foreach ($ids as $id) {
                if ($model->clear($component_type, $id)) {
                    $ids_count++;
                } else {
                    $non_ids_count++;
                }
            }
        }

        $app = JFactory::getApplication();

        if ($ids_count > 0) {
            $msg = JText::sprintf('COM_TAGZ_NR_ITEMS_CLEARED', $ids_count);
            $app->enqueueMessage($msg, 'message');
        }
        if ($non_ids_count > 0) {
            $msg_not_locked = JText::_("COM_TAGZ_NOT_CLEARED");
            $app->enqueueMessage($msg_not_locked, 'notice');
        }

        $redirect_url = 'index.php?option=com_tagz&view=tagz&component_type=' . $component_type;
        JFactory::getApplication()->redirect($redirect_url);
    }
}
