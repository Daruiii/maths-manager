import './bootstrap';

import Alpine from 'alpinejs';
import { renderKatex } from './katex.js';
import { loaderForm, renderCodeMirror } from './form.js';

window.Alpine = Alpine;

Alpine.start();
document.addEventListener("DOMContentLoaded", function() {
    renderKatex();
    loaderForm();
    renderCodeMirror();
});

window.addEventListener('scroll', function() {
    var backToTopButton = document.getElementById('back-to-top');
    if (!backToTopButton) {
        return;
    }
    if (window.pageYOffset > 200) { // Affiche le bouton après avoir défilé de 200px
        backToTopButton.style.display = 'flex';
    } else {
        backToTopButton.style.display = 'none';
    }
});