<?php

	$dir="";	
	?>
	
	<script src="https://www.energeticedge.lk/templates/cassiopeia/js/jquery-g.js"></script>
		<script src="https://www.energeticedge.lk/plugins/content/fskmalbum/lg.js"></script>
	<script src="https://www.energeticedge.lk/plugins/content/fskmalbum/mouse.js"></script>

	<script src="https://www.energeticedge.lk/plugins/content/fskmalbum/lg-fullscreen.js"></script>
	<script src="https://www.energeticedge.lk/plugins/content/fskmalbum/lg-zoom.js"></script>
	  <script>
            	$j2=jQuery.noConflict();
            	jQuery.noConflict();
            </script>
<script>

jQuery(document).ready(function($){
$('#fullscreenalbum .album_content .thumb_div_b').on('click', function() {
 
 
    $("#fullscreenalbum .album_content").lightGallery({
      
        selector : '.thumb_div_b',
        getCaptionFromTitleOrAlt : 'true',
        preload : 3
})

});
$("#thumb_div1").click();

$('img, a').on('dragstart', function(event) { event.preventDefault(); });
});

</script>
<?php

  $getcf="https://www.energeticedge.lk/gapi_test/test.php?folder={$send_foldername}&server_id=3";

 //  $getcf="http://localhost/xymain"."/gapi/generate.php?folder=lc-revive-2019-th&server_id=3";
$string = file_get_contents($getcf);
$json_a=json_decode($string);



    $count=0;

    $files=array();


$fileList=$json_a->image_list;


$thumb_list=$json_a->thumb_list;
$name_list=$json_a->name_list;
$download_list=$json_a->download_list;
    foreach ($fileList as $file) {

 $files[]=$file;


        $count++;

    

    } // end while
include 'common_albumload.php';
?>

<script>
$j1(function(){
	

// });
 $j1('.album_content .thumb').each(function(){

           $j1(this).load(function() { 

           $j1(this).fadeIn();

           }) //load

      }); //end .album_content .thumb each

 })

 </script>
 <?php
 if(true){
		?>

		<?php
	}
?>