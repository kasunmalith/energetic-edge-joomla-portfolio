<?php
include 'required_files.php';
//error_reporting(E_ALL); 
ini_set('display_errors', 0);
require_once 'google-api/vendor/autoload.php';
defined('_JEXEC') or die('deny');

class plgContentFskmalbum extends JPlugin{
     function onContentPrepare($context, &$article, &$params, $limitstart) {
$root_address="https://www.energeticedge.lk/";     	
$collageUpdate="";	
		$googleArticleAd='';
	$googleArticleAd="";


         
		 
		 
		 ///////////////////////////////////////////////////////////////////////////////////////////////////////
		    if ($context == 'com_content.article') {
            // Get the article ID
            $article_id = $article->id;

            // Now you can use $articleId as the article ID in your plugin logic
            // ...

            // Example: Print the article ID to the error log
            JFactory::getApplication()->enqueueMessage("Article ID: $article_id", 'message');
        }
		 /////////////////////////////////////////////////////////////////////////////////////////////////////////
		 if($context == 'com_content.article'){
	//////////////////////////////////////////////////////////////////
$db1 = JFactory::getDbo();
$query1 = $db1->getQuery(true);
$query1->select($db1->quoteName('metadesc'));
$query1->from($db1->quoteName('#__content'));
$query1->where($db1->quoteName('id') . ' = ' . (int)$article_id);
$db1->setQuery($query1); 
$metadesc = $db1->loadResult();

$article->text=str_replace("{meta_description}",$metadesc, $article->text);


$db1 = JFactory::getDbo();
$query1 = $db1->getQuery(true);
$query1->select($db1->quoteName('images'));
$query1->from($db1->quoteName('#__content'));
$query1->where($db1->quoteName('id') . ' = ' . (int)$article_id);
$db1->setQuery($query1); 
$images = $db1->loadResult();



$imagesj = json_decode($images); 

$introimage = $imagesj->image_intro;


ob_start(); 
?>
<div><img style="opacity: 0; filter: alpha(opacity=0); position: absolute;" title="" src="<?php echo $introimage; ?>"></div>
<?php
$introimagehtml = ob_get_contents();
ob_end_clean(); 

ob_start(); 
?>

<img class="article_main_img" src="<?php echo $introimage; ?>">
<?php
$cover_image = ob_get_contents();
ob_end_clean(); 

$article->text=str_replace("{introimage}",$introimagehtml, $article->text);

$article->text=str_replace("{cover_image}",$cover_image, $article->text);
//////////////////////////////////////////////////////////////////////	 
     
$document = JFactory::getDocument();
$document->addStyleSheet('plugins/content/fskmalbum/css/lg.css');
$document->addStyleSheet('plugins/content/fskmalbum/fskmalbum_2.css');


  
  $document->addScript('https://events1.eonepisode.com/media/system/js/mootools-core.js');
  $document->addScript('https://events1.eonepisode.com/media/system/js/core.js');
  $document->addScript('https://events1.eonepisode.com/media/system/js/caption.js');
  
$document->addScript('templates/cassiopeia/js/jquery-g.js');
$document->addScript('plugins/content/fskmalbum/lg.js');
$document->addScript('plugins/content/fskmalbum/mouse.js');

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

$attrb = json_decode($attribs); 


$password = isset($attrb->article_password) ? $attrb->article_password : null;

if (is_null($password)) {
  $password="";
}

if(strlen($password)==0){
$isPrivate=FALSE;
}else{
$isPrivate=TRUE;
}
if($isPrivate){

		if(isset($_REQUEST['checkpassword'])){
 $checkpassword=$_REQUEST['checkpassword'];
if($password==$checkpassword){

include 'withdownload_ex3.php';

}
	};
	
	$decodedArticleUrl=urldecode($articleUrl);
	$replaceText = "<div class='pwd_protectedtext'>The photo albums are password protected. Please enter the password to proceed.</div>
	<div class='metrouicss'>
	<form class='formBody input-control text' action='' method='post'>
	<input class='pwd_field' name='checkpassword' type='text' value='' placeholder='Enter your password'><br>
	<input class='sbt-btn' type='submit' value='Submit'>
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
	 
	 $pname=preg_match_all('/{PNAME}(.*?){\/PNAME}/is', $article->text,$pp);



  foreach($pp[0] as $pps){
    	$article->text=str_replace($pps,"", $article->text);	
		 
 }
	 
}else{
	
include 'withdownload_ex3.php';

}
?>
<style>
.filename{
position: absolute;
bottom: -20px;
left: 50%;
background: #000;
padding: 1px 3px;
font-size: 9px;
border-radius: 10px;
border: 2px solid #fff;
opacity: 0.3;
text-align: center;
width: 102px;
overflow: hidden;
margin-left: -51px;
}
.collage_upload{
position: relative;
top: 40px;
left: 20px;
z-index: 1;
}

.preview_collage img {
  /* Webkit for Chrome and Safari */
  -webkit-transform: scale(1, 1);
  -webkit-transition-duration: 500ms;
  -webkit-transition-timing-function: ease-out;
 
  /* Webkit for Mozila Firefox */
  -moz-transform: scale(1, 1);
  -moz-transition-duration: 500ms;
  -moz-transition-timing-function: ease-out;
 
  /* Webkit for IE( Version: 11, 10 ) */
  -ms-transform: scale(1, 1);
  -ms-transition-duration: 500ms;
  -ms-transition-timing-function: ease-out;
}
.preview_collage img:hover {
  /* Webkit for Chrome and Safari */
  -webkit-transform: scale(1.2, 1.2); // This is the enlarged size scale of the image.
  -webkit-transition-duration: 500ms;
  -webkit-transition-timing-function: ease-out;
 
  /* Webkit for Mozila Firefox */
  -moz-transform: scale(1.2, 1.2);
  -moz-transition-duration: 500ms;
  -moz-transition-timing-function: ease-out;
 
  /* Webkit for IE( Version: 11, 10 ) */
  -ms-transform: scale(1.20, 1.20);
  -ms-transition-duration: 500ms;
  -ms-transition-timing-function: ease-out;
}
.preview_collage:hover {
opacity: 1 !important;
}

#photo-preview{
 width: 100%;
    height: 100%;
    display: inline-block;
    position: fixed;
    opacity: 0.3;
   
    margin-top: -30px;
}

#photo-preview div{
	float: left;
width: 50%;
overflow: hidden;
}

#photo-preview img{
	width: 100%;
margin-bottom: -132px;

-webkit-filter: blur(15px); 
-moz-filter: blur(15px); 
-o-filter: blur(15px); 
-ms-filter: blur(15px); 
filter: blur(15px);

}
.loadingdone #photo-preview img{
 -webkit-transition: all 4s; /* For Safari 3.1 to 6.0 */
    transition: all 4s;

