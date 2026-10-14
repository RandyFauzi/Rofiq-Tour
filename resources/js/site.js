import { initRegistration } from "./registration.js";
import { initMotion } from "./motion.js";
import { initAirlineMarquee, initFeatureCarousels } from "./carousel.js";
import { initSchedules } from "./schedule.js";

document.querySelector(".menu-toggle")?.addEventListener("click", () => {
    const menu = document.querySelector("#site-nav");
    const toggle = document.querySelector(".menu-toggle");
    const expanded = toggle.getAttribute("aria-expanded") !== "true";
    toggle.setAttribute("aria-expanded", String(expanded));
    toggle.setAttribute(
        "aria-label",
        expanded ? "Tutup navigasi" : "Buka navigasi",
    );
    menu.classList.toggle("open", expanded);
});

if (document.body.classList.contains("public-site")) {
    initRegistration();
    initSchedules();
    initAirlineMarquee();
    initFeatureCarousels();
    initMotion();
}
document.addEventListener("keydown", (event) => {
    if (event.key === "Escape") {
        document.querySelector("#site-nav")?.classList.remove("open");
        document
            .querySelector(".menu-toggle")
            ?.setAttribute("aria-expanded", "false");
    }
});
const lightbox = document.querySelector("#lightbox");
document.querySelectorAll("[data-lightbox]").forEach((button) => {
    button.addEventListener("click", () => {
        lightbox.querySelector("img").src = button.dataset.lightbox;
        lightbox.querySelector("img").alt = button.dataset.caption;
        lightbox.querySelector("p").textContent = button.dataset.caption;
        lightbox.showModal();
    });
});
document
    .querySelector(".lightbox-close")
    ?.addEventListener("click", () => lightbox.close());
lightbox?.addEventListener("click", (event) => {
    if (event.target === lightbox) lightbox.close();
});
document.querySelector("#package_id")?.addEventListener("change", (event) => {
    const departure = document.querySelector("#selected-departure");
    if (departure)
        departure.disabled = departure.dataset.package !== event.target.value;
});
document.querySelectorAll("form[data-confirm]").forEach((form) => {
    form.addEventListener("submit", (event) => {
        if (!window.confirm(form.dataset.confirm)) event.preventDefault();
    });
});
document.querySelectorAll("form").forEach((form) => {
    form.addEventListener("submit", (event) => {
        if (
            event.defaultPrevented ||
            form.method.toLowerCase() !== "post" ||
            !form.checkValidity()
        )
            return;
        const button = form.querySelector(
            "button[type=submit], button:not([type])",
        );
        if (button) {
            setTimeout(() => {
                button.disabled = true;
                button.textContent = "Memproses…";
            }, 0);
        }
    });
});
