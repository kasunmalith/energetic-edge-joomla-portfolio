<?php
header("Access-Control-Allow-Origin: *");
ini_set('error_reporting', E_ALL);
ini_set('display_errors', 1);
$root_address="https://www.energeticedge.lk/";
// Load Joomla framework
define('_JEXEC', 1);
use Joomla\CMS\Factory;

 $fullpath = $_SERVER['PHP_SELF'];
 $path = explode("templates", $fullpath);
 $path = $path[0];
 $d_path = $_SERVER['DOCUMENT_ROOT'] . $path;

define('JPATH_BASE', $d_path);

require_once JPATH_BASE . '/includes/defines.php';
require_once JPATH_BASE . '/includes/framework.php';

ini_set('error_reporting', E_ALL);
ini_set('display_errors', 1);
use Joomla\CMS\Table\Table;
use Joomla\CMS\Table\ContentTable;

$crlf		= PHP_EOL;
$app	= new Joomla\CMS\Application\SiteApplication();
// Get the application object




 $jpathbase=JPATH_BASE;

 $jpathbase=str_replace('/', '\\', $jpathbase);

  $jpathbase=str_replace('\\\\', '\\', $jpathbase);

 $myMod_dir = str_replace($jpathbase, '', dirname(__FILE__));

 $myMod_dir=str_replace('\\', '/', $myMod_dir);

 $myMod_dir;



$input = $app->input;
//print_r($input);
$keyword= $input->get('catid', '', 'raw');
$page_number= $input->get('page', '', 'raw');
$modified_page_number=$page_number-1;
$p=$modified_page_number*4;


$keywordLength = strlen($keyword);

if ($keywordLength >= 1){
	
}else{
	exit;
}
$categoryId = $keyword;
if($categoryId==" "){
	exit;
}

$db = Factory::getDbo();
$query = $db->getQuery(true);
$query->select(array('id,title,metadesc,introtext,created'));
$query->from($db->quoteName('#__content'));
$query->where(
    $db->quoteName('catid') . ' = ' . (int)$categoryId
    . ' AND ' . $db->quoteName('access') . ' = 1'
    . ' AND ' . $db->quoteName('state') . ' = 1'
);
$query->order($db->quoteName('created') . ' DESC');
$db->setQuery($query,$p,4); 
$article_ids = $db->loadColumn (0);
$article_titles = $db->loadColumn (1);
$article_metadescs = $db->loadColumn (2);
$article_introtexts=$db->loadColumn (3);
 $created_date_times=$db->loadColumn (4);



if (!empty($article_ids)) {
    // Loop through each article ID and load the article
    

  
} else {
    echo "No articles found in the specified category.";
	exit;
}




$x=0;

