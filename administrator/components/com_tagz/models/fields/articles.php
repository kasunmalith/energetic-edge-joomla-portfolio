<?php
/**
 * Element: Articles
 * Displays a list of articles
 *
 * @package TAGZ
 * @Copyright (C) 2024 roosterz.nl
 * @ All rights reserved
 * @ Joomla! is Free Software
 * @ Released under GNU/GPL v3.0 License : http://www.gnu.org/licenses/gpl-3.0.html
 **/

defined('_JEXEC') or die;

class JFormFieldRH_Articles extends JFormField
{
	public $type = 'Articles';
	private $params = null;
	private $db = null;

	protected function getInput()
	{
		$this->params = $this->element->attributes();
		$this->db = JFactory::getDBO();

		$articles = $this->getArticles();

		$options = array();

		foreach ($articles as $article)
		{
			$art_title_id = $article->title . " [" . $article->ctitle . "]" . " [" . $article->id . "]";
			$options[] = JHtml::_('select.option', $article->id, $art_title_id);
		}

		return JHtml::_('select.genericlist', $options, $this->name, array(
			'multiple' => 'multiple'
		), 'value', 'text', $this->value, $this->id);

	}

	function getArticles()
	{
		$query = $this->db->getQuery(true)
			->select('a.title, a.id, c.title as ctitle')
			->from('#__content AS a, #__categories AS c')
			->where('a.access > -1')
			->where('a.state > 0')
			->where('a.catid = c.id');
		$this->db->setQuery($query);
		$articles = $this->db->loadObjectList();

		return $articles;
	}

	private function get($val, $default = '')
	{
		return (isset($this->params[$val]) && (string) $this->params[$val] != '') ? (string) $this->params[$val] : $default;
	}
}
