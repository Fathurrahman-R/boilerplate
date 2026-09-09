import { draggable, springTo, setNow, clampRubber, project, haptic, prefs } from '../motion';

/**
 * Toast yang bisa disingkirkan dengan sapuan.
 *
 * Keluarnya lewat sumbu yang sama dengan arah sapuan: kalau sesuatu didorong
 * ke kanan, ia harus pergi ke kanan. Menghilang ke arah lain membuat orang
 * kehilangan hubungan antara yang dilakukannya dan yang terjadi.
 *
 * Timernya berhenti selama jari menempel atau kursor melintas — pesan yang
 * sedang dibaca tidak boleh kabur di tengah kalimat.
 */
export default function swipeDismiss(config = {}) {
    const { timeout = 6000, threshold = 400, fraction = 0.4 } = config;

    return {
        show: true,
        dragging: false,
        paused: false,
        remaining: timeout,
        since: 0,
        timer: null,
        dragger: null,

        startTimer() {
            if (this.timer || this.remaining <= 0) {
                return;
            }

            this.since = Date.now();
            this.timer = setTimeout(() => {
                this.timer = null;
                this.dismiss();
            }, this.remaining);
        },

        pauseTimer() {
            if (! this.timer) {
                return;
            }

            clearTimeout(this.timer);
            this.timer = null;
            this.remaining = Math.max(0, this.remaining - (Date.now() - this.since));
        },

        dismiss({ velocity = 0 } = {}) {
            if (velocity !== 0) {
                this.$el.__springVelocity = velocity;
            }

            this.pauseTimer();
            this.show = false;
        },

        swipeInit() {
            this.startTimer();

            const width = () => this.$el.getBoundingClientRect().width || 1;

            this.dragger = draggable(this.$el, {
                axis: 'x',
                hysteresis: 10,

                onPress: () => this.pauseTimer(),

                onStart: () => {
                    this.dragging = true;
                },

                onMove: ({ dx }) => {
                    // Ke arah tepinya jari diikuti apa adanya; ke arah
                    // sebaliknya perlawanannya bertambah.
                    setNow(this.$el, { x: clampRubber(dx, 0, Infinity, width()) });
                },

                onEnd: ({ dx, velocity }) => {
                    this.dragging = false;

                    const thrown = velocity.x > threshold;
                    const past = dx + project(velocity.x) > width() * fraction;

                    if (thrown || past) {
                        haptic('select');
                        this.dismiss({ velocity: velocity.x });

                        return;
                    }

                    springTo(this.$el, { x: 0 }, 'pop', { velocity: velocity.x });
                    this.startTimer();
                },

                onCancel: ({ tapped }) => {
                    this.dragging = false;

                    if (! tapped && ! prefs.reducedMotion) {
                        springTo(this.$el, { x: 0 }, 'pop');
                    }

                    this.startTimer();
                },
            });

            this.$cleanup?.(() => {
                this.pauseTimer();
                this.dragger?.destroy();
            });
        },
    };
}
