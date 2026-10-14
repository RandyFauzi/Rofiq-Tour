import test from "node:test";
import assert from "node:assert/strict";
globalThis.window = {
    matchMedia: () => ({ matches: true, addEventListener() {} }),
};
const { selectSchedules, initSchedules } =
    await import("../../resources/js/schedule.js");
const { initFeatureCarousels } = await import("../../resources/js/carousel.js");
const { initRegistration } = await import("../../resources/js/registration.js");
class Element {
    constructor(dataset = {}) {
        this.dataset = dataset;
        this.attributes = {};
        this.events = {};
        this.hidden = false;
        this.classNames = new Set();
        this.classList = {
            add: (name) => this.classNames.add(name),
            toggle: (name, enabled) =>
                enabled
                    ? this.classNames.add(name)
                    : this.classNames.delete(name),
        };
    }
    addEventListener(type, handler) {
        (this.events[type] ??= []).push(handler);
    }
    fire(type, event = {}) {
        this.events[type]?.forEach((handler) =>
            handler({ preventDefault() {}, ...event }),
        );
    }
    setAttribute(key, value) {
        this.attributes[key] = value;
    }
    getAttribute(key) {
        return this.attributes[key];
    }
    querySelector(selector) {
        return this.children?.[selector] ?? null;
    }
    querySelectorAll(selector) {
        return this.groups?.[selector] ?? [];
    }
    focus() {
        this.focused = true;
    }
    contains(target) {
        return target === this;
    }
}
const fixtures = () => [
    ...Array.from({ length: 8 }, (_, index) => ({
        id: index,
        month: "2026-11",
        destination: index < 4 ? "thaif" : "turki",
    })),
    ...Array.from({ length: 3 }, (_, index) => ({
        id: 8 + index,
        month: "2026-12",
        destination: "dubai",
    })),
];
test("month and program filters keep accurate totals before pagination", () => {
    const all = selectSchedules(fixtures(), "2026-11", "all", 6);
    assert.equal(all.total, 8);
    assert.equal(all.visible.length, 6);
    const filtered = selectSchedules(fixtures(), "2026-11", "turki", 6);
    assert.equal(filtered.total, 4);
    assert.ok(filtered.visible.every((card) => card.destination === "turki"));
    assert.equal(selectSchedules(fixtures(), "2026-12", "turki").total, 0);
    assert.equal(
        selectSchedules(fixtures(), "2026-11", "all").visible.length,
        8,
    );
});
test("tabs, destination filters and show-all update visible cards and accessible states", () => {
    const root = new Element({ pageSize: "6" });
    const months = [
        new Element({ scheduleMonth: "2026-11", label: "November 2026" }),
        new Element({ scheduleMonth: "2026-12", label: "Desember 2026" }),
    ];
    months.forEach((element, index) => {
        element.id = "month-" + index;
    });
    const filters = ["all", "thaif", "turki", "dubai"].map(
        (destination) => new Element({ scheduleDestination: destination }),
    );
    const cards = fixtures().map(
        (card) =>
            new Element({ month: card.month, destination: card.destination }),
    );
    const more = new Element(),
        empty = new Element(),
        panel = new Element(),
        count = new Element(),
        controls = new Element();
    root.groups = {
        "[data-schedule-month]": months,
        "[data-schedule-destination]": filters,
        "[data-schedule-card]": cards,
    };
    root.children = {
        "[data-schedule-more]": more,
        "[data-schedule-empty]": empty,
        "#schedule-results": panel,
        "[data-schedule-count]": count,
        "[data-schedule-controls]": controls,
    };
    globalThis.document = { querySelectorAll: () => [root] };
    initSchedules();
    assert.equal(cards.filter((card) => !card.hidden).length, 6);
    assert.equal(count.textContent, "8 keberangkatan November 2026");
    more.fire("click");
    assert.equal(cards.filter((card) => !card.hidden).length, 8);
    assert.equal(more.getAttribute("aria-expanded"), "true");
    months[1].fire("click");
    assert.equal(cards.filter((card) => !card.hidden).length, 3);
    assert.equal(months[1].getAttribute("aria-selected"), "true");
    filters[2].fire("click");
    assert.equal(cards.filter((card) => !card.hidden).length, 0);
    assert.equal(empty.hidden, false);
    assert.equal(filters[2].getAttribute("aria-pressed"), "true");
    months[1].fire("keydown", { key: "ArrowLeft" });
    assert.equal(months[0].focused, true);
    assert.equal(cards.filter((card) => !card.hidden).length, 4);
});
test("carousel buttons, dots and swipe wrap slides with reduced motion respected", () => {
    const root = new Element(),
        play = new Element(),
        previous = new Element(),
        next = new Element(),
        status = new Element();
    play.children = { path: new Element() };
    const slides = [new Element(), new Element(), new Element()];
    slides.forEach((slide, index) =>
        slide.setAttribute("aria-label", "Banner " + index),
    );
    const dots = slides.map(
        (_, index) => new Element({ carouselDot: String(index) }),
    );
    root.groups = { "[data-slide]": slides, "[data-carousel-dot]": dots };
    root.children = {
        "[data-carousel-play]": play,
        "[data-carousel-status]": status,
        "[data-carousel-prev]": previous,
        "[data-carousel-next]": next,
    };
    globalThis.document = {
        querySelectorAll: () => [root],
        addEventListener() {},
        hidden: false,
    };
    initFeatureCarousels();
    assert.equal(play.disabled, true);
    assert.equal(slides[0].inert, false);
    assert.equal(slides[1].inert, true);
    previous.fire("click");
    assert.equal(slides[2].inert, false);
    assert.equal(dots[2].getAttribute("aria-current"), "true");
    next.fire("click");
    assert.equal(slides[0].inert, false);
    dots[1].fire("click");
    assert.equal(status.textContent, "Banner 1");
    root.fire("pointerdown", {
        pointerType: "touch",
        clientX: 200,
        clientY: 10,
    });
    root.fire("pointerup", { clientX: 100, clientY: 12 });
    assert.equal(slides[2].inert, false);
});

