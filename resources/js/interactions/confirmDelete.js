import sheet from './sheet';

/**
 * Dialog konfirmasi hapus yang dipakai bersama seluruh tabel di halaman.
 *
 * Sebelumnya tiap baris menanam modalnya sendiri di DOM: halaman berisi 25
 * baris berarti 25 dialog lengkap yang menunggu, padahal paling banyak satu
 * yang akan dibuka. Sekarang barisnya cukup mengirim URL dan namanya lewat
 * event.
 *
 * Dibangun di atas objek sheet(), bukan disebar dengan spread: sheet()
 * memakai getter (panel, scrim), dan menyebarnya justru memanggil getter itu
 * saat komponen belum punya $refs — nilainya ikut membeku jadi undefined dan
 * getter-nya hilang.
 */
export default function confirmDelete(config = {}) {
    const base = sheet({ axis: 'y', ...config });

    base.url = '';
    base.name = '';
    // Keterangan tambahan untuk hapusan yang membawa akibat lain — mis.
    // pemetaan yang ikut hilang bersama resource-nya.
    base.note = '';

    base.ask = function (detail) {
        this.url = detail?.url ?? '';
        this.name = detail?.name ?? '';
        this.note = detail?.note ?? '';
        this.show();
    };

    return base;
}
