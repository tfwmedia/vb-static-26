/* Volksbühne Worms — Site-JS: Header, Mobile-Navigation, Scroll-Reveal.
   Vanilla JS, absichtlich ohne Framework; wird extern geladen (CSP: script-src 'self'). */
(function () {
  'use strict';

  // Scroll-Schatten am Sticky-Header
  var header = document.querySelector('[data-header]');
  var onScroll = function () {
    if (header) header.classList.toggle('is-scrolled', window.scrollY > 8);
  };
  onScroll();
  window.addEventListener('scroll', onScroll, { passive: true });

  // Mobile Navigation (natives <dialog>)
  var dialog = document.querySelector('dialog[data-nav-dialog]');
  document.querySelectorAll('[data-nav-open]').forEach(function (btn) {
    btn.addEventListener('click', function () {
      if (dialog) dialog.showModal();
    });
  });
  document.querySelectorAll('[data-nav-close]').forEach(function (btn) {
    btn.addEventListener('click', function () {
      if (dialog) dialog.close();
    });
  });
  if (dialog) {
    // Klick auf Backdrop schließt das Menü
    dialog.addEventListener('click', function (e) {
      if (e.target === dialog) dialog.close();
    });
  }

  // Scroll-Reveal
  var revealables = document.querySelectorAll('[data-reveal]');
  if ('IntersectionObserver' in window) {
    var io = new IntersectionObserver(
      function (entries) {
        entries.forEach(function (entry) {
          if (entry.isIntersecting) {
            entry.target.classList.add('is-revealed');
            io.unobserve(entry.target);
          }
        });
      },
      { rootMargin: '0px 0px -10% 0px', threshold: 0.05 }
    );
    revealables.forEach(function (el) {
      io.observe(el);
    });
  } else {
    revealables.forEach(function (el) {
      el.classList.add('is-revealed');
    });
  }
})();
