<?php

/**
 * @package     Joomla.Site
 * @subpackage  Templates.cassiopeia
 *
 * @copyright   (C) 2017 Open Source Matters, Inc. <https://www.joomla.org>
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;

use Joomla\CMS\Factory;
use Joomla\CMS\HTML\HTMLHelper;
use Joomla\CMS\Language\Text;
use Joomla\CMS\Uri\Uri;

/** @var Joomla\CMS\Document\HtmlDocument $this */

$app   = Factory::getApplication();
$input = $app->getInput();
$wa    = $this->getWebAssetManager();

// Browsers support SVG favicons
$this->addHeadLink(HTMLHelper::_('image', 'joomla-favicon.svg', '', [], true, 1), 'icon', 'rel', ['type' => 'image/svg+xml']);
$this->addHeadLink(HTMLHelper::_('image', 'favicon.ico', '', [], true, 1), 'alternate icon', 'rel', ['type' => 'image/vnd.microsoft.icon']);
$this->addHeadLink(HTMLHelper::_('image', 'joomla-favicon-pinned.svg', '', [], true, 1), 'mask-icon', 'rel', ['color' => '#000']);

// Detecting Active Variables
$option   = $input->getCmd('option', '');
$view     = $input->getCmd('view', '');
$layout   = $input->getCmd('layout', '');
$task     = $input->getCmd('task', '');
$itemid   = $input->getCmd('Itemid', '');
$sitename = htmlspecialchars($app->get('sitename'), ENT_QUOTES, 'UTF-8');
$menu     = $app->getMenu()->getActive();
$pageclass = $menu !== null ? $menu->getParams()->get('pageclass_sfx', '') : '';

// Color Theme
$paramsColorName = $this->params->get('colorName', 'colors_standard');
$assetColorName  = 'theme.' . $paramsColorName;
$wa->registerAndUseStyle($assetColorName, 'media/templates/site/cassiopeia/css/global/' . $paramsColorName . '.css');

// Use a font scheme if set in the template style options
$paramsFontScheme = $this->params->get('useFontScheme', false);
$fontStyles       = '';

if ($paramsFontScheme) {
    if (stripos($paramsFontScheme, 'https://') === 0) {
        $this->getPreloadManager()->preconnect('https://fonts.googleapis.com/', ['crossorigin' => 'anonymous']);
        $this->getPreloadManager()->preconnect('https://fonts.gstatic.com/', ['crossorigin' => 'anonymous']);
        $this->getPreloadManager()->preload($paramsFontScheme, ['as' => 'style', 'crossorigin' => 'anonymous']);
        $wa->registerAndUseStyle('fontscheme.current', $paramsFontScheme, [], ['media' => 'print', 'rel' => 'lazy-stylesheet', 'onload' => 'this.media=\'all\'', 'crossorigin' => 'anonymous']);

        if (preg_match_all('/family=([^?:]*):/i', $paramsFontScheme, $matches) > 0) {
            $fontStyles = '--cassiopeia-font-family-body: "' . str_replace('+', ' ', $matches[1][0]) . '", sans-serif;
			--cassiopeia-font-family-headings: "' . str_replace('+', ' ', $matches[1][1] ?? $matches[1][0]) . '", sans-serif;
			--cassiopeia-font-weight-normal: 400;
			--cassiopeia-font-weight-headings: 700;';
        }
    } elseif ($paramsFontScheme === 'system') {
        $fontStylesBody    = $this->params->get('systemFontBody', '');
        $fontStylesHeading = $this->params->get('systemFontHeading', '');

        if ($fontStylesBody) {
            $fontStyles = '--cassiopeia-font-family-body: ' . $fontStylesBody . ';
            --cassiopeia-font-weight-normal: 400;';
        }
        if ($fontStylesHeading) {
            $fontStyles .= '--cassiopeia-font-family-headings: ' . $fontStylesHeading . ';
    		--cassiopeia-font-weight-headings: 700;';
        }
    } else {
        $wa->registerAndUseStyle('fontscheme.current', $paramsFontScheme, ['version' => 'auto'], ['media' => 'print', 'rel' => 'lazy-stylesheet', 'onload' => 'this.media=\'all\'']);
        $this->getPreloadManager()->preload($wa->getAsset('style', 'fontscheme.current')->getUri() . '?' . $this->getMediaVersion(), ['as' => 'style']);
    }
}

