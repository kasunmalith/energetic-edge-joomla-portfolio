<?php

/**
 * @version             5.0.1
 * @package             TAGZ
 * @copyright           Copyright (C) 2024 roosterz.nl. All rights reserved.
 * @license             GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
 * Joomla! is free software. This version may have been modified pursuant
 * to the GNU General Public License, and as distributed it includes or
 * is derivative of works licensed under the GNU General Public License or
 * other free or open source software licenses.
 */

// no direct access
defined('_JEXEC') or die('Restricted access');

class pkg_tagzInstallerScript
{
    /**
     *  Minimum Joomla required version
     *
     *  @var  string
     */
    private $min_joomla_version = '3.7';

    /**
     *  Minimum PHP required version
     *
     *  @var  string
     */
    private $min_php_version = '5.4.0';

    /**
     *  Joomla Global Application object
     *
     *  @var  object
     */
    private $app;

    /**
     *  Class Constructor
     */
    public function __construct()
    {
        $this->app = JFactory::getApplication();
    }

    /**
     *  Requirements Checks before installation
     *
     *  @param   object             $route
     *  @param   JInstallerAdapter  $adapter
     *
     *  @return  bool
     */
    public function preflight($route, $adapter)
    {
        // Check Joomla and PHP minimum required version
        if (!$this->passMinimumRequirementVersion("joomla")) {
            return false;
        }

        if (!$this->passMinimumRequirementVersion("php")) {
            return false;
        }

        return true;
    }

    /**
     * Method to run after the install, update, or discover_update actions have completed.
     *
     * @return void
     */
    public function postflight($type, $parent)
    {

        // We only need to perform this if the extension is being installed, not updated
        if ($type == 'install') {
            $this->enablePlugin('tagz', 'system');

            $url = 'index.php?option=com_tagz';
            JFactory::getApplication()->redirect($url);
        }
        if ($type == 'update') {
            $this->enablePlugin('tagz', 'system');

            // Get the plugin information
            $db = JFactory::getDbo();
            $query = $db->getQuery(true)
                ->select('*')
                ->from($db->quoteName('#__extensions'))
                ->where($db->quoteName('type') . ' = ' . $db->quote('plugin'))
                ->where($db->quoteName('folder') . ' = ' . $db->quote('system'))
                ->where($db->quoteName('element') . ' = ' . $db->quote('tagz'));
            $db->setQuery($query);
            $plugin = $db->loadObject();

            $url = 'index.php?option=com_tagz';

            $complete_string = '<strong>TAGZ</strong> has been successfully updated to version <strong>5.0.1 FREE</strong>.<br /><br />You can open the dashboard by clicking <a href="%s">HERE</a>.<br /><br />The TAGZ Changelog can be found <a href="https://www.roosterz.nl/support/changelog/tagz" target="_blank">HERE.</a>';
            JFactory::getApplication()->enqueueMessage(sprintf($complete_string, $url));
        }

        JFactory::getCache()->clean('com_plugins');
    }

    private function passMinimumRequirementVersion($type = "joomla")
    {
        switch ($type) {
            case 'joomla':
                if (version_compare(JVERSION, $this->min_joomla_version, '<')) {
                    $this->app->enqueueMessage(
                        JText::sprintf('This extension is no longer supported on Joomla! %1$s. Please update to a more recent version of Joomla! v%2$s or higher.', JVERSION, $this->min_joomla_version),
                        'error'
                    );
                    return false;
                }
                break;
            case 'php':
                if (version_compare(PHP_VERSION, $this->min_php_version, '<')) {
                    $this->app->enqueueMessage(
                        JText::sprintf('This extension is not compatible with PHP %1$s. You need PHP %2$s or higher to be able to install and use this extension.', PHP_VERSION, $this->min_php_version),
                        'error'
                    );
                    return false;
                }
                break;
        }

        return true;
    }

    private function enablePlugin($name, $plugin_folder)
    {
        $db = JFactory::getDbo();
        $query = $db->getQuery(true)
            ->update($db->quoteName('#__extensions'))
            ->set($db->quoteName('enabled') . ' = 1')
            ->where($db->quoteName('type') . ' = ' . $db->quote('plugin'))
            ->where($db->quoteName('folder') . ' = ' . $db->quote($plugin_folder))
            ->where($db->quoteName('element') . ' = ' . $db->quote($name));
        $db->setQuery($query);
        $db->execute();
    }
}
