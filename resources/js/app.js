import './bootstrap';

import Alpine from 'alpinejs';

window.Alpine = Alpine;

Alpine.data('serviceSearch', () => ({
    query: '',
    filter() {
        const q = this.query.toLowerCase().trim();

        document.querySelectorAll('.service-card').forEach((card) => {
            const matches = card.dataset.name.includes(q);
            card.classList.toggle('hidden', !matches);
        });

        const empty = document.querySelector('[data-search-empty]');
        const visible = [...document.querySelectorAll('.service-card:not(.hidden)')].length;
        if (empty) {
            empty.classList.toggle('hidden', visible !== 0);
        }
    },
}));

Alpine.data('confirmAction', (message = 'Are you sure?') => ({
    submit(event) {
        if (!window.confirm(message)) {
            event.preventDefault();
        }
    },
}));

Alpine.start();

window.addEventListener('load', () => {
    document.documentElement.classList.add('js-ready');
});
