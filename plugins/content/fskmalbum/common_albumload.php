<?php
$imageCount= $count;
$count=0;
//loop1
$thumb_list = json_decode(json_encode($thumb_list), true);
$name_list = json_decode(json_encode($name_list), true);
$download_list = json_decode(json_encode($download_list), true);
foreach($files as $entry){
	
$key =$count;
	$thumbnailLink=$thumb_list[$key];
	$downloadlink=$entry;
	$downloadlink = str_replace("=w1920-h929", "", $downloadlink);
	$download_hq_link=$downloadlink;
	$thumbnail_link = $thumbnailLink;
	$view_link = $entry;
	$title=$name_list[$key];
	$download_link_new = $download_list[$key];
	 ?>
<script> var img<?php echo $count; ?>='<img title="<?php echo $title;?>"   class="thumbz thumb<?php echo $count; ?>" src="<?php echo $thumbnail_link;?>">';$j("#thumb_div<?php echo $count;?>").html(img<?php echo $count; ?>);</script><a title="<?php echo $title;?>"  id="thumb_div<?php echo $count;?>" class='thumb_div_b' data-download-url="<?php echo $download_link_new;?>"  data-src="<?php echo $view_link;?>" ></a>
 <?php

	
	
$count++;
if($count==50) break;
}

?>





<script>


function loadmore(){
	
	
	if($j(".thumb_div_b").length==$j(".thumbz").length){
		$j("#load_more").hide();
	}
	
	
	
	if(!$j(".thumb<?php echo $imageCount; ?>").length==0){
		$j("#load_more").hide();
	}
	$value=$j("#load_more").attr("value");
	$value++;
$j("#load_more").attr("value",$value);
    
	 
	var fullHeight=$j("#fullscreenalbum").prop('scrollHeight');
<?php
$count=50;
$phpval=0;
//loop2
$files = array_slice($files, 50); 
$x=1;
foreach($files as $entry){
 if($count%40==1){
		$x++;
	}
  if($count%50==0){
 	$phpval++;
 }
    ?>
if($j(".thumb<?php echo $count; ?>").length==0){
	
	if($value==<?php echo $phpval;?>){
	   setInterval(function(){	
 	if ( $j('#thumb_div<?php echo $count;?>').children().length > 0 ) {
 
    	 $j('#thumb_div<?php echo $count;?>').removeClass("set2");

}
    },100)
	var current_count="<?php echo $count; ?>";
	
	
	<?php   
   
   $key =$count;
    	$downloadlink=$entry;
		$downloadlink = str_replace("=w1920-h929", "", $downloadlink);
	$download_hq_link=$downloadlink;
		$thumbnailLink=$thumb_list[$key];
	$thumbnail_link = $thumbnailLink;
	$view_link = $entry;
	$title=$name_list[$key];
     ?>
	
	var img<?php echo $count; ?>='<img  title="<?php echo $title;?>" class="thumbz thumb<?php echo $count; ?>" src="<?php echo $thumbnail_link;?>">';$j("#thumb_div<?php echo $count;?>").hide().html(img<?php echo $count; ?>).fadeIn('slow');
	
}
	}
<?php

$count++;
 }
?>
};
</script>
<?php
$count=50;
//loop3

foreach($files as $entry){
	
	
 $key =$count;
	
$downloadlink=$entry;
$downloadlink = str_replace("=w1920-h929", "", $downloadlink);
	$download_hq_link=$downloadlink;
	$thumbnailLink=$thumb_list[$key];
	$thumbnail_link =$thumbnailLink;
	$view_link = $entry;
	$title=$name_list[$key];
	 ?>
<a  title="<?php echo $title;?>"  id="thumb_div<?php echo $count;?>" class='thumb_div_b set2' data-download-url="<?php echo $download_hq_link;?>" data-src="<?php echo $view_link;?>"></a>
<?php

$count++;

}
?>		<style>
.lg-sub-html{
	font-size: 11px;
}
#load_more	{
		      border: 0;
    outline: 0;
    border-radius: 50px;
    background: #ff812d;
    color: #fff;
    text-transform: uppercase;
    cursor: pointer;
    transition: background 0.4s ease;
    font-family: Open Sans;
        display: block;
    position: relative;
    bottom: -10px;
    width: 50%;
    text-align: center;
    font-size: 18px;
    padding: 10px 0px;
    z-index: 1;
    margin: 10px auto;
    clear: both;
			}
			
			#load_more:hover{
				background: #111;
			}
.thumb_div_b.set2{
				height:0px;
				width: 0px;
			}
</style>
<div  id="load_more" value="0" onclick="loadmore()">See More Photos</div>