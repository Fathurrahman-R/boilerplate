/**
 * Toggle tema gelap/terang.
 *
 * Nilai disimpan di localStorage supaya bertahan antar-halaman. Pemilihan awal
 * dilakukan lebih dulu di <head> (lihat layouts/partials/theme-script.blade.php)
 * agar halaman tidak sempat berkedip terang sebelum bundle ini termuat.
 */

const STORAGE_KEY = 'theme';

export function currentTheme() {
    const stored = localStorage.getItem(STORAGE_KEY);

    if (stored === 'dark' || stored === 'light') {
        return stored;
    }

    return window.matchMedia('(prefers-color-scheme: dark)').matches ? 'dark' : 'light';
}

export function applyTheme(theme) {
    const root = document.documentElement;

    // Perpindahan terang-gelap di-ease, bukan melompat: lompatan kecerahan
    // sebesar ini menyakitkan mata di ruangan gelap. Kelasnya dilepas lagi
    // setelah transisinya selesai supaya tidak ikut memperlambat perubahan
    // warna lain sepanjang sisa sesi.
    const ease = ! window.matchMedia('(prefers-reduced-motion: reduce)').matches;

    if (ease) {
        root.classList.add('theme-x');
        clearTimeout(applyTheme.timer);
        applyTheme.timer = setTimeout(() => root.classList.remove('theme-x'), 280);
    }

    root.setAttribute('data-theme', theme);
    localStorage.setItem(STORAGE_KEY, theme);
    document.dispatchEvent(new CustomEvent('theme:changed', { detail: { theme } }));
}

export function toggleTheme() {
    applyTheme(currentTheme() === 'dark' ? 'light' : 'dark');
}

document.addEventListener('click', (event) => {
    if (event.target.closest('[data-theme-toggle]')) {
        toggleTheme();
    }
});

window.theme = { current: currentTheme, apply: applyTheme, toggle: toggleTheme };
