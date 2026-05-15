"use strict";

(function () {
    var header = document.querySelector("[data-header]");
    var nav = document.querySelector("[data-nav]");
    var navToggle = document.querySelector("[data-nav-toggle]");

    if (header && nav && navToggle) {
        function setOpen(isOpen) {
            header.setAttribute("data-nav-open", isOpen ? "true" : "false");
            navToggle.setAttribute("aria-expanded", isOpen ? "true" : "false");
        }

        setOpen(false);

        navToggle.addEventListener("click", function () {
            var isExpanded = navToggle.getAttribute("aria-expanded") === "true";
            setOpen(!isExpanded);
        });

        document.addEventListener("click", function (event) {
            var target = event.target;
            if (!(target instanceof Element)) {
                return;
            }
            if (!header.contains(target)) {
                setOpen(false);
            }
        });

        document.addEventListener("keydown", function (event) {
            if (event.key === "Escape") {
                setOpen(false);
                navToggle.focus();
            }
        });

        nav.querySelectorAll("a").forEach(function (link) {
            link.addEventListener("click", function () {
                setOpen(false);
            });
        });

        window.addEventListener("resize", function () {
            if (window.matchMedia("(min-width: 48rem)").matches) {
                setOpen(false);
            }
        });
    }

    var revealNodes = document.querySelectorAll("[data-reveal]");
    if (revealNodes.length > 0 && "IntersectionObserver" in window) {
        var revealObserver = new IntersectionObserver(
            function (entries, observer) {
                entries.forEach(function (entry) {
                    if (!entry.isIntersecting) {
                        return;
                    }
                    entry.target.classList.add("is-visible");
                    observer.unobserve(entry.target);
                });
            },
            {
                threshold: 0.15,
                rootMargin: "0px 0px -8% 0px",
            }
        );

        revealNodes.forEach(function (node) {
            revealObserver.observe(node);
        });
    } else {
        revealNodes.forEach(function (node) {
            node.classList.add("is-visible");
        });
    }

    document.querySelectorAll('a[href^="#"]').forEach(function (anchor) {
        anchor.addEventListener("click", function (e) {
            var targetId = anchor.getAttribute("href");
            if (targetId.length < 2) {
                return;
            }
            var target = document.querySelector(targetId);
            if (target) {
                e.preventDefault();
                target.scrollIntoView({ behavior: "smooth", block: "start" });
                target.focus({ preventScroll: true });
            }
        });
    });
})();
