export default {
    data() {
        return {
            countdown: 0,
            delay: 1000,
            startCountdown: this.field.countdown ?? 15,
            timeout: null,
        };
    },
    watch: {
        result() {
            if (this.result && this.field.enableCountdown) {
                this.countdown = this.startCountdown;
                this.countdownTimer();
            }
        },
    },
    methods: {
        countdownTimer() {
            if (this.countdown > 0) {
                this.timeout = setTimeout(() => {
                    console.log(this.countdown);
                    this.countdown--;
                    this.countdownTimer();
                }, this.delay);
            } else {
                this.result = null;
                if (this.timeout) {
                    clearTimeout(this.timeout);
                }
            }
        },
    },
};
