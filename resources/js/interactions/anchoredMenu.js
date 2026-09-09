/**
 * Menu yang berlabuh pada pemicunya.
 *
 * Dua hal yang ditambahkan di sini, keduanya sebelumnya tidak ada sama
 * sekali:
 *
 *  - Navigasi papan ketik. Menu-menu ini sudah memasang role="menu" tapi
 *    panah atas-bawah tidak melakukan apa pun, jadi janjinya tidak ditepati.
 *  - Fokus yang kembali ke pemicunya saat ditutup, supaya urutan Tab tidak
 *    melompat ke awal halaman.
 *
 * Titik asal transform-nya diurus x-spring lewat prop `origin`, jadi menunya
 * tumbuh dari tombol yang membukanya — bukan dari tengah dirinya sendiri.
 */
export default function anchoredMenu(config = {}) {
    const { itemSelector = '[role="menuitem"]' } = config;

    return {
        open: false,
        active: -1,

        get menuItems() {
            return Array.from(this.$refs.menu?.querySelectorAll(itemSelector) ?? []).filter(
                (el) => ! el.hasAttribute('disabled'),
            );
        },

        toggle() {
            this.open ? this.close() : this.show();
        },

        show() {
            this.open = true;
            this.active = -1;
        },

        close({ restoreFocus = true } = {}) {
            if (! this.open) {
                return;
            }

            this.open = false;
            this.active = -1;

            if (restoreFocus) {
                this.$refs.trigger?.focus();
            }
        },

        move(step) {
            if (! this.open) {
                this.show();

                return;
            }

            const items = this.menuItems;

            if (items.length === 0) {
                return;
            }

            this.active = (this.active + step + items.length) % items.length;
            items[this.active]?.focus();
        },

        first() {
            const items = this.menuItems;

            this.active = 0;
            items[0]?.focus();
        },

        last() {
            const items = this.menuItems;

            this.active = items.length - 1;
            items[this.active]?.focus();
        },
    };
}
