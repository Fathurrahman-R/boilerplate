import { prefs } from '../motion';

/**
 * Judul besar yang menciut ke toolbar.
 *
 * Judul mulai besar di dalam konten, lalu saat halaman digulir ia menyusut
 * dan judul kecil di toolbar muncul menggantikannya. Dua gerakan berlawanan
 * arah yang saling menyilang — itulah yang membuat "di mana saya" tetap
 * terjawab setelah judul aslinya tergulir pergi, tanpa perlu breadcrumb yang
 * mengulang apa yang sudah dikatakan sidebar dan judul halaman.
 *
 * Progresnya diikat langsung ke posisi gulir, bukan dipicu sekali saat
 * melewati ambang. Perpindahan yang menerus terbaca sebagai satu benda yang
 * berubah; perpindahan yang tersentak terbaca sebagai dua benda berbeda.
 */
export default function largeTitle({ distance = 48 } = {}) {
    return {
        progress: 0,

        update() {
            const scrolled = window.scrollY;
            const next = Math.max(0, Math.min(1, scrolled / distance));

            if (Math.abs(next - this.progress) < 0.001) {
                return;
            }

            this.progress = next;

            const large = this.$refs.large;
            const compact = this.$refs.compact;

            if (large) {
                large.style.opacity = String(1 - next);

                // Di bawah gerak yang dikurangi judulnya tetap memudar —
                // yang dilepas hanya perpindahan posisinya.
                large.style.transform = prefs.reducedMotion
                    ? ''
                    : `translate3d(0, ${-next * 8}px, 0) scale(${1 - next * 0.04})`;
            }

            if (compact) {
                compact.style.opacity = String(next);
                compact.style.transform = prefs.reducedMotion
                    ? ''
                    : `translate3d(0, ${(1 - next) * 8}px, 0)`;
                compact.style.pointerEvents = next > 0.5 ? '' : 'none';
            }
        },

        titleInit() {
            this.update();

            const onScroll = () => this.update();

            window.addEventListener('scroll', onScroll, { passive: true });

            this.$cleanup?.(() => window.removeEventListener('scroll', onScroll));
        },
    };
}
