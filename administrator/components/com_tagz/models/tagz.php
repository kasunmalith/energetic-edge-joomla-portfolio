<?php

/**
 * @version    5.0.1
 * @package    TAGZ
 * @author     roosterz.nl <roy@roosterz.nl>
 * @copyright  2024 roosterz.nl
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;

jimport('joomla.application.component.modellist');

/**
 * Methods supporting a list of Tagz records.
 *
 * @since  1.6
 */
class TagzModelTagz extends JModelList
{
    /**
        * Constructor.
        *
        * @param   array  $config  An optional associative array of configuration settings.
        *
        * @see        JController
        * @since      1.6
        */
    public function __construct($config = array())
    {
        if (empty($config['filter_fields'])) {
            $config['filter_fields'] = array(
                'title', 'a.title', 'modified', 'a.modified',
                'search', 'id', 'a.id'
            );
        }

        parent::__construct($config);
    }

    /**
     * Build an SQL query to load the list data.
     *
     * @return   JDatabaseQuery
     *
     * @since    1.6
     */
    protected function getListQuery()
    {
        $jinput = JFactory::getApplication()->input;
        $type = $jinput->get('component_type');

        $structure = TagzHelper::getContentTypeStructure($type);
        $id = $structure['id'];
        $title = $structure['title'];
        $table = $structure['table'];
        $defaultSort = $structure['defaultSort'];

        if (!isset($id) || !isset($title) || !isset($type) || !isset($table)) {
            return;
        }

        // Create a new query object.
        $db    = $this->getDbo();
        $query = $db->getQuery(true);

        if ($type != 'opencart' && $type != 'jcart') { // Opencart and JCart use the OpenCart tables
            $table = '#__' . $table;
        }

        if ($type == 'j2store') {
            $query
                ->select($db->quoteName('a.'. $title, 'title'))
                ->select('t.*')
                ->select($db->quoteName($id, 'component_id'))
                ->from($table . ' as a')
                ->join('INNER', $db->quoteName('#__j2store_products', 'b') . ' ON (' . $db->quoteName('b.product_source_id') . ' = ' . $db->quoteName('a.id') . ')')
                ->join('LEFT', '#__tagz AS t ON t.component_id = '. $id .' AND t.component_type = \''. $type .'\'');
        } else {
            $query
                ->select($db->quoteName('a.'. $title, 'title'))
                ->select('t.*')
                ->select($db->quoteName('a.'. $id, 'component_id'))
                ->from($table . ' as a')
                ->join('LEFT', '#__tagz AS t ON t.component_id = a.'. $id .' AND t.component_type = \''. $type .'\'');
        }

        // Filter the list over the search string if set.
        $search = $this->getState('filter.search');
        if (!empty($search)) {
            $search = $db->quote('%' . $db->escape($search, true) . '%');
            $query->where($db->quoteName($title) . 'LIKE ' . $search);
        }

        if ($type == "menu") { // Get the Menu type as well
            $query->select($db->quoteName('a.menutype', 'menutype'));
            $query->where($db->quoteName('a.published') . ' = 1'); // only published menu items
        }

        if ($type == "zoo") {
            $query->select($db->quoteName('a.type', 'zoo_type'));
        }

        // Add the list ordering clause.
        $orderCol  = $this->state->get('list.ordering', $defaultSort);
        $orderDirn = $this->state->get('list.direction', 'desc');
        $query->order($db->escape($orderCol . ' ' . $orderDirn));

        return $query;
    }

    /**
     * Get an array of data items
     *
     * @return mixed Array of data items on success, false on failure.
     */
    public function getItems()
    {
        $items = parent::getItems();

        return $items;
    }
}
