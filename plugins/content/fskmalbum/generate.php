<?php 
error_reporting(E_ALL); ini_set('display_errors', 1);
header('Content-Type: application/json');

$dir=$_REQUEST['folder'];
$server_id=$_REQUEST['server_id'];

if($server_id==1){
	$parent_folderid = getenv('ENERGETIC_EDGE_GOOGLE_PARENT_FOLDER_1') ?: '';
} else if($server_id==2){
	$parent_folderid = getenv('ENERGETIC_EDGE_GOOGLE_PARENT_FOLDER_1') ?: '';
}else if($server_id==3){
	$parent_folderid = getenv('ENERGETIC_EDGE_GOOGLE_PARENT_FOLDER_1') ?: '';
}else if($server_id==4){
	$parent_folderid = getenv('ENERGETIC_EDGE_GOOGLE_PARENT_FOLDER_1') ?: '';
}
require_once 'google-api/vendor/autoload.php';
$client = new Google_Client();
if ($parent_folderid === '' || getenv('ENERGETIC_EDGE_GOOGLE_API_KEY') === false || getenv('ENERGETIC_EDGE_GOOGLE_API_KEY') === '') {
    http_response_code(503);
    exit('Google Drive configuration is required.');
}
$client->setApplicationName("Client_Library_Examples");
$client->setDeveloperKey(getenv('ENERGETIC_EDGE_GOOGLE_API_KEY') ?: '');

$service = new Google_Service_Drive($client);

$optParams = array(
  'pageSize' => 1000,
  'fields' => 'nextPageToken, files',
  'q' => "name = '".$dir."' and mimeType = 'application/vnd.google-apps.folder' and '{$parent_folderid}' in parents"
  
);
$results = $service->files->listFiles($optParams);



	$folderId = $results->files[0]['id'];

 $optParams = array(
        'pageSize' => 1000,
        'fields' => "nextPageToken, files(contentHints/thumbnail,fileExtension,iconLink,id,name,size,thumbnailLink,webContentLink,webViewLink,mimeType,parents)",
        'q' => "'".$folderId."' in parents"
        );
 $results1 = $service->files->listFiles($optParams);

   $v1 = json_decode(json_encode($results1['files']), true);
  $nextPageToken=$results1['nextPageToken'];
 
if($nextPageToken==""){
	  $variable=$v1;
} else{
	$vArr=Array();
	
	$rec_key=0;
	function recursion($folderId,$nextPageToken){
		global $service;
	$optParams = array(
        'pageSize' => 1000,
        'fields' => "nextPageToken, files(contentHints/thumbnail,fileExtension,iconLink,id,name,size,thumbnailLink,webContentLink,webViewLink,mimeType,parents)",
        'q' => "'".$folderId."' in parents",
        'pageToken'=>"{$nextPageToken}"
        );
  $results2 = $service->files->listFiles($optParams);
 
   $v = json_decode(json_encode($results2['files']), true);
global $vArr;
  global $rec_key;
  $vArr[$rec_key]=$v;
$rec_key++;
 // $output = ( array_merge( $v1 , $v2 ) );
 //  $variable=$output;
     $nextPageToken=$results2['nextPageToken'];
   if($nextPageToken!=""){
   	   recursion($folderId,$nextPageToken);
   }

return $vArr;


	} //end function
	
	
   
$vvv=recursion($folderId,$nextPageToken);

//print_r($vvv);
   	$before=Array();
	$key=0;
	$itemkey=0;

  foreach ($vvv as $value) {

	/*
foreach(  $value[$key] as $item){
			$localkey=0;
		//$i[$itemkey]=$item[$localkey];
	
	print_r($item);

	$itemkey++;
		$localkey++;
		echo $itemkey;
		echo "<hr>";	
}
*/

 $key++;
  } 
   $itemkey;
// print_r($i);
  // print_r($before);
   
}
 
 
 
//


  exit; 
 $variable=  array_reverse($variable);
 //print_r($variable);

$thumb_list=Array();
$image_list=Array();
$name_list=Array();

$number=0;




 foreach ( $variable as $value){
 
$thumb_list[$number]= $value['thumbnailLink'];
$t= $value['webContentLink'];
$image_list[$number]=str_replace("&export=download", "", $t);
$name_list[$number]= $value['name'];;

$number++;
 } 
 
$collage_key = array_search ('collage.jpg', $name_list);
$collagekeylength= strlen($collage_key);

if($collagekeylength==0){
	//echo "no collage";
	
	$optParams = array(
  'pageSize' => 10,
  'fields' => 'files',
  'q' => "name = 'collage.jpg' and '{$folderId}' in parents"
  
);
$resultsc = $service->files->listFiles($optParams);
	
	
	
	 $collage_link=$resultsc['files'][0]['webContentLink'];
	 $collage_link=str_replace("&export=download", "", $collage_link);
	$collagelength= strlen($collage_link);
	if($collagelength<=2){
	$collage_link = "http://www.xtreamyouth.com/xy_articleimages/preview_collage.jpg";
	}
	 ;
	
	
	
}else{
	$collage_link = $image_list[$collage_key];
	//echo "has collage";
	unset($thumb_list[$collage_key]);
unset($image_list[$collage_key]);
unset($name_list[$collage_key]);
	
}






 $final=Array(
'icount'=>$number,
'nextPageToken'=>$nextPageToken,
'thumb_list'=>$thumb_list,
'image_list'=>$image_list,
'name_list'=>$name_list,
'collage_link'=>$collage_link
);
//print_r($final);
//echo json_encode($final);

?>
