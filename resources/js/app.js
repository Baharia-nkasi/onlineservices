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

function setLiveFieldState(input, messageElement, valid, message = '') {
    if (!input || !messageElement) return;
    input.classList.toggle('border-red-400', !valid);
    input.classList.toggle('ring-2', !valid);
    input.classList.toggle('ring-red-100', !valid);
    input.classList.toggle('border-emerald-400', valid);
    messageElement.textContent = message;
    input.setAttribute('aria-invalid', valid ? 'false' : 'true');
}

function initLiveValidation() {
    document.querySelectorAll('form[data-live-validate="login"]').forEach((form) => {
        if (form.dataset.liveReady === '1') return;
        form.dataset.liveReady = '1';

        const email = form.querySelector('#email');
        const name = form.querySelector('#name');
        const password = form.querySelector('#password');
        const confirmation = form.querySelector('#password_confirmation');
        const nameError = form.querySelector('#name-live-error');
        const emailError = form.querySelector('#email-live-error');
        const passwordError = form.querySelector('#password-live-error');

        const validateEmail = () => {
            const value = email?.value.trim() ?? '';
            if (!value) {
                setLiveFieldState(email, emailError, false, 'Email is required.');
                return false;
            }
            const valid = /^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(value);
            setLiveFieldState(email, emailError, valid, valid ? '' : 'Enter a valid email address.');
            return valid;
        };

        const validatePassword = () => {
            const value = password?.value ?? '';
            const valid = form.dataset.liveValidate === 'login' ? value.length > 0 : value.length >= 8;
            setLiveFieldState(password, passwordError, valid, valid ? '' : 'Password must contain at least 8 characters.');
            return valid;
        };

        const validateName = () => {
            if (!name) return true;
            const value = name.value.trim();
            const valid = value.length >= 2 && value.length <= 255;
            setLiveFieldState(name, nameError, valid, valid ? '' : 'Name must contain at least 2 characters.');
            return valid;
        };

        const validateConfirmation = () => {
            if (!confirmation) return true;
            const valid = confirmation.value.length > 0 && confirmation.value === password?.value;
            const error = form.querySelector('#password-confirmation-live-error');
            setLiveFieldState(confirmation, error, valid, valid ? '' : 'Passwords do not match.');
            return valid;
        };

        email?.addEventListener('input', validateEmail);
        email?.addEventListener('blur', validateEmail);
        password?.addEventListener('input', validatePassword);
        password?.addEventListener('blur', validatePassword);
        password?.addEventListener('input', () => { validatePassword(); validateConfirmation(); });
        confirmation?.addEventListener('input', validateConfirmation);
        name?.addEventListener('input', validateName);
        name?.addEventListener('blur', validateName);

        form.addEventListener('submit', (event) => {
            const valid = validateEmail() && validatePassword() && validateName() && validateConfirmation();
            if (!valid) {
                event.preventDefault();
                (form.querySelector('[aria-invalid="true"]') || email)?.focus();
            }
        });
    });
}

function initDynamicTableSearch() {
    document.querySelectorAll('[data-table-search]').forEach((input) => {
        if (input.dataset.tableSearchReady === '1') return;
        input.dataset.tableSearchReady = '1';

        const selector = input.dataset.tableSearch;
        const table = document.querySelector(selector);
        if (!table) return;

        const rows = [...table.querySelectorAll('tbody tr[data-search-row]')];
        const empty = document.querySelector(input.dataset.emptyTarget || '[data-table-search-empty]');
        const count = document.querySelector(input.dataset.countTarget || '[data-table-search-count]');

        const filter = () => {
            const query = input.value.trim().toLowerCase();
            let visible = 0;

            rows.forEach((row) => {
                const text = (row.dataset.searchText || row.textContent).toLowerCase();
                const matches = !query || text.includes(query);
                row.classList.toggle('hidden', !matches);
                if (matches) visible += 1;
            });

            if (empty) empty.classList.toggle('hidden', visible !== 0);
            if (count) count.textContent = visible + (visible === 1 ? ' result' : ' results');
        };

        input.addEventListener('input', filter);
        filter();
    });
}

function initInteractiveUi() {
    initLiveValidation();
    initDynamicTableSearch();
}

document.addEventListener('DOMContentLoaded', initInteractiveUi);

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
