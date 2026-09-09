/**
 * Tepi gulir.
 *
 * Menggantikan garis pembatas 1px di bawah chrome yang mengambang. Alih-alih
 * garis yang selalu ada, kabut tipis muncul hanya di sisi yang memang masih
 * menyembunyikan konten — jadi ia menyampaikan sesuatu, bukan sekadar
 * menggambar batas.
 *
 * Kelas .scroll-edge / .scroll-edge-x yang menggambar kabutnya; di sini hanya
 * opacity-nya yang ditulis.
 */
export default function scrollEdge({ axis = 'y', threshold = 4 } = {}) {
    return {
        update() {
            const el = this.$el;

            if (axis === 'x') {
                const max = el.scrollWidth - el.clientWidth;

                el.style.setProperty('--edge-start', el.scrollLeft > threshold ? '1' : '0');
                el.style.setProperty(
                    '--edge-end',
                    max - el.scrollLeft > threshold ? '1' : '0',
                );

                return;
            }

            const max = el.scrollHeight - el.clientHeight;

            el.style.setProperty('--edge-top', el.scrollTop > threshold ? '1' : '0');
            el.style.setProperty('--edge-bottom', max - el.scrollTop > threshold ? '1' : '0');
        },

        init() {
            this.update();

            // Isi bisa berubah tanpa ada yang menggulir — hasil filter,
            // fragmen yang baru dimuat, jendela yang berubah ukuran.
            const observer = new ResizeObserver(() => this.update());

            observer.observe(this.$el);

            if (this.$el.firstElementChild) {
                observer.observe(this.$el.firstElementChild);
            }

            this.$el.addEventListener('scroll', () => this.update(), { passive: true });

            this.$cleanup?.(() => observer.disconnect());
        },
    };
}
