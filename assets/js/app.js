/*
 * Welcome to your app's main JavaScript file!
 *
 * We recommend including the built version of this JavaScript file
 * (and its CSS file) in your base layout (base.html.twig).
 */

// any CSS you import will output into a single css file (app.css in this case)
import '../scss/app.scss';


// Need jQuery? Install it with "yarn add jquery", then uncomment to import it.
import $ from 'jquery';
import popper from 'popper.js';
import bootstrap from 'bootstrap';
import select2 from 'select2';
import bsCustomFileInput from "bs-custom-file-input";

$(document).ready(function() {
    bsCustomFileInput.init();
    $('.js-select2-input').select2();
});

let $contactbutton = $('#contactButton')
$contactbutton.click(e => {
    e.preventDefault()
    $('#contactForm').slideDown();
    $contactbutton.slideUp();
})