// Enable assets
$wa->usePreset('template.cassiopeia.' . ($this->direction === 'rtl' ? 'rtl' : 'ltr'))
    ->useStyle('template.active.language')
    ->useStyle('template.user')
    ->useScript('template.user')
    ->addInlineStyle(":root {
		--hue: 214;
		--template-bg-light: #f0f4fb;
		--template-text-dark: #495057;
		--template-text-light: #ffffff;
		--template-link-color: var(--link-color);
		--template-special-color: #001B4C;
		$fontStyles
	}");

// Override 'template.active' asset to set correct ltr/rtl dependency
$wa->registerStyle('template.active', '', [], [], ['template.cassiopeia.' . ($this->direction === 'rtl' ? 'rtl' : 'ltr')]);

// Logo file or site title param
if ($this->params->get('logoFile')) {
    $logo = HTMLHelper::_('image', Uri::root(false) . htmlspecialchars($this->params->get('logoFile'), ENT_QUOTES), $sitename, ['loading' => 'eager', 'decoding' => 'async'], false, 0);
} elseif ($this->params->get('siteTitle')) {
    $logo = '<span title="' . $sitename . '">' . htmlspecialchars($this->params->get('siteTitle'), ENT_COMPAT, 'UTF-8') . '</span>';
} else {
    $logo = HTMLHelper::_('image', 'logo.svg', $sitename, ['class' => 'logo d-inline-block', 'loading' => 'eager', 'decoding' => 'async'], true, 0);
}

$hasClass = '';

if ($this->countModules('sidebar-left', true)) {
    $hasClass .= ' has-sidebar-left';
}

if ($this->countModules('sidebar-right', true)) {
    $hasClass .= ' has-sidebar-right';
}

// Container
$wrapper = $this->params->get('fluidContainer') ? 'wrapper-fluid' : 'wrapper-static';

$this->setMetaData('viewport', 'width=device-width, initial-scale=1');

$stickyHeader = $this->params->get('stickyHeader') ? 'position-sticky sticky-top' : '';

// Defer fontawesome for increased performance. Once the page is loaded javascript changes it to a stylesheet.
$wa->getAsset('style', 'fontawesome')->setAttribute('rel', 'lazy-stylesheet');
$app = Factory::getApplication();
$menu = $app->getMenu();
$item = $menu->getActive();


$isHomePage = ($item && $item->home == 1);

if ($isHomePage) {
   
} else {
  
}

 $baseURL = Uri::base();
Factory::getSession()->set('baseURL', $baseURL);
?>
<!DOCTYPE html>
<html lang="<?php echo $this->language; ?>" dir="<?php echo $this->direction; ?>">

<head>
	<!-- Google tag (gtag.js) -->
<script async src="https://www.googletagmanager.com/gtag/js?id=G-FRGKKVLMD9"></script>
<script>
  window.dataLayer = window.dataLayer || [];
  function gtag(){dataLayer.push(arguments);}
  gtag('js', new Date());

  gtag('config', 'G-FRGKKVLMD9');
</script>


<?php if ($isHomePage){  ?>
<meta property="og:image" content="https://www.energeticedge.lk/images/web-intro-image.jpg"/>
<meta property="og:description" content="Fusing web solutions, creative expertise & cutting-edge photography, cinematography for your success." />
<meta name="keywords" content="Photography,Photographers,Videographers,Cinematography,Drone Photography,Instant Photo Prints,Printing Services,Drone,360 videobooth,Live Streaming,Birthday Events,Event Coverage,Online Presence,Sri Lanka,Baby Photography,Events Promotion,Photobooth Services,Videography,Drones" />
<?php }
 ?>
