import { prefs, onPrefsChange } from './prefs';
import { springOptions, springTokens } from './springs';
import { project, rubberband, clampRubber, nearest, clamp } from './physics';
import { presentation, springTo, springAxis, setNow, stop, isAnimating, write } from './present';
import { draggable } from './drag';
import { haptic } from './haptics';

export {
    prefs,
    onPrefsChange,
    springOptions,
    springTokens,
    project,
    rubberband,
    clampRubber,
    nearest,
    clamp,
    presentation,
    springTo,
    springAxis,
    setNow,
    stop,
    isAnimating,
    write,
    draggable,
    haptic,
};

/**
 * Titik asal transform diturunkan dari penempatan menu, supaya lembarannya
 * tumbuh dari tombol yang membukanya. Kalau sesuatu menghilang ke satu arah,
 * kita menunggunya muncul lagi dari arah yang sama.
 */
const ORIGINS = {
    bottom: 'top center',
    'bottom-start': 'top left',
    'bottom-end': 'top right',
    top: 'bottom center',
    'top-start': 'bottom left',
    'top-end': 'bottom right',
    start: 'right center',
    end: 'left center',
};

function resolveOrigin(value) {
    return ORIGINS[value] ?? value;
}

export function installMotion(Alpine) {
    /**
     * x-spring — pengganti x-transition.
     *
     * Alpine tidak memasang display:none sendiri kalau elemen punya
     * _x_transition; ia menyerahkannya ke in()/out() dan baru menyembunyikan
     * elemen ketika out() memanggil callback keduanya. Itulah celah yang
     * dipakai di sini, dan itu memberi tiga hal sekaligus:
     *
     *  - Masuk dan keluar lewat jalur yang sama. Satu pasang from/to
     *    menggerakkan kedua arah, jadi tidak mungkin lagi mengirim transisi
     *    yang cuma punya arah masuk.
     *  - Bisa disela. Menampilkan ulang di tengah keluar memanggil in(),
     *    yang menghentikan animasi berjalan dan berangkat lagi dari posisi
     *    yang sedang terlihat — bukan dari awal, jadi tidak ada lompatan.
     *  - display:none tidak pernah balapan dengan animasinya, karena ia hanya
     *    dipasang oleh callback yang dijalankan saat spring benar-benar
     *    selesai. Penjaga __exiting memastikan callback basi dari animasi
     *    yang sudah dibatalkan tidak ikut menyembunyikan elemen yang
     *    barusan ditampilkan lagi.
     *
     * Kontraknya internal Alpine dan tidak terdokumentasi. Kalau Alpine naik
     * versi minor, yang perlu diperiksa ulang ada di
     * node_modules/alpinejs/src/directives/x-show.js.
     */
    Alpine.directive('spring', (el, { expression }, { evaluate, cleanup }) => {
        const config = expression ? evaluate(expression) : {};

        const from = config.from ?? { opacity: 0, y: 8, scale: 0.96 };
        const to = config.to ?? { opacity: 1, y: 0, scale: 1 };
        const token = config.token ?? 'sheet';
        const exitToken = config.exitToken ?? token;

        if (config.origin) {
            el.style.transformOrigin = resolveOrigin(config.origin);
        }

        el._x_transition = {
            in(before = () => {}, after = () => {}) {
                el.__exiting = false;
                before();

                // Elemen ber-x-cloak masih display:none pada tick pertama;
                // membaca posisinya di situ akan mengembalikan nol.
                const begin = () => {
                    el.style.pointerEvents = '';

                    // Hanya disemai kalau tidak ada yang sedang berjalan.
                    // Saat disela, nilai tampil sudah ada dan justru itulah
                    // titik berangkat yang benar.
                    if (! isAnimating(el)) {
                        setNow(el, from);
                    }

                    springTo(el, to, token, { onComplete: after });
                };

                if (getComputedStyle(el).display === 'none') {
                    requestAnimationFrame(begin);
                } else {
                    begin();
                }
            },

            out(before = () => {}, after = () => {}) {
                el.__exiting = true;
                before();

                // Lapisan yang sedang pergi tidak boleh menelan klik
                // berikutnya. Dulu elemen ini hilang seketika, jadi masalah
                // itu tidak pernah muncul.
                el.style.pointerEvents = 'none';

                // Kalau kepergiannya dipicu lemparan jari, kecepatannya
                // dititipkan lapisan gesture supaya tidak ada sambungan
                // terlihat antara seretan dan animasinya.
                const velocity = el.__springVelocity ?? 0;
                delete el.__springVelocity;

                springTo(el, from, velocity !== 0 ? 'throw' : exitToken, {
                    velocity,
                    onComplete: () => {
                        if (el.__exiting) {
                            after();
                        }
                    },
                });
            },
        };

        cleanup(() => {
            stop(el);
            delete el._x_transition;
        });
    });

    Alpine.magic('spring', (el) => (props, token = 'move', options = {}) =>
        springTo(el, props, token, options),
    );

    Alpine.magic('springAxis', (el) => (axis, to, token = 'move', options = {}) =>
        springAxis(el, axis, to, token, options),
    );

    Alpine.magic('setNow', (el) => (props) => setNow(el, props));

    Alpine.magic('presentation', (el) => () => presentation(el));

    Alpine.magic('haptic', () => (kind) => haptic(kind));

    Alpine.magic('prefs', () => prefs);

    Alpine.magic('drag', (el) => (options) => draggable(el, options));
}
