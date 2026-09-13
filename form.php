<?php   ini_set('display_errors', 0); 
//get joomla functions to the external file
define( '_JEXEC', 1 );
define( 'DS', DIRECTORY_SEPARATOR );
$fullpath=$_SERVER[ 'PHP_SELF' ];
$path= explode("modules", $fullpath);
 $path= $path[0];
$d_path=$_SERVER[ 'DOCUMENT_ROOT' ].$path;
define( 'JPATH_BASE', $d_path );
require_once( JPATH_BASE . DS . 'includes' . DS . 'defines.php' );
require_once( JPATH_BASE . DS . 'includes' . DS . 'framework.php' );
require_once( JPATH_BASE . DS . 'libraries' . DS . 'joomla' . DS . 'factory.php' );
$mainframe = JFactory::getApplication('site');
$mainframe->initialise();
$session = JFactory::getSession();
//end get joomla functions to the external file
 $jpathbase=JPATH_BASE;
 $jpathbase=str_replace('/', '\\', $jpathbase);
  $jpathbase=str_replace('\\\\', '\\', $jpathbase);
 $myMod_dir = str_replace($jpathbase, '', dirname(__FILE__));
 $myMod_dir=str_replace('\\', '/', $myMod_dir);
 $myMod_dir;
/*
 $myMod_dir = str_replace(JPATH_BASE, '', dirname(__FILE__));
$myMod_dir=str_replace('\\', '/', $myMod_dir);

 */
$keyword= JRequest::getVar('keyword');

//////////////

$db1 = JFactory::getDbo();

$query1 = $db1->getQuery(true);

$SQL1="
INSERT INTO #__modern_search (
keyword
) VALUES (
'{$keyword}'
)
";

$db1->setQuery($SQL1);

$result = $db1->query();
//////////////////
$db = JFactory::getDbo();

$query = $db->getQuery(true);
$query->select(array('id,title,metadesc,introtext'));
$query->from('#__content');
 $keyword = preg_replace('/\s+/', ' ', $keyword);

$keyArray=explode(" ", $keyword);
 $keyCount=count($keyArray);
 $x=0;
foreach ($keyArray as $value) {
	 $x++;

	
	$search.= "title like '%".$value."%' ";
	
	if($keyCount==$x){
		
	}else{
		$search.=" and ";
	}
}
  $search;
  $wherez="catid='58' and access=1 and state=1 and {$search}";

$query->where($wherez);
$query->order('created DESC');

$db->escape();
$db->setQuery($query,0,50); 
 $db;
$article_ids = $db->loadResultArray(0);
$article_titles = $db->loadResultArray(1);
$article_metadescs = $db->loadResultArray(2);
$article_introtexts=$db->loadResultArray(3);
function catch_that_image($source) {  
  $first_img = '';
  ob_start();
  ob_end_clean();
 $output = preg_match_all('/<img [^<>]*src=[\\"\']?([^\\"\']+\.(png|jpg|gif))[\\"\']?/i', $source, $matches);
  $first_img = $matches [1] [0];
 // print_r($matches);
return $first_img;

}
$x=0;
foreach($article_ids as $article_id){
   $article_title=$article_titles[$x];
$article_metadesc=$article_metadescs[$x];
?>
<?php
$link="index.php?option=com_content&view=article&id=".$article_id;
$db2 = JFactory::getDbo();
$query2 = $db2->getQuery(true);
$query2->select(array('path'));
$query2->from('#__menu');
$query2->where("menutype='mainmenu' and published=1 and link='{$link}'");
$query2->order('id DESC');
$db2->setQuery($query2); 
$path = $db2->loadResultArray(0);
$event_link=$path[0];
if($article_id>=2404){
	$socialm_event_link = "http://www.xtreamyouth.com/".$event_link;
	$event_link_e=urlencode("http://www.xtreamyouth.com/".$event_link);
}else{
	$socialm_event_link = "http://www.xtreamyouth.com/".$event_link;
	$event_link_e=urlencode("http://www.xtreamyouth.com/".$event_link);
	
}


$article_title_e=urlencode($article_title);
 ?>  <div class="article-set <?php if($x!=1){echo "first";} ?>">
<a style="" class="current link-article" href="<?php echo $event_link; ?>">
<div class="article" id="lazy_article<?php echo $article_id;?>"> <?php
$article_introtext= $article_introtexts[$x];?>
<div class="image">
     <div class="fb_rec_lazy">
             
              </div>
                 <div class="shares">
              
 
       </div><img src="<?php echo   catch_that_image($article_introtext);; ?>" /></div>
<h4><?php echo  $article_title; ?></h4><p>  <?php  $article_metadesc; 
                        if(strlen($article_metadesc)>150){
                        echo $article_metadescL = substr($article_metadesc,0,150).'...';
                        }else{
                           echo $article_metadesc;
                        }
                        ?> </p>
<i class="icon-arrow-right-3 fg-color-white"></i></div> </a></div>
 <?php 

 $x++;
 }



?>