<meta property="og:type" content="website" />
<meta property="fb:admins" content="100000968983145" />





  
    <jdoc:include type="metas" />
    <jdoc:include type="styles" />
    <jdoc:include type="scripts" />
      <script src="<?php echo $baseURL; ?>templates/cassiopeia/js/jquery.js" type="text/javascript"></script>
      
          
            <script>
            	$j1=jQuery.noConflict();
            	jQuery.noConflict();
            </script>
            <link href="<?php echo $baseURL; ?>templates/cassiopeia/css/main.css" rel="stylesheet" type="text/css" />
            <link href="//fonts.googleapis.com/css?family=Open Sans:100,100i,300,300i,400,400i,500,500i,700,700i,900,900i&amp;subset=cyrillic-ext&amp;display=swap" rel="stylesheet" media="none" onload="media=&quot;all&quot;" type="text/css" />
            
<link href="//fonts.googleapis.com/css?family=Playfair Display:100,100i,300,300i,400,400i,500,500i,700,700i,900,900i&amp;subset=vietnamese&amp;display=swap" rel="stylesheet" media="none" onload="media=&quot;all&quot;" type="text/css" />	
<link href="//fonts.googleapis.com/css?family=DM+Sans:100,100italic,200,200italic,300,300italic,400,400italic,500,500italic,600,600italic,700,700italic,800,800italic,900,900italic&display=swap" rel="stylesheet" type="text/css" />
<link rel="stylesheet" type="text/css" href="<?php echo $baseURL; ?>templates/cassiopeia/slick/slick.css?v2022">
		<link rel="stylesheet" type="text/css" href="<?php echo $baseURL; ?>templates/cassiopeia/slick/slick-theme.css?v2022">


</head>

<body class="site <?php echo $option
    . ' ' . $wrapper
    . ' view-' . $view
    . ($layout ? ' layout-' . $layout : ' no-layout')
    . ($task ? ' task-' . $task : ' no-task')
    . ($itemid ? ' itemid-' . $itemid : '')
    . ($pageclass ? ' ' . $pageclass : '')
    . $hasClass
    . ($this->direction == 'rtl' ? ' rtl' : '');
?>">
    <div id="container_main">
    	<div class="mobile_menu"><?php if ($this->countModules('menu', true)) : ?>
    		<div  id="menu_close" class=""></div>
           <div class="logo_main"><a href="#"></a></div>
                <jdoc:include type="modules" name="menu" style="card" />
            
        <?php endif; ?></div>
        
    <div id="fixed_top_area">
    	 <div class="fixed_top_area_inside">
    	 	<div class="mobile_menu_icon">  </div>
    	 	<div class="logo_main pc_version"><a href="https://www.energeticedge.lk/"></a></div>
    	 <div class="pc_menu">
      <?php if ($this->countModules('menu', true)) : ?>
           
                <jdoc:include type="modules" name="menu" style="card" />
            
        <?php endif; ?>
         </div>
         </div>
     </div>
     
      <main>
      	<div class="sp-column ">
      		<div class="sp-page-title" style="background-image: url(templates/cassiopeia/img/event-header-bg.jpg);">
      		
      		<div class="intro">Building smarter digital solutions through software, AI, cloud technology, and creative innovation.</div>
      		
      				</div>
      				<div class="fade_line"></div>
      				</div>
      			
      				
            <div class="com-content-article-outer">
            		
            	    <jdoc:include type="component" />
            </div>
            </main>
     
     <?php if ($isHomePage){ ?>
     <div id="main_slider_outer">
     	<div id="slider_bg_colour"></div>
    <div id="main_slider">
    	
		
		
		<div class="container">

			<section class="center slider">
				<div class="main_slide_slice">
					
					<div class="slider_text">The BNS Show with Umaria & Randhir - OGA of Sirimavo BV<br>
						<a class="slider_btn" href="https://www.energeticedge.lk/events/school-events/the-bns-show-oga-of-sirimavo-bv">View Photos & Article</a>
						</div>
					<div class="slider_img">
				<img src="<?php echo $baseURL; ?>templates/cassiopeia/slider/1-min.png"  />	</div>
				
				
				
				</div>
				<div class="main_slide_slice">
					<div class="slider_text">Safe Haven '23 - Rotaract Club of SLIIT<br><a class="slider_btn" href="https://www.energeticedge.lk/events/university-events/safe-haven-23-rotaract-club-of-sliit">View Photos & Article</a></div>
						<div class="slider_img">
					<img src="<?php echo $baseURL; ?>templates/cassiopeia/slider/2-min.png"  /></div>
					
				</div>
				
				
				
				
			</section>
			</div>

	
    </div>
    </div>
    
    
    
    
    
    
    
    <?php }

 ?>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js" type="text/javascript"></script>
 <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
		<script src="https://code.jquery.com/jquery-migrate-3.4.0.min.js"></script>
		<script src="<?php echo $baseURL; ?>templates/cassiopeia/slick/slick.js?v2022" type="text/javascript" charset="utf-8"></script>
		  <script src="<?php echo $baseURL; ?>templates/cassiopeia/js/jquery.form.js" type="text/javascript"></script>
		    <script src="<?php echo $baseURL; ?>templates/cassiopeia/js/kmscript.js" type="text/javascript"></script>
		<script type="text/javascript">
			$(document).on('ready', function() {
			$(".center").slick({
					dots : true,
					infinite : true,
					centerMode : false,
					slidesToShow : 1,
					slidesToScroll : 1
				});
			
			});
		</script>
    
            
      <div id="main_search_outer">
    <div id="main_search">
    	
    
    	
    	  <jdoc:include type="modules" name="search" style="none" />
    	
    </div> </div>
    
 
    <div id="morefromrecent">
    	<p class="title">More Recent Events</p>
    	<div id="morefromrecent_result">
    		
    		<?php 
    		$root_address="https://www.energeticedge.lk/";
			
			
