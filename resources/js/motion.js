const motionPreference = window.matchMedia("(prefers-reduced-motion: reduce)");
export function revealElements(elements, stagger = 85) {
    if (motionPreference.matches) return;
    elements.forEach((element, index) => {
        if (element.hidden || !element.animate) return;
        element
            .getAnimations()
            .filter((animation) => animation.id === "scroll-reveal")
            .forEach((animation) => animation.cancel());
        const direction = element.dataset.reveal;
        const start =
            direction === "left"
                ? "translate3d(-26px,0,0)"
                : direction === "right"
                  ? "translate3d(26px,0,0)"
                  : "translate3d(0,22px,0)";
        const animation = element.animate(
            [
                { opacity: 0, transform: start },
                { opacity: 1, transform: "translate3d(0,0,0)" },
            ],
            {
                duration: 1050,
                delay: Math.min(index * stagger, 380),
                easing: "cubic-bezier(.16,1,.3,1)",
                fill: "backwards",
            },
        );
        animation.id = "scroll-reveal";
    });
}
export function initMotion() {
    const body = document.body;
    const header = document.querySelector(".site-header");
    const syncHeader = () =>
        header?.classList.toggle("is-scrolled", window.scrollY > 20);
    window.addEventListener("scroll", syncHeader, { passive: true });
    syncHeader();
    const selectors = [
        "[data-reveal]",
        ".nav-wrap > *",
        ".hero-copy > *",
        ".hero-visual",
        ".hero-bottom > *",
        ".airline-strip-heading",
        ".airline-window",
        ".feature-carousel",
        ".feature-footnote",
        ".page-hero .container > *",
        ".section-heading > *",
        ".center-heading > *",
        ".package-card",
        ".why-photo",
        ".why-copy > *",
        ".benefit",
        ".schedule-filter-panel",
        ".schedule-card",
        ".step",
        ".journey-card",
        ".accordion details",
        ".article-card",
        ".quote-card",
        ".social-copy > *",
        ".office-card",
        ".contact-aside > *",
        ".consultation-copy > *",
        ".consultation-form",
        ".detail-main > *",
        ".detail-sidebar",
        ".rate-schedule",
        ".filter-bar",
        ".results-header",
        ".gallery-item",
        ".prose > *",
        ".closing-cta .container > *",
        ".footer-grid > *",
        ".footer-bottom > *",
    ].join(",");
    const candidates = [];
    document.querySelectorAll(selectors).forEach((element) => {
        if (element.parentElement.closest("[data-motion-target]")) return;
        element.dataset.motionTarget = "true";
        candidates.push(element);
    });
    let observer;
    const syncPreference = () => {
        observer?.disconnect();
        body.classList.toggle("motion-enabled", !motionPreference.matches);
        if (motionPreference.matches) {
            candidates.forEach((element) =>
                element
                    .getAnimations?.()
                    .filter((animation) => animation.id === "scroll-reveal")
                    .forEach((animation) => animation.cancel()),
            );
            return;
        }
        if (!("IntersectionObserver" in window)) return;
        observer = new IntersectionObserver(
            (entries) => {
                const visible = entries
                    .filter((entry) => entry.isIntersecting)
                    .map((entry) => entry.target);
                revealElements(visible);
                visible.forEach((element) => {
                    element.dataset.motionRevealed = "true";
                    observer.unobserve(element);
                });
            },
            { threshold: 0.03, rootMargin: "0px 0px 35px 0px" },
        );
        candidates
            .filter((element) => !element.dataset.motionRevealed)
            .forEach((element) => observer.observe(element));
    };
    syncPreference();
    motionPreference.addEventListener("change", syncPreference);
    if ("IntersectionObserver" in window) {
        const floatingObserver = new IntersectionObserver((entries) => {
            entries.forEach((entry) =>
                entry.target.classList.toggle(
                    "is-offscreen",
                    !entry.isIntersecting,
                ),
            );
        });
        document
            .querySelectorAll(".hero-visual,.social-art")
            .forEach((element) => floatingObserver.observe(element));
    }
    document.addEventListener("visibilitychange", () =>
        body.classList.toggle("motion-paused", document.hidden),
    );
}