foreach($article_ids as $article_id){


$db1 = Factory::getDbo();
$query1 = $db1->getQuery(true);
$query1->select($db1->quoteName('images'));
$query1->from($db1->quoteName('#__content'));
$query1->where($db1->quoteName('id') . ' = ' . (int)$article_id);
$query1->order($db1->quoteName('created') . ' DESC');
$db1->setQuery($query1); 
$images = $db1->loadResult();

$imagesj = json_decode($images); 

$introimage = $imagesj->image_intro;

   $article_title=$article_titles[$x];

$article_metadesc=$article_metadescs[$x];
$created_date_time=$created_date_times[$x];

$link="index.php?option=com_content&view=article&id=".$article_id;

$db2 = Factory::getDbo();

$query2 = $db2->getQuery(true);

$query2->select(array('path'));

$query2->from('#__menu');

$query2->where("menutype='mainmenu' and published=1 and link='{$link}'");



$db2->setQuery($query2); 

$path = $db2->loadColumn(0);
$arrayCount = count($path);
if($arrayCount==0){
	$event_link= $link;
}else{
	$event_link=$path[0];

$socialm_event_link = "".$event_link;

	$event_link_e=urlencode("".$event_link);
	$article_title_e=urlencode($article_title);
}




$datetimeString = $created_date_time;

// Create a DateTime object from the string
$datetime = new DateTime($datetimeString);

// Format the DateTime to exclude the time part
$Year = $datetime->format('Y');
$month = $datetime->format('F');
$date = $datetime->format('d');



 ?>
 
 
 
<div class="article-set <?php if($x!=1){echo "first";} ?>" id="lazy_article<?php echo $article_id;?>">

<div  class="inner-article-set" >

  <div class="created_date month"><?php echo $month." ".$Year; ?></div> 
  
  <div class="created_date_number"><?php echo $date;?></div>

<a  class="article_btn" href="<?php echo $root_address.$event_link; ?>">View Albums</a>



 <?php $article_introtext= $article_introtexts[$x];?>



<h4><?php   $article_title;

$limitedString = strlen($article_title) > 60 ? substr($article_title, 0, 60) . '...' : $article_title;

echo $limitedString;

 ?></h4>

<p style="display: none">  <?php  $article_metadesc;  if(strlen($article_metadesc)>150){ echo $article_metadescL = substr($article_metadesc,0,150).'...';}else{echo $article_metadesc;}


?> </p>
                        
                     
                        

</div>
<div class="image"> <img src="<?php 
 echo  $root_address."".$introimage; 
?>" /></div>


<div class="left-box"></div>
<div class="right-box"></div>
</div>

 <?php 



 $x++;

 }

///////////////////

$db = Factory::getDbo();
$query = $db->getQuery(true);
$query->select(array('id,title,metadesc,introtext,created'));
$query->from('#__content');

$query->where(
    $db->quoteName('catid') . ' = ' . (int)$categoryId
    . ' AND ' . $db->quoteName('access') . ' = 1'
    . ' AND ' . $db->quoteName('state') . ' = 1'
);


$query->order($db->quoteName('created') . ' DESC');
$db->setQuery($query,0,50); 


 $article_ids = $db->loadColumn (0);
$article_titles = $db->loadColumn (1);
$article_metadescs = $db->loadColumn (2);
$article_introtexts=$db->loadColumn (3);
 $created_date_times=$db->loadColumn (4);

 $arrayCount = count($article_ids);



if($arrayCount<=4){
	 $pageCount = 1;
	
}else{
	 $pag_count= $arrayCount/4;
 $pageCount = ceil($pag_count);
 }

  $pageCount;
/////////////////////
$active_page = $page_number;
?>
<div id="pagination">
<?php
for ($i = 1; $i <= $pageCount; $i++) {
	
   ?>
   <div onclick="openPageCategory(<?php echo $i; ?>,'<?php echo $keyword; ?>')" class="pagination_number <?php if($active_page==$i){echo "active";} ?>" href="#"><?php echo $i; ?></div>
    <?php
}
?>
</div>
<script>
	
	function openPageCategory(page,catId){
		
	
		
		$("#cat_article_list_inner").load("<?php echo $root_address."templates/cassiopeia/html/com_content/category/cat_pag.php?page="; ?>"+page+"&catid="+catId,function(){
		
	
	});
		
	}
	
	
jQuery(document).ready(function($) {
	
	$(".article-set").hover(
		function(){
			$(this).children(".image").animate({
        top: '-350px',
       opacity: 0.7 
      }, 1500);
		},function(){
			$(this).children(".image").animate({
        top: '0px',
        opacity: 0.2 
      }, 500);
		}
	);
	
		$(".inner-article-set").hover(
		function(){
			$(this).children(".article_btn").animate({
       backgroundColor: "rgba(255,255,255,0.7)",
        borderColor: "#fff",
         color: "#000"
      }, 500);
		},function(){
			$(this).children(".article_btn").animate({
      backgroundColor: "rgba(255,255,255,0)",
       borderColor: "#ff812d",
        color: "#ff812d"
      
      }, 500);
		}
	);
})	
	
</script>

