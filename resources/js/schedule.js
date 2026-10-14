import { revealElements } from "./motion.js";
export function selectSchedules(cards, month, destination, limit = Infinity) {
    const matching = cards.filter(
        (card) =>
            card.month === month &&
            (destination === "all" || card.destination === destination),
    );
    return { total: matching.length, visible: matching.slice(0, limit) };
}
export function initSchedules() {
    document.querySelectorAll("[data-schedule]").forEach((root) => {
        const monthButtons = Array.from(
            root.querySelectorAll("[data-schedule-month]"),
        );
        const filters = Array.from(
            root.querySelectorAll("[data-schedule-destination]"),
        );
        const cards = Array.from(
            root.querySelectorAll("[data-schedule-card]"),
        ).map((element) => ({
            element,
            month: element.dataset.month,
            destination: element.dataset.destination,
        }));
        const more = root.querySelector("[data-schedule-more]");
        const empty = root.querySelector("[data-schedule-empty]");
        const panel = root.querySelector("#schedule-results");
        const count = root.querySelector("[data-schedule-count]");
        const pageSize = Number(root.dataset.pageSize) || 6;
        let month = monthButtons[0]?.dataset.scheduleMonth;
        let destination = "all";
        let expanded = false;
        const render = (animate) => {
            const result = selectSchedules(
                cards,
                month,
                destination,
                expanded ? Infinity : pageSize,
            );
            const visible = new Set(result.visible);
            cards.forEach((card) => {
                card.element.hidden = !visible.has(card);
            });
            const selected = monthButtons.find(
                (button) => button.dataset.scheduleMonth === month,
            );
            monthButtons.forEach((button) => {
                const active = button === selected;
                button.setAttribute("aria-selected", String(active));
                button.tabIndex = active ? 0 : -1;
            });
            filters.forEach((button) =>
                button.setAttribute(
                    "aria-pressed",
                    String(button.dataset.scheduleDestination === destination),
                ),
            );
            panel.setAttribute("aria-labelledby", selected.id);
            count.textContent =
                result.total + " keberangkatan " + selected.dataset.label;
            empty.hidden = result.total !== 0;
            more.hidden = result.total <= pageSize;
            more.textContent = expanded
                ? "Tampilkan lebih sedikit"
                : "Tampilkan semua " + result.total + " keberangkatan";
            more.setAttribute("aria-expanded", String(expanded));
            if (animate)
                revealElements(
                    result.visible.map((card) => card.element),
                    45,
                );
        };
        monthButtons.forEach((button) => {
            button.addEventListener("click", () => {
                month = button.dataset.scheduleMonth;
                expanded = false;
                render(true);
            });
            button.addEventListener("keydown", (event) => {
                const index = monthButtons.indexOf(button);
                let target;
                if (event.key === "ArrowRight")
                    target = (index + 1) % monthButtons.length;
                if (event.key === "ArrowLeft")
                    target =
                        (index - 1 + monthButtons.length) % monthButtons.length;
                if (event.key === "Home") target = 0;
                if (event.key === "End") target = monthButtons.length - 1;
                if (target === undefined) return;
                event.preventDefault();
                monthButtons[target].focus();
                month = monthButtons[target].dataset.scheduleMonth;
                expanded = false;
                render(true);
            });
        });
        filters.forEach((button) =>
            button.addEventListener("click", () => {
                destination = button.dataset.scheduleDestination;
                expanded = false;
                render(true);
            }),
        );
        more.addEventListener("click", () => {
            expanded = !expanded;
            render(true);
        });
        root.querySelector("[data-schedule-controls]").hidden = false;
        render(false);
    });
}