-webkit-filter: blur(0px); 
-moz-filter: blur(0px); 
-o-filter: blur(0px); 
-ms-filter: blur(0px); 
filter: blur(0px);

}

@media screen and  (max-width: 600px){
	#photo-preview div{
	float: left;
width: 100%;
overflow: hidden;
}
#photo-preview img{
	width: 100%;
margin-bottom: -50px;
}
}

.album_error{
	background: #ff0000;
color: #fff;
padding: 13px;
font-size: 12px;
}


#lazy_article .article .image img {
  /* Webkit for Chrome and Safari */
  -webkit-transform: scale(1, 1);
  -webkit-transition-duration: 500ms;
  -webkit-transition-timing-function: ease-out;
 
  /* Webkit for Mozila Firefox */
  -moz-transform: scale(1, 1);
  -moz-transition-duration: 500ms;
  -moz-transition-timing-function: ease-out;
 
  /* Webkit for IE( Version: 11, 10 ) */
  -ms-transform: scale(1, 1);
  -ms-transition-duration: 500ms;
  -ms-transition-timing-function: ease-out;
}


#lazy_article .article-set:hover .image img{
  /* Webkit for Chrome and Safari */
  -webkit-transform: scale(1.2, 1.2); // This is the enlarged size scale of the image.
  -webkit-transition-duration: 500ms;
  -webkit-transition-timing-function: ease-out;
 
  /* Webkit for Mozila Firefox */
  -moz-transform: scale(1.2, 1.2);
  -moz-transition-duration: 500ms;
  -moz-transition-timing-function: ease-out;
 
  /* Webkit for IE( Version: 11, 10 ) */
  -ms-transform: scale(1.20, 1.20);
  -ms-transition-duration: 500ms;
  -ms-transition-timing-function: ease-out;
}
.preview_collage{
overflow: hidden;
width: 100%;
max-height: 400px;
    margin-top: 10px;
}
.google_artcile_ad{
	
}
.youtube-sub{
	 

	    display: none;
}
</style>
<?php
} // end of article id if condition
   }
}

?>


  	

  	
  
  
 
<?php
 


