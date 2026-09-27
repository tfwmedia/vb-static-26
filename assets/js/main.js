"use strict";

/**
 * Volksbühne Worms — main UI controller.
 * Organized in IIFEs; each module exports onto a `vb` namespace and
 * degrades gracefully when its prerequisite features are absent.
 */
(function () {
    var vb = (window.vb = window.vb || {});

    /* -----------------------------------------------------------------
     * 1. Mobile navigation as <dialog> with focus trap
     * --------------------------------------------------------------- */
    vb.initMobileNav = function () {
        var toggle = document.querySelector("[data-nav-toggle]");
        var dialog = document.querySelector("[data-nav-dialog]");
        if (!toggle || !dialog || typeof dialog.showModal !== "function") {
            // Browser lacks <dialog> support — hide the toggle to avoid
            // presenting a control that does nothing.
            if (toggle) toggle.hidden = true;
            return;
        }

        var focusableSelector =
            'a[href], button:not([disabled]), input:not([disabled]),' +
            ' select:not([disabled]), textarea:not([disabled]),' +
            ' [tabindex]:not([tabindex="-1"])';

        var lastFocused = null;

        function open() {
            if (dialog.open) return;
            lastFocused = document.activeElement;
            dialog.showModal();
            toggle.setAttribute("aria-expanded", "true");
            document.documentElement.style.overflow = "hidden";
            // Focus the first focusable element inside the dialog.
            var first = dialog.querySelector(focusableSelector);
            if (first) first.focus();
        }

        function close() {
            if (!dialog.open) return;
            dialog.close();
            toggle.setAttribute("aria-expanded", "false");
            document.documentElement.style.overflow = "";
            if (lastFocused && typeof lastFocused.focus === "function") {
                lastFocused.focus();
            }
        }

        toggle.addEventListener("click", function () {
            if (dialog.open) {
                close();
            } else {
                open();
            }
        });

        // Native <dialog> emits "close" when ESC is pressed.
        dialog.addEventListener("close", function () {
            toggle.setAttribute("aria-expanded", "false");
            document.documentElement.style.overflow = "";
        });

        dialog.addEventListener("click", function (event) {
            // Close when clicking on the backdrop (outside the inner box).
            if (event.target === dialog) close();
        });

        // Focus trap: keep tabbing within the dialog.
        dialog.addEventListener("keydown", function (event) {
            if (event.key !== "Tab") return;
            var focusables = dialog.querySelectorAll(focusableSelector);
            if (focusables.length === 0) return;
            var first = focusables[0];
            var last = focusables[focusables.length - 1];
            if (event.shiftKey && document.activeElement === first) {
                event.preventDefault();
                last.focus();
            } else if (!event.shiftKey && document.activeElement === last) {
                event.preventDefault();
                first.focus();
            }
        });

        // Close when a nav link inside the dialog is activated.
        dialog.querySelectorAll("a").forEach(function (link) {
            link.addEventListener("click", function () {
                close();
            });
        });

        // Auto-close if the viewport grows past the mobile breakpoint.
        var mq = window.matchMedia("(min-width: 64rem)");
        var handleMq = function (e) { if (e.matches && dialog.open) close(); };
        if (mq.addEventListener) mq.addEventListener("change", handleMq);
        else if (mq.addListener) mq.addListener(handleMq);
    };

    /* -----------------------------------------------------------------
     * 2. Reveal animations via IntersectionObserver
     * --------------------------------------------------------------- */
    vb.initReveal = function () {
        var nodes = document.querySelectorAll("[data-reveal]");
        if (nodes.length === 0) return;

        if (!("IntersectionObserver" in window)) {
            nodes.forEach(function (n) { n.classList.add("is-visible"); });
            return;
        }

        var observer = new IntersectionObserver(
            function (entries, obs) {
                entries.forEach(function (entry) {
                    if (!entry.isIntersecting) return;
                    entry.target.classList.add("is-visible");
                    obs.unobserve(entry.target);
                });
            },
            { threshold: 0.12, rootMargin: "0px 0px -6% 0px" }
        );

        nodes.forEach(function (n) { observer.observe(n); });
    };

    /* -----------------------------------------------------------------
     * 3. Smooth scroll for in-page anchors
     * --------------------------------------------------------------- */
    vb.initSmoothAnchors = function () {
        var reduced = window.matchMedia("(prefers-reduced-motion: reduce)").matches;
        document.querySelectorAll('a[href^="#"]').forEach(function (anchor) {
            anchor.addEventListener("click", function (e) {
                var href = anchor.getAttribute("href");
                if (!href || href.length < 2) return;
                var target = document.querySelector(href);
                if (!target) return;
                e.preventDefault();
                target.scrollIntoView({
                    behavior: reduced ? "auto" : "smooth",
                    block: "start"
                });
                // Move keyboard focus to the target for screen readers.
                var prevTabIndex = target.getAttribute("tabindex");
                if (!prevTabIndex) target.setAttribute("tabindex", "-1");
                target.focus({ preventScroll: true });
            });
        });
    };

    /* -----------------------------------------------------------------
     * 4. Hero curtain reveal
     * --------------------------------------------------------------- */
    vb.initCurtain = function () {
        var curtain = document.querySelector("[data-curtain]");
        if (!curtain) return;
        if (window.matchMedia("(prefers-reduced-motion: reduce)").matches) {
            curtain.remove();
            return;
        }

        var opened = false;
        function reveal() {
            if (opened) return;
            opened = true;
            curtain.classList.add("is-open");
            window.setTimeout(function () {
                if (curtain && curtain.parentNode) curtain.parentNode.removeChild(curtain);
            }, 1800);
        }

        // Reveal after a brief delay so the user perceives the curtain.
        window.setTimeout(reveal, 600);
        // Or on first interaction.
        ["scroll", "keydown", "click", "touchstart"].forEach(function (ev) {
            window.addEventListener(ev, reveal, { once: true, passive: true });
        });
    };

    /* -----------------------------------------------------------------
     * 5. Countdown to a target ISO date
     * --------------------------------------------------------------- */
    vb.initCountdown = function () {
        var nodes = document.querySelectorAll("[data-countdown]");
        if (nodes.length === 0) return;

        nodes.forEach(function (root) {
            var targetIso = root.getAttribute("data-countdown");
            if (!targetIso) return;
            var target = new Date(targetIso);
            if (isNaN(target.getTime())) return;

            var live = root.querySelector("[data-countdown-live]");
            var numDay = root.querySelector("[data-countdown-day]");
            var numHour = root.querySelector("[data-countdown-hour]");
            var numMin = root.querySelector("[data-countdown-min]");
            var numSec = root.querySelector("[data-countdown-sec]");
            var label = root.getAttribute("data-countdown-label") || "noch";

            function pad(n) { return n < 10 ? "0" + n : String(n); }

            function tick() {
                var now = Date.now();
                var diff = target.getTime() - now;
                if (diff <= 0) {
                    if (numDay) numDay.textContent = "0";
                    if (numHour) numHour.textContent = "00";
                    if (numMin) numMin.textContent = "00";
                    if (numSec) numSec.textContent = "00";
                    if (live) live.textContent = "Beginn: jetzt";
                    return false;
                }
                var secTotal = Math.floor(diff / 1000);
                var days = Math.floor(secTotal / 86400);
                var hours = Math.floor((secTotal % 86400) / 3600);
                var mins = Math.floor((secTotal % 3600) / 60);
                var secs = secTotal % 60;

                if (numDay) numDay.textContent = days;
                if (numHour) numHour.textContent = pad(hours);
                if (numMin) numMin.textContent = pad(mins);
                if (numSec) numSec.textContent = pad(secs);

                if (live) {
                    live.textContent =
                        label + " " +
                        days + " Tage, " +
                        pad(hours) + ":" + pad(mins) + ":" + pad(secs) +
                        " bis zum Beginn";
                }
            }

            tick();
            var intervalId = window.setInterval(tick, 1000);
            // Pause when tab is hidden to save CPU.
            document.addEventListener("visibilitychange", function () {
                if (document.hidden) {
                    window.clearInterval(intervalId);
                } else {
                    tick();
                    intervalId = window.setInterval(tick, 1000);
                }
            });
        });
    };

    /* -----------------------------------------------------------------
     * 6. Gallery lightbox
     * --------------------------------------------------------------- */
    vb.initLightbox = function () {
        var gallery = document.querySelector("[data-gallery]");
        var dialog = document.querySelector("[data-lightbox]");
        if (!gallery || !dialog || typeof dialog.showModal !== "function") return;

        var imgEl = dialog.querySelector("[data-lightbox-img]");
        var capEl = dialog.querySelector("[data-lightbox-cap]");
        var closeBtn = dialog.querySelector("[data-lightbox-close]");

        gallery.addEventListener("click", function (event) {
            var trigger = event.target.closest("[data-gallery-item]");
            if (!trigger) return;
            event.preventDefault();
            var src = trigger.getAttribute("data-full") || trigger.getAttribute("href") || trigger.querySelector("img")?.src;
            var caption = trigger.getAttribute("data-caption") || trigger.querySelector("img")?.alt || "";
            if (!src || !imgEl) return;
            imgEl.src = src;
            imgEl.alt = caption;
            if (capEl) capEl.textContent = caption;
            dialog.showModal();
        });

        if (closeBtn) closeBtn.addEventListener("click", function () { dialog.close(); });
        dialog.addEventListener("click", function (e) {
            if (e.target === dialog) dialog.close();
        });
    };

    /* -----------------------------------------------------------------
     * 7. Boot
     * --------------------------------------------------------------- */
    function ready(fn) {
        if (document.readyState !== "loading") fn();
        else document.addEventListener("DOMContentLoaded", fn);
    }

    ready(function () {
        vb.initMobileNav();
        vb.initReveal();
        vb.initSmoothAnchors();
        vb.initCurtain();
        vb.initCountdown();
        vb.initLightbox();
    });
})();