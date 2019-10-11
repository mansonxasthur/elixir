import Snackbar from '../components/dialoges/Snackbar';

export default {
    components: {Snackbar},
    data() {
        return {
            snackbar: {
                active: false,
                color: '',
                message: '',
            }
        }
    },
    methods: {
        activateSnackbar(color, message) {
            if (typeof message === 'object') {
                let multiple = '';

                Object.keys(message).forEach(key => {
                    if (typeof message[key] === 'object') {
                        message[key].forEach(message => {
                            multiple += message + '<br>';
                        });
                    } else {
                        multiple += error;
                    }
                });

                this.snackbar.message += multiple;
            } else {
                this.snackbar.message += message;
            }

            this.snackbar.color = color;
            this.snackbar.active = true;
        },
        deactivateSnackbar() {
            this.snackbar.active = false;
            this.snackbar.color = '';
            this.snackbar.message = '';
        }
    }
}