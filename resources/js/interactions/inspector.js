import { springTo, prefs } from '../motion';
import { trapFocus, lockScroll, unlockScroll } from './focusTrap';

/**
 * Panel detail.
 *
 * Di layar lebar ia kolom ketiga yang mendorong konten ke samping; di layar
 * sempit ia drawer yang menutupi konten. Bedanya bukan soal ukuran layar
 * semata: panel yang berjalan berdampingan tidak memutus alur, jadi ia tidak
 * memakai peredup dan tidak mengunci gulir. Meredupkan seluruh halaman untuk
 * sesuatu yang sebetulnya muat di sebelahnya membuat orang harus menutupnya
 * dulu setiap kali ingin melihat konteksnya lagi.
 *
 * Isinya diambil saat dibuka. Satu panel melayani seluruh tabel di halaman:
 * barisnya mengirim URL fragmennya lewat event, bukan menanam satu panel per
 * baris di DOM.
 */
export default function inspector(config = {}) {
    const { breakpoint = 1280, openEvent = 'drawer-remote-open', closeEvent = 'drawer-remote-close' } =
        config;

    return {
        open: false,
        loading: false,
        html: '',
        error: '',
        wide: false,
        trigger: null,
        release: null,

        get blocking() {
            return ! this.wide;
        },

        async show(url) {
            this.trigger = document.activeElement;
            this.open = true;
            this.loading = true;
            this.error = '';
            this.html = '';

            if (this.blocking) {
                lockScroll();
            }

            this.$nextTick(() => {
                this.$refs.panel?.focus();

                // Fokus hanya ditahan kalau panelnya memang memblokir. Kolom
                // yang berdampingan justru harus bisa ditinggalkan dengan Tab.
                if (this.blocking && this.$refs.panel) {
                    this.release = trapFocus(this.$refs.panel);
                }
            });

            try {
                const response = await fetch(url, {
                    headers: { 'X-Requested-With': 'XMLHttpRequest' },
                    credentials: 'same-origin',
                });

                if (! response.ok) {
                    this.error =
                        response.status === 403
                            ? 'Anda tidak punya akses ke data ini.'
                            : 'Detail tidak bisa dimuat (' + response.status + ').';
                } else {
                    this.html = await response.text();
                }
            } catch {
                this.error = 'Detail tidak bisa dimuat. Periksa koneksi lalu coba lagi.';
            } finally {
                this.loading = false;
            }
        },

        hide() {
            if (! this.open) {
                return;
            }

            this.open = false;

            if (this.blocking) {
                unlockScroll();
            }

            this.release?.({ restore: true });
            this.release = null;
        },

        inspectorInit() {
            const query = window.matchMedia(`(min-width: ${breakpoint}px)`);

            this.wide = query.matches;

            const onChange = (event) => {
                const was = this.wide;

                this.wide = event.matches;

                // Berpindah mode selagi terbuka: kunci gulir dan penahan
                // fokus harus ikut berpindah, bukan tertinggal di mode lama.
                if (this.open && was !== this.wide) {
                    if (this.wide) {
                        unlockScroll();
                        this.release?.({ restore: false });
                        this.release = null;
                    } else {
                        lockScroll();

                        if (this.$refs.panel) {
                            this.release = trapFocus(this.$refs.panel);
                        }
                    }
                }
            };

            if (query.addEventListener) {
                query.addEventListener('change', onChange);
            } else if (query.addListener) {
                query.addListener(onChange);
            }

            // Skeleton berganti isi dengan cross-fade, bukan tukar mendadak:
            // yang berubah cuma isinya, panelnya tetap benda yang sama.
            this.$watch('loading', (loading) => {
                if (! loading && this.$refs.body && ! prefs.reducedMotion) {
                    springTo(this.$refs.body, { opacity: 1 }, 'overlay');
                }
            });

            this.$cleanup?.(() => {
                if (query.removeEventListener) {
                    query.removeEventListener('change', onChange);
                }

                this.release?.({ restore: false });

                if (this.open && this.blocking) {
                    unlockScroll();
                }
            });
        },

        openEventName: openEvent,
        closeEventName: closeEvent,
    };
}