test("reveal uses a gentle transition and skips animation under reduced motion", async () => {
    const originalMatchMedia = window.matchMedia;
    const preference = { matches: false };
    window.matchMedia = () => preference;
    const { revealElements } =
        await import("../../resources/js/motion.js?timing-test");
    window.matchMedia = originalMatchMedia;
    const calls = [];
    const elements = [0, 1].map(() => ({
        hidden: false,
        dataset: {},
        getAnimations: () => [],
        animate: (frames, options) => {
            calls.push({ frames, options });
            return {};
        },
    }));
    revealElements(elements);
    assert.equal(calls.length, 2);
    assert.equal(calls[0].options.duration, 1050);
    assert.ok(calls[1].options.delay > calls[0].options.delay);
    assert.equal(calls[0].frames.at(-1).opacity, 1);
    preference.matches = true;
    revealElements(elements);
    assert.equal(calls.length, 2);
});
test("registration keyboard selection updates action and explanation", () => {
    const root = new Element();
    const buttons = Array.from({ length: 4 }, (_, index) => new Element({ step: String(index), detail: `Detail ${index}`, action: `Action ${index}`, url: `/step/${index}` }));
    const track = new Element();
    track.style = { setProperty(key, value) { this[key] = value; } };
    const action = new Element();
    action.firstChild = { textContent: "" };
    const description = new Element();
    root.groups = { "[data-step]": buttons };
    root.children = { ".journey-track": track, "[data-journey-description]": description, ".journey-action": action, ".journey-detail": new Element() };
    globalThis.document = { querySelectorAll: selector => selector === "[data-journey]" ? [root] : [] };
    initRegistration();
    buttons[0].fire("keydown", { key: "End" });
    assert.equal(buttons[3].attributes["aria-pressed"], "true");
    assert.equal(buttons[3].focused, true);
    assert.equal(description.textContent, "Detail 3");
    assert.equal(action.href, "/step/3");
    buttons[3].fire("keydown", { key: "ArrowRight" });
    assert.equal(buttons[0].attributes["aria-pressed"], "true");
});

test("cost selector shows matching package and FAQ closes other answers", () => {
    const costs = new Element();
    const select = new Element();
    select.value = "1";
    const count = new Element();
    const panels = [new Element({ costPanel: "1" }), new Element({ costPanel: "2" })];
    panels[0].groups = { ".cost-card": [new Element()] };
    panels[1].groups = { ".cost-card": [new Element(), new Element()] };
    panels.forEach(panel => panel.children = { ".cost-exclusions": new Element() });
    costs.groups = { "[data-cost-panel]": panels };
    costs.children = { "[data-cost-select]": select, "[data-cost-count]": count };
    const faq = new Element();
    const questions = [new Element(), new Element()];
    faq.groups = { details: questions };
    questions.forEach(item => item.children = { ".faq-answer": new Element() });
    globalThis.document = { querySelectorAll: selector => selector === "[data-costs]" ? [costs] : selector === "[data-faq-group]" ? [faq] : [] };
    initRegistration();
    assert.equal(panels[1].hidden, true);
    select.value = "2";
    select.fire("change");
    assert.equal(panels[0].hidden, true);
    assert.equal(panels[1].hidden, false);
    assert.equal(count.textContent, "2 fasilitas termasuk");
    questions.forEach(item => item.open = true);
    questions[1].fire("toggle");
    assert.equal(questions[0].open, false);
    assert.equal(questions[1].open, true);
});
