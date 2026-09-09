import { draggable, setNow, springTo, clampRubber, project, prefs, haptic } from '../motion';
import { trapFocus, lockScroll, unlockScroll } from './focusTrap';

/**
 * Lapisan yang datang dan pergi: modal, drawer, panel detail, command palette.
 *
 * Satu factory untuk keempatnya, karena perilakunya memang sama dan yang
 * berbeda cuma sumbu dan arahnya. Yang dikerjakan di sini:
 *
 *  - Membuka dan menutup lewat event, dengan fokus dikembalikan ke pemicunya.
 *  - Menahan fokus dan mengunci gulir selama terbuka.
 *  - Bisa ditarik untuk ditutup, mengikuti jari 1:1, dengan perlawanan yang
 *    meningkat kalau ditarik ke arah yang tidak menutup.
 *  - Menentukan komit dari tanda kecepatan lebih dulu, posisi belakangan:
 *    sentakan cepat menutup meski jaraknya baru sedikit, karena ke situlah
 *    gerakannya menuju.
 *  - Menyerahkan kecepatan lepasan ke animasinya, jadi tidak ada sambungan
 *    terlihat antara seretan dan penutupan.
 *
 * Sisi peredupan dan pendorongan latar diurus di sini juga: tugas yang
 * memblokir memang harus meredupkan dan mendorong mundur apa yang ada di
 * belakangnya, sementara panel yang berjalan berdampingan tidak.
 */
export default function sheet(config = {}) {
    const {
        id = null,
        axis = 'y',
        side = 'end',
        openEvent = 'modal-open',
        closeEvent = 'modal-close',
        dismissVelocity = 500,
        dismissFraction = 0.35,
        pushBack = true,
        scrimFade = 0.85,
        blocking = true,
    } = config;

    return {
        open: false,
        dragging: false,
        progress: 0,
        trigger: null,
        release: null,
        dragger: null,

        get panel() {
            return this.$refs.panel;
        },

        get scrim() {
            return this.$refs.scrim;
        },

        matches(detail) {
            return id === null || detail === id || detail === undefined;
        },

        show() {
            if (this.open) {
                return;
            }

            this.trigger = document.activeElement;
            this.open = true;
            this.progress = 0;

            if (blocking) {
                lockScroll();
            }

            this.$nextTick(() => {
                this.panel?.focus();

                if (this.panel) {
                    this.release = trapFocus(this.panel);
                }

                this.pushBackground(true);
            });
        },

        hide({ velocity = 0 } = {}) {
            if (! this.open) {
                return;
            }

            // Kecepatan dititipkan ke elemen supaya x-spring memakainya saat
            // menjalankan animasi keluar.
            if (velocity !== 0 && this.panel) {
                this.panel.__springVelocity = velocity;
            }

            this.open = false;
            this.progress = 0;

            if (blocking) {
                unlockScroll();
            }

            this.pushBackground(false);
            this.release?.({ restore: true });
            this.release = null;
        },

        /**
         * Meredupkan dan mendorong mundur halaman di belakangnya. Hanya untuk
         * lapisan yang memang memblokir — panel yang berjalan berdampingan
         * justru harus membiarkan alurnya utuh.
         */
        pushBackground(active) {
            if (! pushBack || ! blocking) {
                return;
            }

            const root = document.querySelector('[data-app-root]');

            if (! root || prefs.reducedMotion) {
                return;
            }

            springTo(root, active ? { scale: 0.985, y: 6 } : { scale: 1, y: 0 }, 'sheet');
        },

        /**
         * Ukuran perjalanan penuh: dari terbuka sampai hilang sepenuhnya.
         */
        extent() {
            if (! this.panel) {
                return 1;
            }

            const rect = this.panel.getBoundingClientRect();

            return axis === 'y' ? rect.height : rect.width;
        },

        /**
         * Arah yang menutup. Drawer di tepi kanan menutup ke kanan; sesuatu
         * yang masuk dari satu arah harus keluar ke arah yang sama.
         */
        direction() {
            if (axis === 'y') {
                return 1;
            }

            return side === 'start' ? -1 : 1;
        },

        apply(offset) {
            const extent = this.extent();
            const dir = this.direction();
            const travelled = Math.max(0, offset * dir);

            this.progress = extent > 0 ? Math.min(1, travelled / extent) : 0;

            setNow(this.panel, {
                [axis]: offset,
                // Panel sedikit mengecil saat ditarik: gerakan antaranya
                // menunjuk ke arah hasilnya, bukan cuma menggeser.
                scale: axis === 'y' ? 1 - this.progress * 0.04 : 1,
            });

            if (this.scrim) {
                setNow(this.scrim, { opacity: 1 - this.progress * scrimFade });
            }
        },

        startDrag(el) {
            if (! el) {
                return;
            }

            this.dragger = draggable(el, {
                axis,
                hysteresis: 10,
                enabled: () => this.open,

                onStart: () => {
                    this.dragging = true;
                },

                onMove: ({ delta }) => {
                    const extent = this.extent();
                    const dir = this.direction();

                    // Ke arah menutup jari diikuti apa adanya; ke arah
                    // sebaliknya perlawanannya bertambah, karena memang tidak
                    // ada lagi apa-apa di sana.
                    const bounded =
                        dir > 0
                            ? clampRubber(delta, 0, Infinity, extent)
                            : clampRubber(delta, -Infinity, 0, extent);

                    this.apply(bounded);
                },

                onEnd: ({ delta, velocity }) => {
                    this.dragging = false;

                    const v = axis === 'y' ? velocity.y : velocity.x;
                    const dir = this.direction();
                    const extent = this.extent();
                    const signed = v * dir;

                    // Tanda kecepatan lebih dulu: sentakan cepat menutup
                    // walau jaraknya baru sedikit, karena ke situlah gerakan
                    // itu menuju kalau dibiarkan.
                    const thrown = signed > dismissVelocity;
                    const projected = (delta + project(v)) * dir;
                    const past = projected > extent * dismissFraction;

                    if (thrown || (past && signed > -dismissVelocity)) {
                        haptic('commit');
                        this.hide({ velocity: v });

                        return;
                    }

                    // Kembali ke tempatnya, dengan kecepatan yang sama supaya
                    // pembalikannya tidak terasa seperti menabrak dinding.
                    springTo(
                        this.panel,
                        { x: 0, y: 0, scale: 1, opacity: 1 },
                        'pop',
                        { velocity: v },
                    );

                    if (this.scrim) {
                        springTo(this.scrim, { opacity: 1 }, 'overlay');
                    }

                    this.progress = 0;
                },

                onCancel: () => {
                    this.dragging = false;
                },
            });
        },

        sheetInit() {
            this.$watch?.('open', () => {});
        },

        destroySheet() {
            this.dragger?.destroy();
            this.release?.({ restore: false });

            if (this.open && blocking) {
                unlockScroll();
            }
        },

        openEventName: openEvent,
        closeEventName: closeEvent,
    };
}
