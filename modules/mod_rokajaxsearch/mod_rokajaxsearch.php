<?php

use Joomla\CMS\Factory;

require(JModuleHelper::getLayoutPath('mod_rokajaxsearch'));


 $myMod_dir = str_replace(JPATH_BASE, '', dirname(__FILE__));
$myMod_dir=str_replace('\\', '/', $myMod_dir);

$myMod_dir= substr($myMod_dir, 1);   
$myVariable = Factory::getSession()->get('baseURL', 'Default value if not set');
?>
<script>
		$j1(document).ready(function(){     
      $j1("#roksearch_search_str").click(function(){
      	$j1(this).attr("disabled", "disabled"); 
      	setTimeout(function(){
      		 $j1("#modern-search-box").focus();
      	},500)
      	$j1("#modern-search, #fancybox-overlayz").fadeIn();
      })
 ///////////////
     
     

   
     
     }
    )
    
</script>
<style>

</style>
<script>

	jQuery(document).ready(function($) {

$("#modern-search-box").bind("enterKey",function(e){
  $("#modern-search-box .submit").click();
  
});
$('textarea').keyup(function(e){
    if(e.keyCode == 13)
    {
        $(this).trigger("enterKey");
    }
});


    $("#modern-search-form").ajaxForm({
        beforeSubmit : function() {
    $("#modern-search .loading_gif").show();
        },
        
          success  : function() {
    $("#modern-search .loading_gif").hide();
        },
target : '#modern-search-result'
    });
})
</script>

<div id="modern-search" class="metrouicss">
	<p class="title">	Search your event on Energetic Edge</p>
	<div id="modern-search-area">
	<form style="position: relative;" autocomplete="off" id="modern-search-form" action="<?php  echo $myVariable.$myMod_dir; ?>/form.php" method="post">
			<input id="modern-search-box" type="text" name="keyword" size="50" />
			<input value="Search" class="submit" type="submit" />
	<div class="loading_gif">loading</div>
	</form>
		
	</div>
	<div id="modern-search-result"></div>
	
<?php


 ;

$x=0;

?> 



</div>