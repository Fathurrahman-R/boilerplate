import { draggable, springTo, setNow, clampRubber, project, nearest, haptic, prefs } from '../motion';

/**
 * Tuas yang knob-nya bisa diseret.
 *
 * Input checkbox aslinya tetap ada dan tetap bisa dijalankan dari papan
 * ketik; yang digambar ulang hanya lapisan tampilannya. Knob-nya harus elemen
 * sungguhan, bukan pseudo-element, karena pseudo-element tidak bisa
 * digerakkan dari JavaScript.
 *
 * Yang membuatnya terasa benar:
 *  - Knob membesar sedikit begitu jari turun, sebelum apa pun diputuskan.
 *  - Warna track berubah menerus mengikuti posisi, bukan membalik sekali di
 *    akhir. Umpan baliknya berjalan selama gerakan, bukan cuma di ujungnya.
 *  - Yang menentukan hasil adalah ke mana gerakan itu menuju, bukan di mana
 *    jari kebetulan berhenti. Sentakan cepat balik melewati titik awal tetap
 *    berakhir mundur.
 */
export default function dragToggle(config = {}) {
    const { travel = 20, checked = false } = config;

    return {
        on: checked,
        dragging: false,
        dragger: null,

        get knob() {
            return this.$refs.knob;
        },

        get track() {
            return this.$refs.track;
        },

        get input() {
            return this.$refs.input;
        },

        paint(x) {
            const ratio = Math.max(0, Math.min(1, x / travel));

            setNow(this.knob, { x });

            if (this.track) {
                this.track.style.setProperty('--toggle-progress', String(ratio));
            }
        },

        /**
         * Menulis keadaan sungguhannya. Peristiwa change dikirim pada frame
         * yang sama dengan dimulainya spring, supaya gambar, data, dan
         * getarannya jatuh bersamaan.
         */
        commit(on, { velocity = 0 } = {}) {
            const changed = on !== this.on;

            this.on = on;

            if (this.input) {
                this.input.checked = on;
                this.input.dispatchEvent(new Event('change', { bubbles: true }));
            }

            if (prefs.reducedMotion) {
                this.paint(on ? travel : 0);
            } else {
                springTo(this.knob, { x: on ? travel : 0, scaleX: 1 }, 'pop', { velocity });

                if (this.track) {
                    this.track.style.setProperty('--toggle-progress', on ? '1' : '0');
                }
            }

            if (changed) {
                haptic('select');
            }
        },

        toggle() {
            this.commit(! this.on);
        },

        /**
         * Menyelaraskan gambar dengan keadaan input.
         *
         * Klik dan Space ditangani label dan checkbox aslinya, bukan oleh
         * kode ini — kalau keduanya sama-sama membalik nilainya, satu klik
         * jadi dua pembalikan dan tuasnya kembali ke tempat semula.
         */
        sync() {
            const on = Boolean(this.input?.checked);

            if (on === this.on) {
                return;
            }

            this.on = on;

            if (prefs.reducedMotion) {
                this.paint(on ? travel : 0);
            } else {
                springTo(this.knob, { x: on ? travel : 0, scaleX: 1 }, 'pop');

                if (this.track) {
                    this.track.style.setProperty('--toggle-progress', on ? '1' : '0');
                }
            }

            haptic('select');
        },

        toggleInit() {
            this.$nextTick(() => this.paint(this.on ? travel : 0));

            const base = () => (this.on ? travel : 0);
            let start = 0;

            // Dipasang di track, bukan di seluruh baris: menyeret labelnya
            // seharusnya menyeleksi teks, bukan menggeser tuas.
            this.dragger = draggable(this.track ?? this.$el, {
                axis: 'x',
                hysteresis: 10,

                onPress: () => {
                    if (! prefs.reducedMotion) {
                        // Merespons saat jari turun, bukan saat dilepas.
                        springTo(this.knob, { scaleX: 1.12 }, 'snap');
                    }
                },

                onStart: () => {
                    this.dragging = true;
                    start = base();
                },

                onMove: ({ dx }) => {
                    this.paint(clampRubber(start + dx, 0, travel, travel));
                },

                onEnd: ({ dx, velocity }) => {
                    this.dragging = false;

                    const landing = start + dx + project(velocity.x);

                    this.commit(nearest(landing, [0, travel]) === travel, {
                        velocity: velocity.x,
                    });

                    // Setelah seretan, peramban masih mengirim click ke label
                    // yang membungkusnya. Kalau dibiarkan, ia membalik lagi
                    // nilai yang barusan ditetapkan seretan dan tuasnya
                    // kembali ke tempat semula.
                    this.$el.addEventListener(
                        'click',
                        (event) => {
                            event.preventDefault();
                            event.stopPropagation();
                        },
                        { capture: true, once: true },
                    );
                },

                onCancel: () => {
                    this.dragging = false;

                    // Ketukan tidak diurus di sini: label dan checkbox
                    // aslinya sudah menanganinya, dan sync() yang menyusul
                    // menggerakkan knob-nya.
                    if (! prefs.reducedMotion) {
                        springTo(this.knob, { scaleX: 1 }, 'pop');
                    }
                },
            });

            this.$cleanup?.(() => this.dragger?.destroy());
        },
    };
}
