$j1=jQuery.noConflict();
function loadFskalbum(folderName,count,articleurl,article_id,server_id,root){
//$j1("#container").hide();
	$j1("#fsbutton").click();
	
send_foldername=folderName;
folderName=folderName+count;
var divName='<div id="fullscreenalbum" class="'+folderName+'_main fullscreenaalbum"><div class="album_content"><center> <img class="content-1-loading" src="/images/preloader-w8-cycle-white.gif"></center></div><div onclick="close_fullscreenaalbum(\x27'+folderName+'\x27)" id="'+folderName+'_close" class=" fullscreenaalbum-close"></div></div>';
$j1("body").addClass('removeScroll');
$j1("body").append(divName); //body append
$j1("."+folderName+"_main .album_content").hide();
$j1("."+folderName+"_main .album_content").load(root+'plugins/content/fskmalbum/load.php?count='+count+'&send_foldername='+send_foldername+'&articleurl='+articleurl+'&article_id='+article_id+'&server_id='+server_id,function(){
          $j1(".album_content center").remove();
          $j1("."+folderName+"_main .album_content").fadeIn(2000);
         });

	//$j1("."+folderName+"_main").html("fff");

}

function close_fullscreenaalbum(folderName){
	$j1("#container").show();
	$j1("#album_like_btn").remove();
	$j1("."+folderName+"_main img").remove();
	$j1("."+folderName+"_main").fadeOut(1000,function(){
		$j1("body").removeClass('removeScroll');
		$j1("."+folderName+"_main").remove();
	});
	  var $container = $j1('#content-1');     
      $container.isotope({
        itemSelector: '.moduletable',
        masonry : {
          columnWidth : 1
        }
      });
}

	function loadSingle(path,count){
		
		$j1(".fullscreenaalbum").addClass("singlemood");
		
		
		var data="<img id='single_image' src='"+path+"'>";
		var data2='<div id="closesingle" onclick="closeSingle()"></div>';
		var data3='<div id="next_loadsingle" onclick="next_loadsingle('+count+')"></div>';
		var data4='<div id="prev_loadsingle" onclick="prev_loadsingle('+count+')"></div>';
		$j1("#loadsingle").html(data+data2+data3+data4);
		
		
		setInterval(function(){
			  $j1('#single_image').load(function() {
       
var loadsingle_width=$j1("#single_image").width();
			var margin_left=loadsingle_width/2;
	
			$j1("#loadsingle").css("margin-left","-"+margin_left+"px");
			
			var loadsingle_height=$j1("#single_image").height();
			var margin_top=loadsingle_height/2;
			
			$j1("#loadsingle").css("margin-top","-"+margin_top+"px");
			$j1("#loadsingle,#loadsingle-background").fadeIn();
			
    });
		},0);
			
	
	}	
	




