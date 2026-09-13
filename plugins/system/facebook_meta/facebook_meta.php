<?php 

/**
 * @version		$Id: nameofplugin.php revision date lasteditedby $
 * @package		Joomla
 * @subpackage	Content
 * @copyright	Copyright (C) 2005 - 2008 Open Source Matters. All rights reserved.
 * @license		GNU/GPL, see LICENSE.php
 * Joomla! is free software. This version may have been modified pursuant
 * to the GNU General Public License, and as distributed it includes or
 * is derivative of works licensed under the GNU General Public License or
 * other free or open source software licenses.
 * See COPYRIGHT.php for copyright notices and details.
 */
// no direct access
defined('_JEXEC') or die('Restricted access');

jimport('joomla.plugin.plugin');

class plgSystemFacebook_meta extends JPlugin {

    function plgSystemFacebook_meta(&$subject, $config) {
        parent::__construct($subject, $config);
    }

    function onBeforeCompileHead() {
        $limit = $this->params->def('limitw');
        if($limit<1) 
            $limit = 247;
        else
            $limit = $this->params->def('limitw')-3;

        $option = JRequest::getVar('option', '');
        $view = JRequest::getVar('view','');
        if($view=='article' && $option=='com_content') {
            $db =  $database = JFactory::getDBO();
            $document =& JFactory::getDocument();
            $id = JRequest::getInt('id');
            
            $sql = "SELECT * FROM #__content WHERE id=".$id." LIMIT 1";
            $db->setQuery($sql);		
            $item = $db->loadObject();
            
            $narekovaji = array('"', "'");
            $title = str_replace($narekovaji, '', $item->title);
            
            preg_match('/<img.+src=[\'"]([^\'"]+)[\'"].*>/i', $item->introtext, $image);           
            if(empty($image))
                preg_match('/<img.+src=[\'"]([^\'"]+)[\'"].*>/i', $item->fulltext, $image); 
       
            $document->addCustomTag( "<meta property='og:url' content='".JURI::current()."'>" );
            $document->addCustomTag( "<meta property='og:type' content='article'>" );
            
            if(isset($item->title))
                $document->addCustomTag( "<meta property='og:title' content='". $title ."'>" );
    
            if(isset($item->introtext)) 
                $document->addCustomTag( "<meta property='og:description' content='".  substr(strip_tags($item->introtext), 0, $limit)."...'>" );
            
            if(!empty($image))
                $document->addCustomTag( "<meta property='og:image' content='". JURI::base().$image[1]."'>" );

        }
    }

}