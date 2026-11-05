/* Volksbühne Worms — Countdown zur nächsten Vorstellung.
   Das Ziel-Datum steht im Attribut data-countdown des Wurzel-Elements. */
(function () {
  'use strict';

  var root = document.querySelector('[data-countdown]');
  if (!root) return;

  var target = new Date(root.getAttribute('data-countdown')).getTime();
  if (Number.isNaN(target)) return;

  var dayEl = root.querySelector('[data-countdown-day]');
  var hourEl = root.querySelector('[data-countdown-hour]');
  var minEl = root.querySelector('[data-countdown-min]');
  var secEl = root.querySelector('[data-countdown-sec]');
  var liveEl = root.querySelector('[data-countdown-live]');
  var reduced = window.matchMedia('(prefers-reduced-motion: reduce)').matches;

  function pad(n) {
    return String(n).padStart(2, '0');
  }

  function render() {
    var diff = target - Date.now();
    if (diff <= 0) {
      if (dayEl) dayEl.textContent = '0';
      if (hourEl) hourEl.textContent = '00';
      if (minEl) minEl.textContent = '00';
      if (secEl) secEl.textContent = '00';
      if (liveEl) liveEl.textContent = 'Die Vorstellung hat begonnen.';
      return;
    }
    var days = Math.floor(diff / 86400000);
    var hours = Math.floor(diff / 3600000) % 24;
    var mins = Math.floor(diff / 60000) % 60;
    var secs = Math.floor(diff / 1000) % 60;
    if (dayEl) dayEl.textContent = String(days);
    if (hourEl) hourEl.textContent = pad(hours);
    if (minEl) minEl.textContent = pad(mins);
    if (secEl) secEl.textContent = pad(secs);
    if (liveEl) {
      liveEl.textContent =
        'Noch ' + days + ' Tage, ' + hours + ' Stunden, ' + mins + ' Minuten bis zur Vorstellung.';
    }
  }

  render();
  // Reduzierte Bewegung: kein Sekundentakt, Minutenaktualisierung genügt.
  window.setInterval(render, reduced ? 60000 : 1000);
})();
