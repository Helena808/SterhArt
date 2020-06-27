//Мои настройки Fancybox
$('[data-fancybox="portfolioGallery"]').fancybox({
	animationEffect: "zoom-in-out",
	transitionEffect: "slide",
	buttons: [
	    "zoom",
	    "close"
	],
	protect: true,
});

$('[data-fancybox="execStageGallery"]').fancybox({
	animationEffect: "zoom-in-out",
	transitionEffect: "slide",
	buttons: [
	    "zoom",
	    "close"
	],
	protect: true,
});

$('[data-fancybox="clientStageGallery"]').fancybox({
	animationEffect: "zoom-in-out",
	transitionEffect: "slide",
	buttons: [
	    "zoom",
	    "close"
	],
	protect: true,
});

//Мои настройки Gridify
$(function () {
    var options =  {
        srcNode: 'img',             // grid items (class, node)
        margin: '15px',             // margin in pixel
        width: '240px',             // grid item width in pixel
        max_width: '',              // dynamic gird item width
        resizable: true,            // re-layout if window resize
        transition: 'all 0.5s ease' // support transition for CSS3
    };
    $('.grid').gridify(options);
});

