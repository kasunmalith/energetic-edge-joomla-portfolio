<?php
 $target=$_GET['target'];
$exploded=explode("www.xtreamyouth.com/", $target);
$server_id=$_REQUEST['server_id'];
$exploded[1]=urlencode($exploded[1]);
   $getcf="http://www.xtreamyouth.com/server2/albums/delete.php?target=".$exploded[1];
$string = file_get_contents($getcf);

$json_a=json_decode($string);

echo $status= $json_a->isdone;
 $mailonoff = 1;
 function sendMail($to,$from,$subject,$htmlBody){
	//send mail


$headers = "From:" . $from;
$headers = "From: {$from}" . "\r\n" .
'X-Mailer: PHP/' . phpversion();
$headers  .= 'MIME-Version: 1.0' . "\r\n";
$headers .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";
$message = "<html><head></head><body>
{$htmlBody}
</body>
</html>
";
global $mailonoff;

//if($subject=="testnew"){return mail($to,$subject,$message,$headers);}
if($mailonoff){
return mail($to,$subject,$message,$headers);
}else{
	return false;
}
}

$htmlBody="
Hello,

<br>
<h3>The following photo has deleted.</h3><br><br>
Server ID - {$server_id}<br>
Photo : {$target} <br>

<p>Sincerely,<br>
Event Management System</p>
Thank you.
";


 sendMail("kasun2006@gmail.com","delete@xtreamyouth.com","Photo delete at website",$htmlBody);
 ?>