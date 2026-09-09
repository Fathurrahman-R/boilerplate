/**
 * Fisika gerak lepas tangan.
 */

/**
 * Ke mana sebuah lemparan akan berhenti sendiri.
 *
 * Ini bentuk peluruhan eksponensial yang dipakai Apple, bukan v²/2a dari buku
 * teks — hasilnya berbeda jauh dan yang eksponensial inilah yang terasa sama
 * dengan deselerasi gulir.
 *
 * Gunanya: jangan menempel ke titik terdekat dari posisi saat jari dilepas,
 * tapi ke titik terdekat dari tempat gerakan itu *menuju*. Itu yang membuat
 * sentakan kecil terasa benar-benar melempar.
 *
 * @param {number} velocity px/detik saat dilepas
 * @param {number} deceleration 0,998 untuk rasa gulir biasa; 0,99 lebih cepat berhenti
 */
export function project(velocity, deceleration = 0.998) {
    return ((velocity / 1000) * deceleration) / (1 - deceleration);
}

/**
 * Perlawanan yang meningkat di luar batas.
 *
 * Berhenti mendadak terbaca sebagai "macet"; perlawanan yang terus bertambah
 * terbaca sebagai "masih merespons, tapi memang tidak ada lagi di sana".
 */
export function rubberband(overshoot, dimension, constant = 0.55) {
    if (dimension === 0) {
        return 0;
    }

    return (overshoot * dimension * constant) / (dimension + constant * Math.abs(overshoot));
}

/**
 * Mengikuti jari 1:1 di dalam batas, melawan makin kuat di luarnya.
 */
export function clampRubber(value, min, max, dimension, constant = 0.55) {
    if (value < min) {
        return min + rubberband(value - min, dimension, constant);
    }

    if (value > max) {
        return max + rubberband(value - max, dimension, constant);
    }

    return value;
}

/**
 * Titik tempel terdekat dari sebuah nilai.
 */
export function nearest(value, points) {
    return points.reduce(
        (best, point) => (Math.abs(point - value) < Math.abs(best - value) ? point : best),
        points[0],
    );
}

export function clamp(value, min, max) {
    return Math.min(max, Math.max(min, value));
}
