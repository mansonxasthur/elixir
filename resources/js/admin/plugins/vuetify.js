import Vue from 'vue';
import Vuetify from 'vuetify';
import 'vuetify/dist/vuetify.min.css';
import ar from "vuetify/es5/locale/ar";
import en from "vuetify/es5/locale/en";

let html = document.querySelector('html');
let locale = html.getAttribute('lang');

Vue.use(Vuetify, {
    lang: {
        locales: {ar, en},
        current: locale
    },
    rtl: locale === 'ar',
    theme: {
        themes: {
            light: {
                primary: '#1976D2',
                secondary: '#424242',
                accent: '#82B1FF',
                error: '#FF5252',
                info: '#2196F3',
                success: '#4CAF50',
                warning: '#FFC107',
            }
        }
    }
});

Vue.use(Vuetify);

const opts = {};

export default new Vuetify(opts);