/* TEMPORÄRE WERBUNG — Johannes Ambrosius Trio, Fr 20.11.2026, 20 Uhr.
   Zeigt das Promo-Dialogfenster einmal pro Browser-Sitzung; ESC, Schließen-
   Button oder Klick auf den Backdrop beendet es für die Sitzung.

   AUTOMATISCHES AUS: Nach DEADLINE (Freitag, 20.11.2026, 22:00 Uhr MEZ,
   UTC+1) öffnet dieses Script nichts mehr. Nach dem Stichtag bitte
   aufräumen: promo.js, PromoPopup.astro (+ Einbindung im BaseLayout)
   und den CSS-Block ".promo-popup" entfernen. */
(function () {
  'use strict';

  var DEADLINE = new Date('2026-11-20T22:00:00+01:00').getTime();
  var STORAGE_KEY = 'vb-promo-dismissed';

  if (Number.isNaN(DEADLINE) || Date.now() >= DEADLINE) return;

  try {
    if (window.sessionStorage.getItem(STORAGE_KEY)) return;
  } catch (e) {
    /* Speicher blockiert (z. B. privater Modus): Popup trotzdem zeigen */
  }

  var dlg = document.getElementById('promo-popup');
  if (!dlg || typeof dlg.showModal !== 'function') return;

  function dismiss() {
    try {
      window.sessionStorage.setItem(STORAGE_KEY, '1');
    } catch (e) {
      /* egal: Popup erscheint dann ggf. erneut */
    }
  }

  dlg.addEventListener('close', dismiss);
  dlg.addEventListener('cancel', dismiss); // ESC

  dlg.querySelectorAll('[data-promo-close]').forEach(function (btn) {
    btn.addEventListener('click', function () {
      dlg.close();
    });
  });

  // Klick außerhalb des Karten-Inhalts (Backdrop) schließt ebenfalls.
  dlg.addEventListener('click', function (ev) {
    var r = dlg.getBoundingClientRect();
    var inside =
      ev.clientX >= r.left && ev.clientX <= r.right &&
      ev.clientY >= r.top && ev.clientY <= r.bottom;
    if (!inside) dlg.close();
  });

  window.setTimeout(function () {
    if (dlg.open) return;
    try {
      dlg.showModal();
      dlg.focus(); // Dialog selbst fokussieren, nicht den Schließen-Button
    } catch (e) {
      /* z. B. konkurrierendes Dialogfenster: bewusst ignorieren */
    }
  }, 900);
})();
