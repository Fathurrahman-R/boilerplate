import { draggable, springTo, setNow, clampRubber, project, nearest, haptic, prefs } from '../motion';

/**
 * Indikator yang meluncur.
 *
 * Pil aktif pada segmented control dan garis bawah pada tabs sebelumnya
 * berpindah dengan cara dilepas di satu tempat lalu digambar ulang di tempat
 * lain. Yang terbaca bukan satu benda yang berpindah, melainkan dua benda
 * berbeda yang muncul bergantian — dan orang kehilangan jejak apa yang
 * sebenarnya berubah.
 *
 * Posisi dan lebarnya dianimasikan dua spring terpisah. Ini disengaja: satu
 * spring atas jarak dua dimensi kehilangan sinkron begitu kecepatan kedua
 * sumbunya berbeda, dan lintasannya melengkung ke arah yang tidak diminta
 * siapa pun.
 */
export default function slidingIndicator(config = {}) {
    const {
        draggable: canDrag = false,
        axis = 'x',
        // Nilai form di balik tiap segmen, sejajar urutannya.
        values = [],
        // Nama event yang dipancarkan saat pilihannya berubah.
        changeEvent = null,
    } = config;

    return {
        index: 0,
        dragger: null,
        centers: [],
        values,

        get picked() {
            return this.values[this.index] ?? null;
        },

        get indicator() {
            return this.$refs.indicator;
        },

        get items() {
            return Array.from(this.$refs.track?.querySelectorAll('[data-segment]') ?? []);
        },

        measure() {
            const track = this.$refs.track;
            const indicator = this.indicator;

            if (! track || ! indicator) {
                return;
            }

            const trackRect = track.getBoundingClientRect();

            this.centers = this.items.map((item) => {
                const rect = item.getBoundingClientRect();

                return {
                    start: rect.left - trackRect.left,
                    width: rect.width,
                    center: rect.left - trackRect.left + rect.width / 2,
                };
            });
        },

        placeNow(index) {
            const spot = this.centers[index];

            if (! spot || ! this.indicator) {
                return;
            }

            this.indicator.style.width = `${spot.width}px`;
            setNow(this.indicator, { x: spot.start });
        },

        moveTo(index, { velocity = 0, instant = false } = {}) {
            const spot = this.centers[index];

            if (! spot || ! this.indicator) {
                return;
            }

            if (instant || prefs.reducedMotion) {
                this.indicator.style.width = `${spot.width}px`;
                setNow(this.indicator, { x: spot.start });

                return;
            }

            // Lebar dianimasikan lewat properti CSS-nya sendiri; posisinya
            // lewat transform, supaya yang bergerak tiap frame tetap hanya
            // properti yang bisa ditangani compositor.
            this.indicator.style.transition = 'width var(--dur-base) var(--ease-out-apple)';
            this.indicator.style.width = `${spot.width}px`;

            springTo(this.indicator, { x: spot.start }, velocity !== 0 ? 'throw' : 'move', {
                velocity,
            });
        },

        select(index, options = {}) {
            const changed = index !== this.index;

            this.index = index;
            this.moveTo(index, options);

            if (changed && changeEvent) {
                this.$dispatch(changeEvent, this.picked);
            }
        },

        startDrag() {
            if (! canDrag || ! this.indicator) {
                return;
            }

            let base = 0;
            let lastUnder = this.index;

            this.dragger = draggable(this.indicator, {
                axis,
                hysteresis: 6,

                onStart: () => {
                    base = this.centers[this.index]?.start ?? 0;
                    lastUnder = this.index;
                },

                onMove: ({ delta }) => {
                    const last = this.centers[this.centers.length - 1];
                    const max = last ? last.start : 0;
                    const width = this.centers[this.index]?.width ?? 1;
                    const x = clampRubber(base + delta, 0, max, width);

                    setNow(this.indicator, { x });

                    // Getaran dipasang pada peristiwa penyebabnya — segmen
                    // yang berganti di bawah indikator — bukan pada saat jari
                    // dilepas. Kalau menunggu lepas, sebabnya jadi kabur.
                    const centre = x + width / 2;
                    const under = this.centers.findIndex(
                        (spot) => centre >= spot.start && centre < spot.start + spot.width,
                    );

                    if (under !== -1 && under !== lastUnder) {
                        lastUnder = under;
                        haptic('select');
                    }
                },

                onEnd: ({ delta, velocity }) => {
                    const width = this.centers[this.index]?.width ?? 1;
                    const landing = base + delta + project(velocity.x) + width / 2;
                    const target = nearest(
                        landing,
                        this.centers.map((spot) => spot.center),
                    );
                    const index = this.centers.findIndex((spot) => spot.center === target);

                    this.select(index === -1 ? this.index : index, { velocity: velocity.x });
                },

                onCancel: () => {
                    this.moveTo(this.index);
                },
            });
        },

        indicatorInit(startIndex = 0) {
            this.index = startIndex;

            this.$nextTick(() => {
                this.measure();
                this.placeNow(this.index);
                this.startDrag();
            });

            // Lebar segmen berubah saat jendela berubah ukuran atau labelnya
            // berganti; posisi indikator harus ikut tanpa dianimasikan.
            const observer = new ResizeObserver(() => {
                this.measure();
                this.placeNow(this.index);
            });

            if (this.$refs.track) {
                observer.observe(this.$refs.track);
            }

            this.$cleanup?.(() => {
                observer.disconnect();
                this.dragger?.destroy();
            });
        },
    };
}
