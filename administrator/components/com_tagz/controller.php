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

/**
 * Class TagzController
 *
 * @since  1.6
 */
class TagzController extends JControllerLegacy
{
    /**
     * Method to display a view.
     *
     * @param   boolean  $cachable   If true, the view output will be cached
     * @param   mixed    $urlparams  An array of safe url parameters and their variable types, for valid values see {@link JFilterInput::clean()}.
     *
     * @return   JController This object to support chaining.
     *
     * @since    1.5
     */
    public function display($cachable = false, $urlparams = false)
    {
        require_once JPATH_COMPONENT . '/helpers/tagz.php';

        $view = JFactory::getApplication()->input->getCmd('view', 'about');
        JFactory::getApplication()->input->set('view', $view);

        parent::display($cachable, $urlparams);

        return $this;
    }

    public function getTagInfo()
    {
        $app = JFactory::getApplication();
        $postData = $app->input->get;

        $component_id = $postData->get('component_id', '', 'INT');
        $component_type = $postData->get('component_type', '', 'STRING');

        $results = TagzHelper::getTagInfo($component_id, $component_type);

        echo json_encode($results);
    }

    public function storeTagInfo()
    {
        $app = JFactory::getApplication();
        $postData = $app->input->post;

        $component_id = $postData->getInt('component_id');
        $id = $postData->getInt('id');
        $component_type = $postData->getString('component_type');
        $params = $postData->getArray(array(
            'fb_title' => 'string',
            'fb_description' => 'string',
            'fb_image' => 'string',
            'twitter_title' => 'string',
            'twitter_description' => 'string',
            'twitter_image' => 'string'
        ));

        TagzHelper::storeTagInfo($component_id, $component_type, $params, $id);
    }
}
