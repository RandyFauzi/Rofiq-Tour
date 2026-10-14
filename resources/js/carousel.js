const preference = window.matchMedia("(prefers-reduced-motion: reduce)");
function setPlayIcon(button, paused) {
    const path = button?.querySelector("path");
    if (path)
        path.setAttribute(
            "d",
            paused ? "m8 4 12 8-12 8V4Z" : "M8 5v14M16 5v14",
        );
}
export function initAirlineMarquee() {
    document.querySelectorAll("[data-marquee]").forEach((root) => {
        const button = root.querySelector("[data-marquee-toggle]");
        let userPaused = false;
        let inView = !("IntersectionObserver" in window);
        let hovered = false;
        const sync = () => {
            const paused =
                userPaused ||
                !inView ||
                hovered ||
                document.hidden ||
                preference.matches;
            root.classList.add("marquee-ready");
            root.classList.toggle("marquee-paused", paused);
            button.setAttribute("aria-pressed", String(userPaused));
            button.setAttribute(
                "aria-label",
                userPaused ? "Lanjutkan gerakan logo" : "Jeda gerakan logo",
            );
            setPlayIcon(button, userPaused);
        };
        button.addEventListener("click", () => {
            userPaused = !userPaused;
            sync();
        });
        root.addEventListener("mouseenter", () => {
            hovered = true;
            sync();
        });
        root.addEventListener("mouseleave", () => {
            hovered = false;
            sync();
        });
        document.addEventListener("visibilitychange", sync);
        preference.addEventListener("change", sync);
        if ("IntersectionObserver" in window) {
            new IntersectionObserver((entries) => {
                inView = entries[0].isIntersecting;
                sync();
            }).observe(root);
        }
        sync();
    });
}
export function initFeatureCarousels() {
    document.querySelectorAll("[data-carousel]").forEach((root) => {
        const slides = Array.from(root.querySelectorAll("[data-slide]"));
        const dots = Array.from(root.querySelectorAll("[data-carousel-dot]"));
        const play = root.querySelector("[data-carousel-play]");
        const status = root.querySelector("[data-carousel-status]");
        if (!slides.length) return;
        let current = 0;
        let wantsPlay = !preference.matches && slides.length > 1;
        let hovered = false;
        let focused = false;
        let inView = !("IntersectionObserver" in window);
        let timer;
        let pointerStart;
        const schedule = () => {
            clearTimeout(timer);
            const paused = !wantsPlay || preference.matches;
            play.disabled = preference.matches || slides.length < 2;
            play.setAttribute("aria-pressed", String(paused));
            play.setAttribute(
                "aria-label",
                paused
                    ? "Mulai pergantian otomatis"
                    : "Jeda pergantian otomatis",
            );
            setPlayIcon(play, paused);
            if (paused || hovered || focused || !inView || document.hidden)
                return;
            timer = setTimeout(() => show(current + 1), 3500);
        };
        const show = (index, announce = false) => {
            current = (index + slides.length) % slides.length;
            slides.forEach((slide, position) => {
                const active = position === current;
                slide.classList.toggle("is-active", active);
                slide.setAttribute("aria-hidden", String(!active));
                slide.inert = !active;
            });
            dots.forEach((dot, position) =>
                dot.setAttribute("aria-current", String(position === current)),
            );
            if (announce)
                status.textContent = slides[current].getAttribute("aria-label");
            schedule();
        };
        const manual = (index) => {
            wantsPlay = false;
            show(index, true);
        };
        root.querySelector("[data-carousel-prev]").addEventListener(
            "click",
            () => manual(current - 1),
        );
        root.querySelector("[data-carousel-next]").addEventListener(
            "click",
            () => manual(current + 1),
        );
        dots.forEach((dot, index) =>
            dot.addEventListener("click", () => manual(index)),
        );
        play.addEventListener("click", () => {
            wantsPlay = !wantsPlay;
            schedule();
        });
        root.addEventListener("keydown", (event) => {
            if (event.key === "ArrowLeft") {
                event.preventDefault();
                manual(current - 1);
            }
            if (event.key === "ArrowRight") {
                event.preventDefault();
                manual(current + 1);
            }
            if (event.key === "Home") {
                event.preventDefault();
                manual(0);
            }
            if (event.key === "End") {
                event.preventDefault();
                manual(slides.length - 1);
            }
        });
        root.addEventListener("pointerdown", (event) => {
            if (event.pointerType !== "mouse")
                pointerStart = { x: event.clientX, y: event.clientY };
        });
        root.addEventListener("pointerup", (event) => {
            if (!pointerStart) return;
            const dx = event.clientX - pointerStart.x;
            const dy = event.clientY - pointerStart.y;
            pointerStart = null;
            if (Math.abs(dx) > 45 && Math.abs(dx) > Math.abs(dy))
                manual(current + (dx < 0 ? 1 : -1));
        });
        root.addEventListener("pointercancel", () => {
            pointerStart = null;
        });
        root.addEventListener("mouseenter", () => {
            hovered = true;
            schedule();
        });
        root.addEventListener("mouseleave", () => {
            hovered = false;
            schedule();
        });
        root.addEventListener("focusin", () => {
            focused = true;
            schedule();
        });
        root.addEventListener("focusout", (event) => {
            focused = root.contains(event.relatedTarget);
            schedule();
        });
        document.addEventListener("visibilitychange", schedule);
        preference.addEventListener("change", () => {
            if (preference.matches) wantsPlay = false;
            schedule();
        });
        if ("IntersectionObserver" in window) {
            new IntersectionObserver(
                (entries) => {
                    inView = entries[0].isIntersecting;
                    schedule();
                },
                { threshold: 0.15 },
            ).observe(root);
        }
        root.classList.add("is-enhanced");
        if (slides.length === 1) {
            root.querySelector(".carousel-controls").hidden = true;
            root.querySelector(".carousel-dots").hidden = true;
        }
        show(0);
    });
}
