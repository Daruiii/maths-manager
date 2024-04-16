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
