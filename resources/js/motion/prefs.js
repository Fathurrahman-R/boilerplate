/**
 * Tiga preferensi sistem yang berdiri sendiri.
 *
 * Dibaca ulang setiap animasi dimulai, bukan disimpan sekali saat modul
 * diimpor: pengguna bisa mengubah setelan aksesibilitas di tengah sesi, dan
 * halaman yang sudah terbuka harus langsung ikut.
 *
 * Gerak yang dikurangi tidak berarti umpan balik yang dihapus. Yang hilang
 * adalah perpindahan posisi — tanda bahwa tekanan terdaftar tetap ada, dalam
 * bentuk perubahan warna dan opacity.
 */

const QUERIES = {
    reducedMotion: '(prefers-reduced-motion: reduce)',
    reducedTransparency: '(prefers-reduced-transparency: reduce)',
    moreContrast: '(prefers-contrast: more)',
};

const listeners = new Set();

export const prefs = {
    reducedMotion: false,
    reducedTransparency: false,
    moreContrast: false,
};

if (typeof window !== 'undefined' && window.matchMedia) {
    for (const [key, query] of Object.entries(QUERIES)) {
        const list = window.matchMedia(query);
        prefs[key] = list.matches;

        const onChange = (event) => {
            prefs[key] = event.matches;
            listeners.forEach((callback) => callback(prefs));
        };

        // Safari lama hanya punya addListener; keduanya dipasang supaya
        // perubahan setelan tetap tertangkap di sana.
        if (list.addEventListener) {
            list.addEventListener('change', onChange);
        } else if (list.addListener) {
            list.addListener(onChange);
        }
    }
}

export function onPrefsChange(callback) {
    listeners.add(callback);

    return () => listeners.delete(callback);
}
