import { project } from './physics';

/**
 * Pelacak seret berbasis Pointer Events.
 *
 * Yang membuat seretan terasa langsung bukan cuma "elemen ikut jari", tapi
 * empat hal kecil yang semuanya harus benar:
 *
 *  1. Umpan balik muncul saat jari *turun*, bukan saat dilepas. Begitu ada
 *     jeda, rasa langsungnya jatuh dan tidak bisa diselamatkan lagi.
 *  2. Elemen menempel di titik tempat ia dipegang. Melompat ke tengah saat
 *     disentuh langsung merusak ilusinya.
 *  3. Kecepatan dihitung dari beberapa sampel terakhir, bukan dari selisih
 *     satu frame — satu frame terlalu berisik untuk dipakai melempar.
 *  4. Arah baru dikunci setelah jari bergerak cukup jauh. Sebelum itu semua
 *     kemungkinan gerakan masih hidup berdampingan; yang kalah dibatalkan
 *     setelah maksudnya jelas, bukan ditebak sejak sentuhan pertama.
 */

const SAMPLE_WINDOW_MS = 100;
const MAX_SAMPLES = 6;

export function draggable(el, options = {}) {
    const {
        axis = 'x',
        hysteresis = 10,
        bounds = null,
        dimension = null,
        onPress = null,
        onStart = null,
        onMove = null,
        onEnd = null,
        onCancel = null,
        enabled = () => true,
    } = options;

    let pointerId = null;
    let pressed = false;
    let dragging = false;
    let decided = false;
    let start = { x: 0, y: 0, t: 0 };
    let grabOffset = { x: 0, y: 0 };
    let samples = [];

    function pushSample(x, y, t) {
        samples.push({ x, y, t });

        while (
            samples.length > MAX_SAMPLES ||
            (samples.length > 2 && t - samples[0].t > SAMPLE_WINDOW_MS)
        ) {
            samples.shift();
        }
    }

    /**
     * Kecepatan dari jendela sampel terakhir, dalam px/detik.
     */
    function velocity() {
        if (samples.length < 2) {
            return { x: 0, y: 0 };
        }

        const last = samples[samples.length - 1];
        const first = samples[0];
        const dt = last.t - first.t;

        if (dt <= 0) {
            return { x: 0, y: 0 };
        }

        return {
            x: ((last.x - first.x) / dt) * 1000,
            y: ((last.y - first.y) / dt) * 1000,
        };
    }

    function reset() {
        pointerId = null;
        pressed = false;
        dragging = false;
        decided = false;
        samples = [];
    }

    function onPointerDown(event) {
        if (! enabled() || event.button > 0 || pointerId !== null) {
            return;
        }

        pointerId = event.pointerId;
        pressed = true;
        decided = false;
        dragging = false;
        samples = [];

        const rect = el.getBoundingClientRect();

        // Titik pegang, supaya elemen tidak melompat ke tengah jari.
        grabOffset = { x: event.clientX - rect.left, y: event.clientY - rect.top };
        start = { x: event.clientX, y: event.clientY, t: event.timeStamp };

        pushSample(event.clientX, event.clientY, event.timeStamp);

        // Segera, sebelum apa pun diputuskan.
        onPress?.({ event, grabOffset });
    }

    function onPointerMove(event) {
        if (! pressed || event.pointerId !== pointerId) {
            return;
        }

        pushSample(event.clientX, event.clientY, event.timeStamp);

        const dx = event.clientX - start.x;
        const dy = event.clientY - start.y;

        if (! decided) {
            const travelled = Math.hypot(dx, dy);

            if (travelled < hysteresis) {
                return;
            }

            // Semua arah dipertimbangkan bersamaan sampai di sini; sekarang
            // yang kalah dibatalkan.
            const horizontal = Math.abs(dx) > Math.abs(dy);
            const wanted =
                axis === 'both' || (axis === 'x' && horizontal) || (axis === 'y' && ! horizontal);

            decided = true;

            if (! wanted) {
                pressed = false;
                onCancel?.({ event });
                reset();

                return;
            }

            dragging = true;

            try {
                el.setPointerCapture(pointerId);
            } catch {
                // Pointer bisa saja sudah lepas; seretan tetap boleh jalan.
            }

            onStart?.({ event, grabOffset });
        }

        if (! dragging) {
            return;
        }

        // Selama jari menempel, gambar diperbarui tiap peristiwa tanpa
        // ditahan: umpan baliknya harus menerus, bukan cuma di ujung.
        event.preventDefault();

        onMove?.({
            event,
            dx,
            dy,
            delta: axis === 'y' ? dy : dx,
            velocity: velocity(),
            grabOffset,
            bounds: bounds?.(),
            dimension: dimension?.(),
        });
    }

    function finish(event, cancelled) {
        if (event.pointerId !== pointerId) {
            return;
        }

        const wasDragging = dragging;
        const v = velocity();

        if (pointerId !== null) {
            try {
                el.releasePointerCapture(pointerId);
            } catch {
                // Sudah dilepas duluan; tidak ada yang perlu dibereskan.
            }
        }

        const dx = event.clientX - start.x;
        const dy = event.clientY - start.y;

        reset();

        if (! wasDragging) {
            onCancel?.({ event, tapped: ! cancelled && Math.hypot(dx, dy) < hysteresis });

            return;
        }

        const primary = axis === 'y' ? v.y : v.x;

        onEnd?.({
            event,
            dx,
            dy,
            delta: axis === 'y' ? dy : dx,
            velocity: v,
            // Ke mana gerakan ini akan berhenti sendiri kalau dibiarkan.
            projected: project(primary),
            cancelled,
        });
    }

    const onPointerUp = (event) => finish(event, false);
    const onPointerCancel = (event) => finish(event, true);

    el.addEventListener('pointerdown', onPointerDown);
    el.addEventListener('pointermove', onPointerMove);
    el.addEventListener('pointerup', onPointerUp);
    el.addEventListener('pointercancel', onPointerCancel);

    // Seretan mendatar tidak boleh diambil alih gulir bawaan peramban.
    el.style.touchAction = axis === 'x' ? 'pan-y' : axis === 'y' ? 'pan-x' : 'none';

    return {
        get isDragging() {
            return dragging;
        },
        destroy() {
            el.removeEventListener('pointerdown', onPointerDown);
            el.removeEventListener('pointermove', onPointerMove);
            el.removeEventListener('pointerup', onPointerUp);
            el.removeEventListener('pointercancel', onPointerCancel);
            reset();
        },
    };
}
