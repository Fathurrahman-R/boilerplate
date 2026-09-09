import { prefs } from './prefs';

/**
 * Tabel token spring.
 *
 * Apple menggambarkan spring dengan dua angka yang bisa dibayangkan perancang,
 * bukan dengan massa/kekakuan/redaman:
 *
 *   damping ratio — seberapa jauh ia melewati target. 1,0 tidak memantul sama
 *                   sekali; di bawah itu memantul, makin kecil makin pegas.
 *   response      — seberapa cepat nilainya sampai, dalam detik. Ini bukan
 *                   durasi: spring tidak punya durasi tetap, waktu diamnya
 *                   muncul sendiri dari parameternya.
 *
 * Motion memakai `bounce` dan `visualDuration`, yang memetakan langsung:
 * bounce ≈ 1 − damping, visualDuration ≈ response.
 *
 * Aturan pemakaiannya: bawaannya tidak memantul. Pantulan hanya dipakai
 * kalau gerakannya memang didahului momentum — lemparan, sentakan, lepasan
 * setelah diseret. Menu yang cuma muncul lalu memantul terasa salah;
 * kartu yang dilempar lalu memantul terasa benar.
 */
const TOKENS = {
    // Tekanan, fokus, centang, rotasi chevron.
    snap: { bounce: 0, visualDuration: 0.25 },

    // Perpindahan posisi biasa: indikator meluncur, lebar sidebar, tinggi
    // accordion, judul yang menciut.
    move: { bounce: 0, visualDuration: 0.4 },

    // Lembar yang datang dan pergi: drawer, sidebar, modal, palette.
    sheet: { bounce: 0.2, visualDuration: 0.34 },

    // HANYA setelah dilepas dengan kecepatan. Inilah satu-satunya tempat
    // pantulan besar dibenarkan, karena jarinya memang melempar.
    throw: { bounce: 0.26, visualDuration: 0.45 },

    // Momen kembali ke tempat: knob toggle, segmen yang menempel, sukses.
    pop: { bounce: 0.2, visualDuration: 0.4 },

    // Scrim, ramp blur, tooltip.
    overlay: { bounce: 0, visualDuration: 0.22 },
};

export function springOptions(token = 'move', { velocity = 0 } = {}) {
    const base = TOKENS[token] ?? TOKENS.move;

    // Gerak yang dikurangi meniadakan perpindahannya, bukan umpan baliknya.
    // Pemanggil memasangkan ini dengan cross-fade opacity.
    if (prefs.reducedMotion) {
        return { type: 'spring', bounce: 0, visualDuration: 0.001, velocity: 0 };
    }

    return { type: 'spring', ...base, velocity };
}

export function springTokens() {
    return TOKENS;
}
