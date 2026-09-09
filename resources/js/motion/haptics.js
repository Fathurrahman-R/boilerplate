/**
 * Getaran.
 *
 * Tiga aturan yang membuat umpan balik banyak indera terasa menyatu, bukan
 * berisik:
 *
 *  Sebab — harus jelas apa yang memicunya. Getaran dipasang pada peristiwa
 *          penyebabnya sendiri (segmen yang berganti di bawah jari, tuas yang
 *          benar-benar terbalik), bukan pada saat jari dilepas.
 *  Selaras — gambar, suara, dan getaran harus jatuh di frame yang sama.
 *          Karena itu haptic() selalu dipanggil di dalam callback yang sama
 *          dengan yang memulai spring — tidak pernah di setTimeout dan tidak
 *          pernah di penangan animasi selesai.
 *  Guna — hanya untuk momen yang berarti: berhasil, gagal, terkunci di
 *          tempat. Getaran yang muncul di mana-mana melatih orang untuk
 *          mengabaikan semuanya.
 *
 * Hanya jalan di Chrome Android; iOS Safari dan desktop tidak
 * mengimplementasikannya. Jadi ini murni tambahan — tidak boleh ada perubahan
 * keadaan yang bergantung padanya, dan tidak boleh diasumsikan terasa.
 */

const PATTERNS = {
    select: 8,
    commit: [12],
    success: [10, 40, 18],
    warning: [16, 60, 16],
    error: [24, 50, 24, 50, 24],
};

export function haptic(kind = 'select') {
    const pattern = PATTERNS[kind];

    if (! pattern || typeof navigator === 'undefined' || ! navigator.vibrate) {
        return;
    }

    try {
        navigator.vibrate(pattern);
    } catch {
        // Beberapa peramban menolak getaran tanpa interaksi pengguna.
        // Itu bukan kegagalan yang perlu dilaporkan ke siapa pun.
    }
}
