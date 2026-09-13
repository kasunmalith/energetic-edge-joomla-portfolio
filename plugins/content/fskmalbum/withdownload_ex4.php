<?php 

$preg=preg_match_all('/{AGD}(.*?){\/AGD}/is', $article->text,$matches);
if($preg==0){
	preg_match_all('/{AG}(.*?){\/AG}/is', $article->text,$matches);
}


 $i=0;
       
        foreach($matches[0] as $match){
            $username=$matches[1][$i];
          
  $send_foldername=$username;
  $dir="";	
$collageUpdate="";
if($isroot||$isDeleter){
 $collageUpdate.= " ";
  
         
}else{
	$collageUpdate.="";
}
  $server_id=4;
  $getcf=$root_address."/gapi/generate.php?folder={$username}&server_id={$server_id}";
 //$getcf="http://localhost/xymain"."/gapi/generate.php?folder=lc-revive-2019-th&server_id=3";
$string = file_get_contents($getcf);


if($string){
	$error .= "";
}else{
	
	$error .= "<div class='album_error'>Note: The photos in the following album might be unavailable due to a suspension caused from a server overload due to the high amount of viewership we have received on our photo albums. We offer our sincere apologies for the inconvenience. Our team is working hard to resolve the issue!</div>";
}


$json_a=json_decode($string);

$countx= $json_a->icount;
if($countx>=2){
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

    
   $photo_preview="
   <script>

    jQuery(document).ready(function($) {
    	$(\"body\").prepend('<div id=\"photo-preview\">{$images}{$images}</div>');
    });
</script>
   
   ";
   
   }
  $collage_link=$json_a->collage_link;

        
 $replaceData=$collageUpdate.$photo_preview."{$error}<div id=\"preview_collage{$i}\" class=\" preview_collage \" onclick=\"loadFskalbum('{$username}','{$i}','{$articleUrl}',{$article_id},{$server_id})\"><img src='{$collage_link}'></div>
{$deletecode}
 ";
 $foldername="";
if($isroot||$isDeleter){
  $foldername="<br>$username";
}
$replaceData.=$googleArticleAd.$foldername;

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







