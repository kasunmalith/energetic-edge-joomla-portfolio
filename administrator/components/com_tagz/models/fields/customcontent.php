<?php
/**
 * Element: Custom Content
 * Displays a list of custom content fields and some standard Open Graph content fields
 *
 * @package  TAGZ
 * @copyright  2024 roosterz.nl
 * @ All rights reserved
 * @ Joomla! is Free Software
 * @ Released under GNU/GPL v3.0 License : http://www.gnu.org/licenses/gpl-3.0.html
 **/

defined('_JEXEC') or die;

JFormHelper::loadFieldClass('list');

class JFormFieldRH_Customcontent extends JFormFieldList
{
    public $type    = 'Customcontent';
    private $params = null;
    private $db     = null;

    /**
     * Method to get the field options.
     *
     * @return  array  The field option objects.
     *
     * @since   11.1
     */
    protected function getOptions()
    {
        $options = array();

        $excludeImages = $this->element['excludeimages'];

        $defaultFields = array(
           'title' => 'Title',
           'introtext' => 'Intro Text',
           'fulltext' => 'Full Text',
           'metadesc' => 'Meta Description'
        );

        if (!$excludeImages) {
            $defaultFields += [ 'image_intro' => 'Intro Image' ];
            $defaultFields += [ 'image_fulltext' => 'Full Article Image' ];
        }

        foreach ($defaultFields as $defaultField => $value) {
            $defaultFieldTitle = $value . " [Article Field]";
            $options[] = JHtml::_('select.option', $defaultField, $defaultFieldTitle);
        }

        $customFields = $this->getCustomFields();

        if ($customFields !== false) {
            foreach ($customFields as $customField) {
                $customFieldTitle = $customField->title . " [Custom Field]";
                $options[]    = JHtml::_('select.option', $customField->id, $customFieldTitle);
            }

            return $options;
        }

        return "";
    }

    public function getCustomFields()
    {
        $this->db = JFactory::getDBO();

        $query = $this->db->getQuery(true)
            ->select('title, id')
            ->from('#__fields');
        $this->db->setQuery($query);
        $fields = $this->db->loadObjectList();

        return $fields;
    }

    private function get($val, $default = '')
    {
        return (isset($this->params[$val]) && (string) $this->params[$val] != '') ? (string) $this->params[$val] : $default;
    }
}
