<?php

/**
 * @version    5.0.1
 * @package    TAGZ
 * @author     roosterz.nl <roy@roosterz.nl>
 * @copyright  2024 roosterz.nl
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die('Restricted access');

jimport('joomla.database.table');

class TagzTableConfig extends JTable
{
    /**
     * Constructor
     *
     * @param object Database connector object
     */
    function __construct(&$db) 
    {
        parent::__construct('#__tagz_config', 'name', $db);
    }

    /**
     *  Store method
     *
     *  @param   string  $key  The config name
     */
    public function store($key = 'config')
    {
        $db    = JFactory::getDBo();
        $table = $this->_tbl;
        $key   = empty($this->name) ? $key : $this->name;

        // Check if key exists
        $result = $db->setQuery(
            $db->getQuery(true)
                ->select('COUNT(*)')
                ->from($db->quoteName($this->_tbl))
                ->where($db->quoteName('name') . ' = ' . $db->quote($key))
        )->loadResult();

        $exists = $result > 0 ? true : false;

        // Prepare object to be saved
        $data = new stdClass();
        $data->name   = $key;
        $data->params = $this->params;

        if ($exists)
        {
            return $db->updateObject($table, $data, 'name');
        }

        return $db->insertObject($table, $data);
    }
}