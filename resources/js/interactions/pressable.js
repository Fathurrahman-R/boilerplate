import { springTo, prefs } from '../motion';

/**
 * Umpan balik tekan.
 *
 * Menyorot tombol saat jari *turun*, bukan saat dilepas. Menunggu click
 * membuat tombol terasa mati, dan begitu jeda itu terasa, rasa langsungnya
 * tidak bisa dikembalikan lagi oleh apa pun di belakangnya.
 *
 * Karena skalanya digerakkan spring yang selalu berangkat dari nilai tampil,
 * menyeret jari keluar lalu kembali masuk tidak pernah menghasilkan lompatan:
 * targetnya berubah, gerakannya menyambung.
 */
export default function pressable({ scale = 0.965 } = {}) {
    return {
        pressed: false,

        press() {
            if (this.pressed) {
                return;
            }

            this.pressed = true;

            // Gerak yang dikurangi tetap butuh tanda bahwa tekanan terdaftar;
            // yang dihilangkan perubahan ukurannya, bukan umpan baliknya.
            if (prefs.reducedMotion) {
                this.$el.style.opacity = '0.7';

                return;
            }

            springTo(this.$el, { scale }, 'snap');
        },

        release() {
            if (! this.pressed) {
                return;
            }

            this.pressed = false;

            if (prefs.reducedMotion) {
                this.$el.style.opacity = '';

                return;
            }

            springTo(this.$el, { scale: 1 }, 'pop');
        },

        // Dipasang di elemen: x-data="pressable()" x-bind="pressBind"
        pressBind: {
            ['@pointerdown']() {
                this.press();
            },
            ['@pointerup']() {
                this.release();
            },
            ['@pointercancel']() {
                this.release();
            },
            ['@pointerleave']() {
                this.release();
            },
        },
    };
}