$db = JFactory::getDbo();
$query = $db->getQuery(true);
$query->select(array('id,title,metadesc,introtext,created'));
$query->from('#__content');

 


/////////////////////////////////////////////////////////////////////////////

$wherez="access=1 and state=1 and catid!=17";
$query->where($wherez);
$query->order($db->quoteName('created') . ' DESC');
$db->setQuery($query,0,4); 
			
	

$article_ids = $db->loadColumn (0);
$article_titles = $db->loadColumn (1);
$article_metadescs = $db->loadColumn (2);
$article_introtexts=$db->loadColumn (3);
 $created_date_times=$db->loadColumn (4);		
			

$x=0;
			
		
foreach($article_ids as $article_id){

$db1 = JFactory::getDbo();
$query1 = $db1->getQuery(true);
$query1->select($db1->quoteName('images'));
$query1->from($db1->quoteName('#__content'));
$query1->where($db1->quoteName('id') . ' = ' . (int)$article_id);
$db1->setQuery($query1); 
$images = $db1->loadResult();

$imagesj = json_decode($images); 

$introimage = $imagesj->image_intro;



   $article_title=$article_titles[$x];

$article_metadesc=$article_metadescs[$x];
$created_date_time=$created_date_times[$x];

$link="index.php?option=com_content&view=article&id=".$article_id;

$db2 = Factory::getDbo();

$query2 = $db2->getQuery(true);

$query2->select(array('path'));

$query2->from('#__menu');

$query2->where("menutype='mainmenu' and published=1 and link='{$link}'");



$db2->setQuery($query2); 

$path = $db2->loadColumn(0);
$arrayCount = count($path);
if($arrayCount==0){
	$event_link= $link;
}else{
	$event_link=$path[0];

$socialm_event_link = "".$event_link;

	$event_link_e=urlencode("".$event_link);
	$article_title_e=urlencode($article_title);
}




$datetimeString = $created_date_time;

// Create a DateTime object from the string
$datetime = new DateTime($datetimeString);

// Format the DateTime to exclude the time part
$Year = $datetime->format('Y');
$month = $datetime->format('F');
$date = $datetime->format('d');



 ?>
 
 
 
<div class="article-set <?php if($x!=1){echo "first";} ?>" id="lazy_article<?php echo $article_id;?>">

<div  class="inner-article-set" >

  <div class="created_date month"><?php echo $month." ".$Year; ?></div> 
  
  <div class="created_date_number"><?php echo $date;?></div>

<a  class="article_btn" href="<?php echo $event_link; ?>">View Albums</a>



 <?php $article_introtext= $article_introtexts[$x];?>



<h4><?php   $article_title;

$limitedString = strlen($article_title) > 60 ? substr($article_title, 0, 60) . '...' : $article_title;

echo $limitedString;

 ?></h4>

<p style="display: none">  <?php  $article_metadesc;  if(strlen($article_metadesc)>150){ echo $article_metadescL = substr($article_metadesc,0,150).'...';}else{echo $article_metadesc;}


?> </p>
                        
                     
                        

</div>
<div class="image"> <img src="<?php
 echo  $root_address.$introimage; 
?>" /></div>


<div class="left-box"></div>
<div class="right-box"></div>
</div>

 <?php 



 $x++;

 }	
			
