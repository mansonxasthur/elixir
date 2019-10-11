import store from '../plugins/store';
import Vue from "vue";

const localization = {
    namespaced: true,
    state: {
        locale: 'en',
    },
    getters: {
        getLocale(state) {
            return state.locale;
        },
    },
    mutations: {
        setLocale(state, lang) {
            state.locale = lang;
        },
    },
};

store.registerModule('localization', localization);
Vue.mixin({
    methods: {
        __(key) {

            if (store.getters['localization/getLocale'] === 'ar') {
                if (this.$root.messages[key]) return this.$root.messages[key];
            }

            return key;

        },
    }
});


export default {
    data() {
        return {
            messages: {},
        }
    },
    created() {
        let html = document.querySelector('html');
        let locale = html.getAttribute('lang');

        this.setLocale(locale);
        if (locale === 'ar') {
            this.initMessages();
        }
    },
    methods: {
        async initMessages() {
            this.messages = await import(/* webpackChunkName: '' */ `../lang/ar`);
        },
        setLocale(locale) {
            store.commit('localization/setLocale', locale);
            store.commit('drawer/setAlignment', locale);

            if (this.$vuetify !== 'undefined') {
                this.$vuetify.lang.current = locale;
                this.$vuetify.rtl = locale === 'ar';
            }
        },
        changeLocale(locale) {
            let location = window.location;
            let path = location.pathname.replace(store.getters['localization/getLocale'], locale);
            window.location = location.href.replace(location.pathname, path);
        },
    },
    computed: {
        right() {
            return this.$vuetify.rtl;
        }
    }
}