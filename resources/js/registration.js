import { revealElements } from './motion.js';

export function initRegistration() {
    document.querySelectorAll('[data-journey]').forEach(section => {
        const buttons = [...section.querySelectorAll('[data-step]')];
        function select(button) {
            buttons.forEach(item => {
                item.classList.toggle('is-active', item === button);
                item.setAttribute('aria-pressed', String(item === button));
            });
            section.querySelector('.journey-track').style.setProperty('--journey-progress', String(Number(button.dataset.step) / (buttons.length - 1)));
            section.querySelector('[data-journey-description]').textContent = button.dataset.detail;
            const action = section.querySelector('.journey-action');
            action.firstChild.textContent = `${button.dataset.action} `;
            action.href = button.dataset.url;
            revealElements([section.querySelector('.journey-detail')], 0);
        }
        buttons.forEach((button, index) => {
            button.addEventListener('click', () => select(button));
            button.addEventListener('keydown', event => {
                let next;
                if (event.key === 'ArrowRight') next = (index + 1) % buttons.length;
                if (event.key === 'ArrowLeft') next = (index - 1 + buttons.length) % buttons.length;
                if (event.key === 'Home') next = 0;
                if (event.key === 'End') next = buttons.length - 1;
                if (next !== undefined) {
                    event.preventDefault();
                    buttons[next].focus();
                    select(buttons[next]);
                }
            });
        });
    });
    document.querySelectorAll('[data-costs]').forEach(section => {
        const select = section.querySelector('[data-cost-select]');
        const panels = [...section.querySelectorAll('[data-cost-panel]')];
        const sync = (animate = false) => {
            panels.forEach(panel => {
                panel.hidden = panel.dataset.costPanel !== select.value;
                if (!panel.hidden) {
                    section.querySelector('[data-cost-count]').textContent = `${panel.querySelectorAll('.cost-card').length} fasilitas termasuk`;
                    if (animate) revealElements([...panel.querySelectorAll('.cost-card'), panel.querySelector('.cost-exclusions')], 45);
                }
            });
        };
        section.classList.add('is-enhanced');
        sync();
        select.addEventListener('change', () => sync(true));
    });
    document.querySelectorAll('[data-faq-group]').forEach(group => {
        group.querySelectorAll('details').forEach(item => {
            item.addEventListener('toggle', () => {
                if (!item.open) return;
                group.querySelectorAll('details').forEach(other => {
                    if (other !== item) other.open = false;
                });
                revealElements([item.querySelector('.faq-answer')], 0);
            });
        });
    });
}
