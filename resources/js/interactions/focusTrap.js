/**
 * Penahan fokus untuk dialog.
 *
 * Sebelumnya keempat dialog di aplikasi ini memasang aria-modal tapi tidak
 * menahan Tab sama sekali: menekan Tab beberapa kali membawa fokus keluar ke
 * halaman di belakangnya, yang secara visual sudah diredupkan dan tidak bisa
 * diklik. Bagi yang memakai papan ketik atau pembaca layar, dialognya jadi
 * jebakan tanpa jalan keluar yang jelas.
 *
 * Selain menahan Tab, latar belakangnya juga dipasangi `inert` supaya pembaca
 * layar ikut berhenti di lapisan yang benar.
 */

const FOCUSABLE = [
    'a[href]',
    'button:not([disabled])',
    'input:not([disabled]):not([type="hidden"])',
    'select:not([disabled])',
    'textarea:not([disabled])',
    '[tabindex]:not([tabindex="-1"])',
].join(',');

function focusable(container) {
    return Array.from(container.querySelectorAll(FOCUSABLE)).filter(
        (el) => el.offsetParent !== null || el === document.activeElement,
    );
}

export function trapFocus(container) {
    const root = document.querySelector('[data-app-root]');
    const previous = document.activeElement;

    if (root && ! root.contains(container)) {
        root.inert = true;
    }

    function onKeydown(event) {
        if (event.key !== 'Tab') {
            return;
        }

        const items = focusable(container);

        if (items.length === 0) {
            event.preventDefault();
            container.focus();

            return;
        }

        const first = items[0];
        const last = items[items.length - 1];
        const active = document.activeElement;

        if (event.shiftKey && (active === first || ! container.contains(active))) {
            event.preventDefault();
            last.focus();

            return;
        }

        if (! event.shiftKey && (active === last || ! container.contains(active))) {
            event.preventDefault();
            first.focus();
        }
    }

    document.addEventListener('keydown', onKeydown, true);

    return function release({ restore = true } = {}) {
        document.removeEventListener('keydown', onKeydown, true);

        if (root) {
            root.inert = false;
        }

        if (restore && previous && typeof previous.focus === 'function') {
            previous.focus();
        }
    };
}

/**
 * Kunci gulir halaman selama lapisan yang memblokir terbuka.
 *
 * Dihitung, bukan disetel dan dilepas begitu saja: dua lapisan yang terbuka
 * bersamaan tidak boleh membuat yang pertama ditutup lalu mengembalikan gulir
 * padahal yang kedua masih terbuka.
 */
let locks = 0;

export function lockScroll() {
    locks += 1;

    if (locks === 1) {
        document.body.style.overflow = 'hidden';
    }
}

export function unlockScroll() {
    locks = Math.max(0, locks - 1);

    if (locks === 0) {
        document.body.style.overflow = '';
    }
}
