<?php 
ini_set( 'display_errors', 'on' );
//header('Content-Type: application/json');
error_reporting( E_ALL );

$root_address="https://www.energeticedge.lk/"; 
 
$article_id=$_REQUEST['article_id'];
$server_id=$_REQUEST['server_id'];

$send_foldername=$_REQUEST['send_foldername'];

$album_count=$_REQUEST['count'];
 $articleUrl="http://".$_REQUEST['articleurl'];
$articleUrl=urldecode($articleUrl);


include 'load_ex3.php';
	
	

?>
