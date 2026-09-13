<?php
include 'required_files.php';
error_reporting(E_ALL); ini_set('display_errors', 1);
require_once 'google-api/vendor/autoload.php';
defined('_JEXEC') or die('deny');

class plgContentFskmalbum extends JPlugin{
     function onContentPrepare($context, &$article, &$params, $limitstart) {
$root_address="http://localhost/energeticedge/";     	
	
		$googleArticleAd='';
	$googleArticleAd="";
     	echo $article_id = $_REQUEST['id'];
         
		 
		 
		 ///////////////////////////////////////////////////////////////////////////////////////////////////////
		    if ($context == 'com_content.article') {
            // Get the article ID
            $articleId = $article->id;

            // Now you can use $articleId as the article ID in your plugin logic
            // ...

            // Example: Print the article ID to the error log
            JFactory::getApplication()->enqueueMessage("Article ID: $articleId", 'message');
        }
		 /////////////////////////////////////////////////////////////////////////////////////////////////////////
		 
		 
     
$document = JFactory::getDocument();
$document->addStyleSheet('plugins/content/fskmalbum/css/lg.css');
$document->addStyleSheet('plugins/content/fskmalbum/fskmalbum_2.css');
$document->addScript('media/widgetkit/js/jquery.js');
$document->addScript('plugins/content/fskmalbum/mouse.js');
$document->addScript('plugins/content/fskmalbum/lg.js');
$document->addScript('plugins/content/fskmalbum/lg-fullscreen.js');
$document->addScript('plugins/content/fskmalbum/lg-fullscreen.js');
$document->addScript('plugins/content/fskmalbum/lg-zoom.js');
$document->addScript('plugins/content/fskmalbum/fskmalbum9.js');
$document->addStyleSheet('plugins/content/fskmalbum/fskmalbum_2.css');
$articleUrl=JURI::current();
$articleUrl=str_replace("http://", "", $articleUrl);
$articleUrl=urlencode($articleUrl);

$user   = JFactory::getUser();
$isroot = $user->get('isRoot');
$groups = $user->get('groups');
$isDeleter=false;
foreach($groups as $group) {
if($group==16){
	$isDeleter=true;
}
}
$foldername="";
$albuminfo="";
if($isroot){
	 $albuminfo = "article_id = ".$article_id."<br>"."";
}
/////password
$db = JFactory::getDbo();
$query = $db->getQuery(true);
$query->select($db->quoteName('attribs'));
$query->from($db->quoteName('#__content'));
$query->where($db->quoteName('id') . ' = ' . (int)$article_id);
$db->setQuery($query); 
$attribs = $db->loadResult();

$attribs=$attribs[0];
$attrb = json_decode($attribs); 
$password = $attrb->article_password;


if(strlen($password)==0){
$isPrivate=FALSE;
}else{
$isPrivate=TRUE;
}
if($isroot||$isDeleter){
	
	 $collageUpdate.= "
  ";
 $deletecode=$albuminfo."
   
          ";
}else{
	$collageUpdate.="";
    $deletecode="
          <script></script>
          ";
}
if($isPrivate){
	if(isset($_REQUEST['checkpassword'])){
$checkpassword=$_REQUEST['checkpassword'];
if($password==$checkpassword){

//include 'withdownload_ex3.php';

}
	};
	$decodedArticleUrl=urldecode($articleUrl);
	$replaceText = "<img src='xy_articleimages/private-album-collage.jpg'>
	<div class='metrouicss'>
	<form class='formBody input-control text' action='' method='post'>
	<input name='checkpassword' type='text' value='' placeholder='Enter your password'>
	<input type='submit' value='Submit'>
	</form>
	</div>";
	
	preg_match_all('/{AGD}(.*?){\/AGD}/is', $article->text,$matches);
	 foreach($matches[0] as $match){
	 	  $article->text=str_replace($match,$replaceText , $article->text);
		 
		 $replaceText="";
	 }
	
		preg_match_all('/{AG}(.*?){\/AG}/is', $article->text,$matches);
	 foreach($matches[0] as $match){
	 	  $article->text=str_replace($match,$replaceText, $article->text);
		  $replaceText="";
	 }
	 
}else{
	

//include 'withdownload_ex3.php';

}
   }
}




