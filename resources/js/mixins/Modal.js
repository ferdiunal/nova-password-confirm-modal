import Countdown from "./Countdown";
export default {
    mixins: [Countdown],
    computed: {
        fieldValue() {
            return this.field.displayedAs || this.field.value;
        },
        getValue() {
            return this.result ? this.result : this.fieldValue;
        },
    },
    data() {
        return {
            result: null,
        };
    },
    methods: {
        lock() {
            this.result = null;
            this.countdown = 0;

            if (this.timeout) {
                clearTimeout(this.timeout);
            }
        },
        unLock() {
            if (!this.lock) {
                this.lock = true;
                return;
            }
            this.lock = false;
            this.$nextTick(() => {
                document.getElementById(this.field.attribute).focus();
            });
        },
    },
};
