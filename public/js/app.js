/* Soubla Salam Couture — petits comportements côté navigateur */

(function () {
    'use strict';

    // Zones tactiles confortables : tout bouton a une hauteur minimale (contrainte CSS déjà appliquée).

    // Carrousel simple : miniatures d'une fiche modèle
    document.querySelectorAll('[data-photo-zoom]').forEach(function (miniature) {
        miniature.addEventListener('click', function () {
            var galerie = miniature.closest('[data-photo-galerie]');
            if (!galerie) return;
            var principale = galerie.querySelector('[data-photo-main]');
            if (!principale) return;

            principale.src = miniature.src;
            principale.alt = miniature.getAttribute('alt') || principale.alt;

            galerie.querySelectorAll('.thumb-photo').forEach(function (el) {
                el.classList.remove('active');
            });
            miniature.classList.add('active');
        });
    });

    // Zoom plein écran d'une photo (lightbox minimal)
    document.querySelectorAll('[data-photo-main]').forEach(function (photo) {
        photo.addEventListener('click', function () {
            var overlay = document.createElement('div');
            overlay.style.cssText = 'position:fixed;inset:0;background:rgba(20,10,5,.92);z-index:2000;display:grid;place-items:center;padding:1rem;cursor:zoom-out';
            var img = document.createElement('img');
            img.src = photo.src;
            img.alt = photo.alt;
            img.style.cssText = 'max-width:96vw;max-height:96vh;border-radius:14px;object-fit:contain';
            overlay.appendChild(img);
            document.body.appendChild(overlay);
            overlay.addEventListener('click', function () { overlay.remove(); });
        });
    });

    // Copier dans le presse-papiers (mot de passe généré, lien de partage)
    document.querySelectorAll('[data-copy]').forEach(function (button) {
        button.addEventListener('click', function () {
            navigator.clipboard?.writeText(button.getAttribute('data-copy'));
            var icon = button.querySelector('i');
            if (icon) {
                icon.classList.replace('bi-clipboard', 'bi-check-lg');
                setTimeout(function () { icon.classList.replace('bi-check-lg', 'bi-clipboard'); }, 1500);
            }
        });
    });

    // Champs de partage réseau (WhatsApp / Facebook) sur la fiche modèle
    var shareUrl = encodeURIComponent(window.location.href);
    document.querySelectorAll('[data-share-whatsapp]').forEach(function (link) {
        link.href = 'https://wa.me/?text=' + encodeURIComponent(document.title) + '%20' + shareUrl;
    });
    document.querySelectorAll('[data-share-facebook]').forEach(function (link) {
        link.href = 'https://www.facebook.com/sharer/sharer.php?u=' + shareUrl;
    });
})();
