<div id="cat_article_list"><div id="cat_article_list_inner">
<?php 

$root_address="https://www.energeticedge.lk/";


defined('_JEXEC') or die;

// Get the category ID
$categoryId = $this->category->id;

$articles = $this->items;

function compareByCreated($article1, $article2) {
    // Assuming 'created' is a timestamp, you may need to adjust the comparison logic based on your data structure
    return strtotime($article2->created) - strtotime($article1->created);
}

// Use usort to sort the articles array using the custom comparison function
usort($articles, 'compareByCreated');

$x=0;
foreach ($articles as $article) {
    // Retrieve article details
    $article_id = $article->id;
    $article_title = $article->title;
	 $article_metadesc = $article->metadesc;
    $article_introtext = $article->introtext;
$created_date_time = $article->created;
  $image = $article->images;


$imagej = json_decode($image); 

  $introimage = $imagej->image_intro;
 

$link="index.php?option=com_content&view=article&id=".$article_id;

$db2 = JFactory::getDbo();

$query2 = $db2->getQuery(true);

$query2->select(array('path'));

$query2->from('#__menu');

$query2->where("menutype='mainmenu' and published=1 and link='{$link}'");

$query2->order('id DESC');

$db2->setQuery($query2,0,4); 

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

<a  class="article_btn" href="<?php echo $event_link; ?>">View Albums</a>







<h4><?php  

$limitedString = strlen($article_title) > 60 ? substr($article_title, 0, 60) . '...' : $article_title;

echo $limitedString;

 ?></h4>

<p style="display: none">  <?php  $article_metadesc;  if(strlen($article_metadesc)>150){ echo $article_metadescL = substr($article_metadesc,0,150).'...';}else{echo $article_metadesc;}


?> </p>
                        
                     
                        

</div>
<div class="image"> <img src="<?php
 echo  $introimage; 
?>" /></div>


<div class="left-box"></div>
<div class="right-box"></div>
</div>
 
 
 
 
 <?php
 
 
 
 
 
 $x++;
 if($x==4) break;
}

$articlesForPg = $this->items;
$j=0;
 $articlesForPgCount = count($articlesForPg);
foreach ($articlesForPg as $articlePg){
	
	
	$j++;
}
$active_page = 1;
if($articlesForPgCount<=4){
	 $pageCount = 1;
	
}else{
	 $pag_count= $articlesForPgCount/4;
 $pageCount = ceil($pag_count);
 }
?>




<div id="pagination2">
<?php
for ($i = 1; $i <= $pageCount; $i++) {
	
   ?>
   <div onclick="openPageCategory(<?php echo $i; ?>,'<?php echo $categoryId; ?>')" class="pagination_number <?php if($active_page==$i){echo "active";} ?>" href="#"><?php echo $i; ?></div>
   <?php
}
?>
</div>

</div></div>

<script>
	function openPageCategory(page,catId){
		
	
		
		$("#cat_article_list_inner").load("<?php echo $root_address."templates/cassiopeia/html/com_content/category/cat_pag.php?page="; ?>"+page+"&catid="+catId,function(){
		
	
	});
		
	}
</script>