<link href="http://www.xtreamyouth.com/plugins/content/fskmalbum/photoswipe2/photoswipe.css" type="text/css" rel="stylesheet" />

<script type="text/javascript" src="http://www.xtreamyouth.com/plugins/content/fskmalbum/photoswipe2/lib/simple-inheritance.min.js"></script>
<script type="text/javascript" src="http://www.xtreamyouth.com/plugins/content/fskmalbum/photoswipe2/code-photoswipe-1.0.11.min.js"></script>

<script type="text/javascript" src="http://jquery.eisbehr.de/lazy/js/min/jquery.js"></script>
<script type="text/javascript" src="http://www.xtreamyouth.com/plugins/content/fskmalbum/photoswipe2/jquery.lazy.min.js"></script>

<script type="text/javascript">

	// Set up PhotoSwipe with all anchor tags in the Gallery container
setInterval(function(){
		 		 $('.lazy').lazy({
            appendScroll: $('#fullscreenalbum')
        })
	jQuery(document).ready(function($) {
		 
		 setInterval(function(){
		 		 $('.lazy').lazy({
            appendScroll: $('#fullscreenalbum')
        })
		 })
        

		Code.photoSwipe('a', '#gallery');

		
	
	}); 
</script>

<div id="MainContent">

	<div id="gallery" class="gallery-swipe">
		<?php $dir = "http://www.content-a-xy.net/albums/resonate-2017-surangan";

		$getcf = "http://www.content-a-xy.net/albums/generate.php?name=resonate-2017-surangan";

		$string = file_get_contents($getcf);

		$json_a = json_decode($string);

		$count = 1;
		$files = array();
		$fileList = $json_a -> fileList;
		foreach ($fileList as $file) {
			$files[] = $file;
			$count++;

		}// end while
		sort($files);

		$imageCount = $count;
		?>

		<?php

$count=1;

//loop1

foreach($files as $entry){
		?>

		<div class="gallery-item">
			<a href="<?php echo $dir . "/" . $entry; ?>"> <img src="" class="lazy" data-src="<?php echo $dir . "/thumbnails/thumb_" . $entry; ?>" /></a>
		</div>
		<?php $count++;

		}
		?>
	</div>

</div>

<style>
	body {
		margin: 0;
	}
	.gallery-swipe .gallery-item {
		float: left;
		width: 200px;
		overflow: hidden;
		height: 130px;
		max-width: 45%;
	}

	.gallery-swipe img {
		width: 200px;
		height: auto;
	}
</style>