$db = Factory::getDbo();
$query = $db->getQuery(true);
$query->select(array('id,title,metadesc,introtext,created'));
$query->from('#__content');

$wherez="access=1 and state=1 and catid!=17";
$query->where($wherez);
$query->order($db->quoteName('created') . ' DESC');


$db->setQuery($query,0,50); 


 $article_ids = $db->loadColumn (0);
$article_titles = $db->loadColumn (1);
$article_metadescs = $db->loadColumn (2);
$article_introtexts=$db->loadColumn (3);
 $created_date_times=$db->loadColumn (4);

 $arrayCount = count($article_ids);



if($arrayCount<=4){
	 $pageCount = 1;
	
}else{
	 $pag_count= $arrayCount/4;
 $pageCount = ceil($pag_count);
 }

  $pageCount;
/////////////////////
$active_page = 1;
?>
<div id="pagination">
<?php
for ($i = 1; $i <= $pageCount; $i++) {
	
   ?>
   <div onclick="openPageRecent(<?php echo $i; ?>)" class="pagination_number <?php if($active_page==$i){echo "active";} ?>" href="#"><?php echo $i; ?></div>
    <?php
}
?>
</div>
		
    		
    	</div>
    	<script>
    		
	function openPageRecent(page){
		$j3=jQuery.noConflict();
		
		//var keyword= encodeURIComponent(keyword);
		$j3("#morefromrecent_result").load("<?php echo $root_address."templates/cassiopeia/recent_events.php?page="; ?>"+page,function(){
		
	
	});
		
	}

    			
    	</script>
</div> <!-- morefromrecent end -->
    
    
    
      </div>
 <?php include 'footer.php'; ?>
 
 
 

 
 <div id="quotation-event" class="visible">
 	<a style="text-decoration: none;"  href="<?php echo $root_address; ?>contact-us">Request a Custom Software Quote</a> 
 	<div id="rquest_quotation_btn0" class="move-left ">
 		 </div>
 <div id="rquest_quotation_btn1" class="move-right "></div>
 </div>
 <script>
 	jQuery(document).ready(function($) {
 		
$(".mobile_menu_icon").click(function(){
	$(".mobile_menu").animate({
      width: '280px'
    }, 1000); 
    
    $("#fixed_top_area").animate({
      left: '280px'
    }, 1000)
    
    
})
	$("#menu_close").click(function(){
	$(".mobile_menu").animate({
      width: '0px'
    }, 1000); 
    
    $("#fixed_top_area").animate({
      left: '0px'
    }, 1000)
    
    
})

 $('#rquest_quotation_btn0').on("click",function() {
 	$(this).hide();
 	$("#rquest_quotation_btn1").show();
        $("#quotation-event").animate({
                left: '-265px' // Adjust the final right position as needed
            }, 1000); // Adjust the duration of the animation in milliseconds
        });
        
        
        
        
        
       $('#rquest_quotation_btn1').on("click",function() {
$(this).hide();
 	$("#rquest_quotation_btn0").show();
            // Toggle the right position with an animation
        $("#quotation-event").animate({
                left: '0px' // Adjust the final right position as needed
            }, 1000); // Adjust the duration of the animation in milliseconds
        });
        
 
        

 	})
 	
 	
 </script>
 
 
 
 
 
 
    <jdoc:include type="modules" name="debug" style="none" />
</body>

</html>
