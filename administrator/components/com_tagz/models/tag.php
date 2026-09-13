<?php

/**
 * @version    5.0.1
 * @package    TAGZ
 * @author     roosterz.nl <roy@roosterz.nl>
 * @copyright  2020 roosterz.nl
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die('Restricted access');

// import Joomla modelform library
jimport('joomla.application.component.modeladmin');

/**
 * Item Model
 */
class TagzModelTag extends JModelAdmin
{
    /**
     * Returns a reference to the a Table object, always creating it.
     *
     * @param       type    The table type to instantiate
     * @param       string  A prefix for the table class name. Optional.
     * @param       array   Configuration array for model. Optional.
     * @return      JTable  A database object
     * @since       2.5
     */
    public function getTable($type = 'Tagz', $prefix = 'TagzTable', $config = array())
    {
        return JTable::getInstance($type, $prefix, $config);
    }

    /**
     * Method to get the record form.
     *
     * @param       array   $data           Data for the form.
     * @param       boolean $loadData       True if the form is to load its own data (default case), false if not.
     * @return      mixed   A JForm object on success, false on failure
     * @since       2.5
     */
    public function getForm($data = array(), $loadData = true)
    {
        // Get the form.
        $form = $this->loadForm('com_tagz.tag', 'tag', array('control' => 'jform', 'load_data' => $loadData));
        if (empty($form))
        {
            return false;
        }

        return $form;
    }

    protected function preprocessForm(JForm $form, $data, $group = 'content')
    {
        parent::preprocessForm($form, $data, $group);
    }

    public function getItem($pk = null)
    {
        if ($item = parent::getItem($pk)) {

            $params = $item->params;

            if (is_array($params) && count($params)) {

                foreach ($params as $key => $value) {
                    if (!isset($item->$key) && !is_object($value)) {
                        $item->$key = $value;
                    }
                }

                unset($item->params);
            }
        }

        return $item;
    }

    /**
     * Method to get the data that should be injected in the form.
     *
     * @return    mixed    The data for the form.
     */
    protected function loadFormData()
    {
        $app = JFactory::getApplication();

        // Check the session for previously entered form data.
        $data = $app->getUserState('com_tagz.edit.item.data', array());

        if (empty($data))
        {
            $data = $this->getItem();
        }

        $jinput = $app->input;
		$componentType = $jinput->get('component_type');
		$componentId = $jinput->get('component_id');
        $title = $jinput->get('title', '', 'string');

        if ($componentType && $componentId)
        {
            $data->component_type = $componentType;
            $data->component_id = $componentId;
            $data->title = $title;
        }

        return $data;
    }

    /**
     * Method to validate form data.
     */
    public function validate($form, $data, $group = null)
    {
        $newdata = array();

        $params = array();
        $this->_db->setQuery('SHOW COLUMNS FROM #__tagz');
        $dbkeys = $this->_db->loadObjectList('Field');
        $dbkeys = array_keys($dbkeys);

        foreach ($data as $key => $val)
        {
            if (in_array($key, $dbkeys))
            {
                $newdata[$key] = $val;
            }
            else
            {
                $params[$key] = $val;
            }
        }

        $newdata['params'] = json_encode($params);
        return $newdata;
    }

    /**
     * Method to lock an item
     *
     * @access    public
     * @return    boolean    True on success
     */
    function lock($component_type, $component_id)
    {
        $db = JFactory::getDBO();

        $query = $db->getQuery(true);
        $query
            ->select('id')
            ->from($db->quoteName('#__tagz'))
            ->where($db->quoteName('component_type')." = ".$db->quote($component_type))
            ->where($db->quoteName('component_id')." = ".$db->quote($component_id));

        $db->setQuery($query);
        $id = $db->loadResult();

        if (isset($id)) {

            $query = $db->getQuery(true);
    		$query
    			->update($db->quoteName('#__tagz'))
    			->set($db->quoteName('lock_tag') . ' = 1')
    			->where($db->quoteName('id') . ' = ' . $db->quote($id));
 
    		$db->setQuery($query);
            $result = $db->execute();

            return true;
        }
        else {
            return false;
        }
    }

    /**
     * Method to unlock an item
     *
     * @access    public
     * @return    boolean    True on success
     */
    function unlock($component_type, $component_id)
    {
        $db = JFactory::getDBO();

        $query = $db->getQuery(true);
        $query
            ->select('id')
            ->from($db->quoteName('#__tagz'))
            ->where($db->quoteName('component_type')." = ".$db->quote($component_type))
            ->where($db->quoteName('lock_tag') . ' = 1')
            ->where($db->quoteName('component_id')." = ".$db->quote($component_id));

        $db->setQuery($query);
        $id = $db->loadResult();

        if (isset($id)) {

            $query = $db->getQuery(true);
    		$query
    			->update($db->quoteName('#__tagz'))
    			->set($db->quoteName('lock_tag') . ' = 0')
    			->where($db->quoteName('id') . ' = ' . $db->quote($id));

    		$db->setQuery($query);
            $result = $db->execute();

            return true;
        }
        else {
            return false;
        }
    }

    /**
     * Method to Clear an item
     *
     * @access    public
     * @return    boolean    True on success
     */
    function clear($component_type, $component_id)
    {
        $db = JFactory::getDBO();

        $query = $db->getQuery(true);
        $query
            ->select('id')
            ->from($db->quoteName('#__tagz'))
            ->where($db->quoteName('component_type')." = ".$db->quote($component_type))
            ->where($db->quoteName('component_id')." = ".$db->quote($component_id));

        $db->setQuery($query);
        $id = $db->loadResult();

        if (isset($id)) {

            $query = $db->getQuery(true);

            $conditions = array(
                $db->quoteName('id') . " = ".$db->quote($component_id)
            );

            $query->delete($db->quoteName('#__tagz'));
            $query->where($db->quoteName('id') . " = ".$db->quote($id));

            $db->setQuery($query);
            $result = $db->execute();

            return true;
        }
        else {
            return false;
        }
    }

}
