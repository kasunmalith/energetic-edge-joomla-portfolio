<?php

/**
 * @version    5.0.1
 * @package    TAGZ
 * @author     roosterz.nl <roy@roosterz.nl>
 * @copyright  2024 roosterz.nl
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die('Restricted access');

// import Joomla table library
jimport('joomla.database.table');

class TagzTableTagz extends JTable
{
    /**
     * Constructor
     *
     * @param object Database connector object
     */
    function __construct(&$db)
    {
        parent::__construct('#__tagz', 'id', $db);
    }
}
