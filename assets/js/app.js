/*
 * ЗДЕСЬ ПОДКЛЮЧАЕМ ВСЁ ЧТО НУЖНО
 * ЭТОТ ФАЙЛ КОМПИЛИТСЯ В build/js/app.js
 *
 * Welcome to your app's main JavaScript file!
 *
 * We recommend including the built version of this JavaScript file
 * (and its CSS file) in your base layout (base.html.twig).
 */

// any CSS you import will output into a single css file (app.css in this case)
require('../css/app.scss');
require('bootstrap');

// ЗДЕСЬ ИМПОРТИРУЕМ JS И CSS ФАЙЛЫ, КОТОРЫЕ НАМ НУЖНЫ (СВОИ И ЧУЖИЕ)
// 
import '../css/app.scss';
import '../js/jquery.fancybox.min.js';
import '../css/jquery.fancybox.min.css';
import '../js/custom.js';

// Need jQuery? Install it with "yarn add jquery", then uncomment to import it.
import $ from 'jquery';



console.log('Hello Webpack Encore! Edit me in assets/js/app.js');

$('#portfolio_form_photos').change(function() {
    var $el = $(this),
    files = $el[0].files,
    label = files[0].name;
    if (files.length > 1) {
        label = label + " and " + String(files.length - 1) + " more files"
    }
    $el.next('.custom-file-label').html(label);
});

