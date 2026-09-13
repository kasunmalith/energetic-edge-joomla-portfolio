<style>

	#youtube-sub{
	position: absolute;
    left: 50%;
    top: 0px;
    margin-left: -100px;
}

	#youtube-sub img {
		width: 200px;
		height: 60px;
	}
	#youtube-sub-outer {
		position: fixed;
		top: 25px;
		left: 50%;
		animation-name: fa;
		animation-duration: 2s;
	margin-left: -125px;
		animation-timing-function: ease-in-out;
		width: 250px;
		height: 80px;
	}
	@keyframes fa {
	from {
		top: -60px;
	}
	to {
		top: 25px;
	}
	}

	#youtube-sub-outer .fullscreenaalbum-close {
		    position: absolute;
    top: -12px;
    right: 19px;
    zoom: 0.7;
	}
</style>
<div id="youtube-sub-outer">
	<div onclick="closeyoutube()" class=" fullscreenaalbum-close"></div>
	<a id="youtube-sub" href="https://www.youtube.com/channel/UC1uru7hv5BhZNYoTw8w4KYQ?sub_confirmation=1" target="_blank"> <img src="xy_articleimages/youtube-sub.png" /> </a>
</div>
<script>
	
	function closeyoutube(){
		$j1("#youtube-sub-outer").hide();
	}
	
</script>