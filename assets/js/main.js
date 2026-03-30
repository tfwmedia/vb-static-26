"use strict";

(function () {
    const header = document.querySelector("[data-header]");
    const nav = document.querySelector("[data-nav]");
    const navToggle = document.querySelector("[data-nav-toggle]");

    if (header && nav && navToggle) {
        const setOpen = (isOpen) => {
            header.setAttribute("data-nav-open", isOpen ? "true" : "false");
            navToggle.setAttribute("aria-expanded", isOpen ? "true" : "false");
        };

        setOpen(false);

        navToggle.addEventListener("click", () => {
            const isExpanded = navToggle.getAttribute("aria-expanded") === "true";
            setOpen(!isExpanded);
        });

        document.addEventListener("keydown", (event) => {
            if (event.key === "Escape") {
                setOpen(false);
                navToggle.focus();
            }
        });

        nav.querySelectorAll("a").forEach((link) => {
            link.addEventListener("click", () => setOpen(false));
        });
    }

    const revealNodes = document.querySelectorAll("[data-reveal]");
    if (revealNodes.length > 0 && "IntersectionObserver" in window) {
        const revealObserver = new IntersectionObserver(
            (entries, observer) => {
                entries.forEach((entry) => {
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

        revealNodes.forEach((node) => revealObserver.observe(node));
    } else {
        revealNodes.forEach((node) => node.classList.add("is-visible"));
    }
})();
