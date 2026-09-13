jQuery(document).ready(function($) {
	
	
	var headerHeight = $(".page-header").height();
	headerHeight=headerHeight+20;
	$(".col-1").css('padding-top', headerHeight);
	
	
		 var screenWidth = $(window).width();
		 
		 if(screenWidth<=999){
		$(".menu li").hover(
		function(){
			$(this).children("ul").stop().animate({
                        opacity: 1,
                        top: "100px"
                    }, 500);
		},
		function(){
			$(this).children("ul").stop().fadeOut().animate({
                        opacity: 0,
                        top: "140px"
                    }, 1);;
		}
	);
	
	}else{
		$(".menu li").hover(
		function(){
			$(this).children("ul").stop().animate({
                        opacity: 1,
                        top: "100px"
                    }, 500);
		},
		function(){
			$(this).children("ul").stop().fadeOut().animate({
                        opacity: 0,
                        top: "140px"
                    }, 1);;
		}
	);
	
	}
	//$(".mobile_menu_icon").html(screenWidth);

	 if(screenWidth<=999){
	 	$(".article-set").hover(
		function(){
			$(this).children(".image").animate({
        top: '-80px',
       opacity: 0.9 
      }, 1500);
		},function(){
			$(this).children(".image").animate({
        top: '0px',
        opacity: 0.8 
      }, 500);
		}
	);
	 }else{
	 	$(".article-set").hover(
		function(){
			$(this).children(".image").animate({
        top: '-350px',
       opacity: 0.8
      }, 1500);
		},function(){
			$(this).children(".image").animate({
        top: '0px',
        opacity: 0.6 
      }, 500);
		}
	);
	 }
	
	
		$(".inner-article-set").hover(
		function(){
			$(this).children(".article_btn").animate({
       backgroundColor: "rgba(255,255,255,0.7)",
        borderColor: "#fff",
         color: "#000"
      }, 500);
		},function(){
			$(this).children(".article_btn").animate({
      backgroundColor: "rgba(255,255,255,0)",
       borderColor: "#ff812d",
        color: "#ff812d"
      
      }, 500);
		}
	);


	
	
	
	
	
})

jQuery(document).ready(function($) {
	

	
		$("#cat_article_list .inner-article-set").hover(
		function(){
			$(this).children(".article_btn").animate({
       backgroundColor: "rgba(255,255,255,0.7)",
        borderColor: "#fff",
         color: "#000"
      }, 500);
		},function(){
			$(this).children(".article_btn").animate({
      backgroundColor: "rgba(255,255,255,0)",
       borderColor: "#ff812d",
        color: "#ff812d"
      
      }, 500);
		}
	);
})	

