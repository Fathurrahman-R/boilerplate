import { draggable, springTo, setNow, clampRubber, project, nearest, prefs } from '../motion';
import { lockScroll, unlockScroll } from './focusTrap';

/**
 * Sidebar sebagai drawer di layar sempit.
 *
 * Sebelumnya panel ini berpindah dengan membalik kelas translate: terbuka
 * atau tertutup, tanpa keadaan di antaranya. Sekarang posisinya ditulis
 * seretan dan spring, jadi ada jalan menerus di antara keduanya — dan panel
 * yang sedang bergerak bisa ditangkap lalu dibalik kapan saja.
 *
 * Ada dua tempat yang bisa dipegang: panelnya sendiri untuk menutup, dan
 * strip tipis di tepi layar untuk membuka. Keduanya memakai pelacak yang
 * sama, jadi keduanya membawa momentum dan perlawanan tepi yang sama pula.
 */
export default function mobileSidebar() {
    return {
        draggingPanel: null,
        draggingEdge: null,
        locked: false,

        get panel() {
            return this.$refs.panel ?? this.$el;
        },

        get scrim() {
            return this.$refs.scrim;
        },

        get width() {
            return this.panel?.getBoundingClientRect().width || 260;
        },

        get isOpen() {
            return this.$store.shell.sidebarOpen;
        },

        /**
         * Di atas lg sidebar bukan drawer melainkan kolom yang menetap, dan
         * posisinya diurus CSS. Transform apa pun dari lapisan gesture harus
         * dilepas di sana — kalau tidak, panel yang seharusnya diam ikut
         * tergeser keluar layar.
         *
         * Ambangnya dibaca dari keadaan CSS panelnya sendiri, bukan dari
         * lebar jendela. Menyalin angka breakpoint ke JavaScript berarti
         * menyimpan aturan yang sama di dua tempat, dan yang satu akan
         * ketinggalan begitu yang lain berubah.
         */
        get narrow() {
            return getComputedStyle(this.panel).position === 'fixed';
        },

        /**
         * offset 0 berarti terbuka penuh; -width berarti tersembunyi.
         */
        paint(offset) {
            if (! this.narrow) {
                this.reset();

                return;
            }

            const width = this.width;
            const progress = 1 - Math.min(1, Math.abs(offset) / width);

            this.$store.shell.sidebarProgress = progress;

            setNow(this.panel, { x: offset });

            if (this.scrim) {
                setNow(this.scrim, { opacity: progress });
            }
        },

        reset() {
            setNow(this.panel, { x: 0 });

            if (this.scrim) {
                setNow(this.scrim, { opacity: 0 });
            }

            this.$store.shell.sidebarProgress = 1;
            this.setLock(false);
        },

        /**
         * Kunci gulir dihitung, bukan disetel dan dilepas begitu saja: dua
         * lapisan yang terbuka bersamaan tidak boleh membuat yang pertama
         * ditutup lalu mengembalikan gulir padahal yang kedua masih terbuka.
         * Di sini penjaganya memastikan komponen ini menyumbang paling banyak
         * satu kunci.
         */
        setLock(wanted) {
            if (wanted === this.locked) {
                return;
            }

            this.locked = wanted;
            wanted ? lockScroll() : unlockScroll();
        },

        settle(open, velocity = 0) {
            if (! this.narrow) {
                this.reset();

                return;
            }

            const width = this.width;

            this.$store.shell.sidebarOpen = open;
            this.$store.shell.sidebarProgress = open ? 1 : 0;

            if (prefs.reducedMotion) {
                this.paint(open ? 0 : -width);
            } else {
                springTo(this.panel, { x: open ? 0 : -width }, velocity !== 0 ? 'throw' : 'sheet', {
                    velocity,
                });

                if (this.scrim) {
                    springTo(this.scrim, { opacity: open ? 1 : 0 }, 'overlay');
                }
            }

            this.setLock(open);
        },

        decide({ from, delta, velocity }) {
            const width = this.width;
            const landing = from + delta + project(velocity.x);

            this.settle(nearest(landing, [-width, 0]) === 0, velocity.x);
        },

        sync() {
            if (! this.narrow) {
                this.reset();

                return;
            }

            this.paint(this.isOpen ? 0 : -this.width);
        },

        sidebarInit() {
            let from = 0;

            const attach = (el, opening) =>
                draggable(el, {
                    axis: 'x',
                    hysteresis: 10,
                    enabled: () => this.narrow,

                    onStart: () => {
                        from = opening ? -this.width : 0;
                        this.$store.shell.sidebarDragging = true;

                        // Panel harus sudah bisa digambar sebelum ditarik
                        // keluar, bukan muncul setelah gerakannya selesai.
                        if (opening) {
                            this.$store.shell.sidebarOpen = true;
                            this.paint(from);
                        }
                    },

                    onMove: ({ dx }) => {
                        this.paint(clampRubber(from + dx, -this.width, 0, this.width));
                    },

                    onEnd: ({ dx, velocity }) => {
                        this.$store.shell.sidebarDragging = false;
                        this.decide({ from, delta: dx, velocity });
                    },

                    onCancel: () => {
                        this.$store.shell.sidebarDragging = false;

                        if (opening) {
                            this.settle(false);
                        }
                    },
                });

            this.$nextTick(() => {
                this.draggingPanel = attach(this.panel, false);

                if (this.$refs.edge) {
                    this.draggingEdge = attach(this.$refs.edge, true);
                }

                this.sync();
            });

            // Melewati ambang lg selagi drawer terbuka: panelnya harus
            // berhenti jadi drawer dan kembali jadi kolom, bukan tertinggal
            // di posisi seretan terakhir.
            const wide = window.matchMedia('(min-width: 1024px)');
            const onChange = () => {
                this.$store.shell.sidebarOpen = false;
                this.sync();
            };

            if (wide.addEventListener) {
                wide.addEventListener('change', onChange);
            } else if (wide.addListener) {
                wide.addListener(onChange);
            }

            // Store dibalik dari tempat lain juga — tombol menu di toolbar,
            // Escape, tautan yang diklik. Panelnya harus ikut lewat jalur
            // yang sama, bukan lewat kelas yang berbeda.
            this.$watch('$store.shell.sidebarOpen', (open) => {
                if (! this.$store.shell.sidebarDragging) {
                    this.settle(open);
                }
            });

            this.$cleanup?.(() => {
                if (wide.removeEventListener) {
                    wide.removeEventListener('change', onChange);
                }

                this.setLock(false);
                this.draggingPanel?.destroy();
                this.draggingEdge?.destroy();
            });
        },
    };
}
