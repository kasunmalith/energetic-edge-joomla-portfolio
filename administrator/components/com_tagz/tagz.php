<?php
/**
 * @version    5.0.1
 * @package    TAGZ
 * @author     roosterz.nl <roy@roosterz.nl>
 * @copyright  2024 roosterz.nl
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 */

// No direct access
defined('_JEXEC') or die;

// Access check.
if (!JFactory::getUser()->authorise('core.manage', 'com_tagz'))
{
	throw new Exception(JText::_('JERROR_ALERTNOAUTHOR'));
}

// Include dependancies
jimport('joomla.application.component.controller');

// Require helper file
JLoader::register('TagzHelper', JPATH_COMPONENT . '/helpers/tagz.php');

$controller = JControllerLegacy::getInstance('Tagz');
$controller->execute(JFactory::getApplication()->input->get('task'));
$controller->redirect();
