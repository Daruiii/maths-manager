import './bootstrap';

import Alpine from 'alpinejs';

window.Alpine = Alpine;

Alpine.start();
document.addEventListener("DOMContentLoaded", function() {
    var form = document.querySelector("#exerciseForm");
    form.addEventListener("submit", function() {
        document.getElementById("loadingPopup").style.display = "block";
    });
});