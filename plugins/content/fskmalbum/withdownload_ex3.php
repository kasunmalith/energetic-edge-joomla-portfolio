<?php 
$error="";
$preg=preg_match_all('/{AGD}(.*?){\/AGD}/is', $article->text,$matches);
if($preg==0){
	preg_match_all('/{AG}(.*?){\/AG}/is', $article->text,$matches);
}
$pname=preg_match_all('/{PNAME}(.*?){\/PNAME}/is', $article->text,$pp);



  foreach($pp[0] as $pps){
    	$article->text=str_replace($pps,"", $article->text);	
		 
 }
	
	

 $i=0;
 $howmanycollages = count($matches[0]);     		
        foreach($matches[0] as $match){
        	

			
            $username=$matches[1][$i];
          
  $send_foldername=$username;
  $dir="";	
$collageUpdate="";

  $server_id=3;
  
  $getcf="https://energeticedge.lk/gapi_test/test.php?folder={$username}&server_id=3";
 
$string = file_get_contents($getcf);

if($string){
	$error .= "";
}else{
	
	$error .= "<div class='album_error'>Note: The photos in the following album might be unavailable due to a suspension caused from a server overload due to the high amount of viewership we have received on our photo albums. We offer our sincere apologies for the inconvenience. Our team is working hard to resolve the issue!</div>";
}



$json_a=json_decode($string);
if(empty($json_a->icount)){$countx= 0;}else{
	$countx= $json_a->icount;
}

  
if((!empty($countx))||$countx>=2){
    	$photo_preview="";
   if($i==0){
    $count=1;

    $files=array();

$fileList=$json_a->image_list;

   foreach ($fileList as $file) {

 $files[$count]=$file;

 
        $count++;

    
if($count==3){break;}

    } // end while

    $images="";
	foreach($files as $entry){
		
		 $images.="<div><img src=\"{$entry}\"></div>";


}

    
   
   }
  $collage_link=$json_a->collage_link;

        



ob_start(); 
 ?>
 
 

<div id="preview_collage<?php echo $i; ?>" class="photo_album" onclick="loadFskalbum('<?php echo $username; ?>','<?php echo $i; ?>','<?php echo $articleUrl; ?>',<?php echo $article_id; ?>,<?php echo $server_id; ?>,'<?php echo $root_address; ?>')">
  		<div class="photo_album-inner">
  		<div class="album-preview"><img src="<?php echo $collage_link; ?>" /></div>
  		<div class="photo-album-text-outer">
  			<div class="photo-count"><?php echo $countx;?> Photos</div>
  			<div class="click-open">Click to Open in Photo Viewer</div>
  			<div class="photographer-name">Photographed by <?php echo $pp[1][$i]; ?></div>
  			<div class="share-line">
  				<div class="share-word">don't forget to share</div>
  				<div class="share-btns"></div>
  			</div>
  		</div>
  		</div>
  	</div>

<?php
$htmlContent = ob_get_contents();
ob_end_clean(); 
if( $i==0){
				$htmlContent='<div class="photo_albums">'.$htmlContent;
			}
$currentAlbum=$i+1;

if($howmanycollages==$currentAlbum){

	$htmlContent=$htmlContent."</div> <!--end photo albums--> ";
}
 $replaceData=$collageUpdate.$photo_preview.$error.$htmlContent;
$article->text=str_replace($match,$replaceData , $article->text);
  

}else{
    $replaceData=$collageUpdate."<div>{$error}</div>";
            $replaceData.=$googleArticleAd;
             $article->text=str_replace($match,$replaceData , $article->text);
}


$album_count=$i;

         ///////
              $i++;

        }



?>







