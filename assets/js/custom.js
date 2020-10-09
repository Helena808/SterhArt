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

// Отображение имен загружаемых файлов
jQuery(function() {
$(".custom-file-input").on("change", function() {
  var fileName = $(this).val().split("\\").pop();
  $(this).siblings(".custom-file-label").addClass("selected").html(fileName);
});
});

// Для подвеса картинок пользователем (добавить новые строки)
jQuery(function() {
	$(document).ready(function() {
	    
	    $("body").on("click",".buttonToOpen",function(){ 
	    	// Копируем первый блок browse и полем для комментария
	    	let html = $(".fieldsForConcepts").children().first().clone();

	    	// Вставляем вниз перед кнопкой "Добавить ещё"
	   		$(".fieldsForConcepts").children().last().before(html);

	   		// Вытаскиваем атрибут name из button (это наш счётчик)
	   		let counter = $(".buttonToOpen").attr("value");	   		

	   		// Формируем имена для name
	   		let newConceptName = "concept"+counter;
	   		let newConceptAnnotationName = "concept_annotation"+counter;

	   		// Устанавливаем элементам нового блока атрибуты name
	   		let kids = $(".fieldsForConcepts").children().eq(-2).children();
	   		$(kids[0]).find("input").attr("name", newConceptName);
	   		$(kids[0]).find("input").attr("id", newConceptName);
	   		$(kids[0]).find("label").attr("for", newConceptName);
	   		$(kids[1]).attr("name", newConceptAnnotationName);

			// Обновляем name в button (+1 счётчику для следующего раза)
			counter++;
			$(".buttonToOpen").attr("value", counter);

			// Отображение имен загружаемых файлов
			$(".custom-file-input").on("change", function() {
  				var fileName = $(this).val().split("\\").pop();
  				$(this).siblings(".custom-file-label").addClass("selected").html(fileName);
			});

		});
	});


});