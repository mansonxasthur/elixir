import colors from "vuetify/lib/util/colors";

/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */

require('./bootstrap');

window.Vue = require('vue');
window.Vuex = require('vuex');
window.Vuetify = require('vuetify');
import translation from "./util/translation";

// Translation provided by Vuetify (javascript)
import en from 'vuetify/es5/locale/en';
import ar from 'vuetify/es5/locale/ar';

Vue.use(Vuex);

Vue.mixin({
    methods: {
        __words(property, nested = '', needle = '') {
            return this.__getTranslation('words', property, nested, needle);
        },
        __sentences(property, nested = '', needle = '') {
            return this.__getTranslation('sentences', property, nested, needle);
        },
        __getTranslation(key, property, nested, needle) {
            let translationStr = translation(this.$vuetify.lang.current, key, property, nested);
            if (needle !== '') {
                translationStr = translationStr.replace(/:\w+:/, needle);
            }

            return translationStr;
        },
        getCookie(cname) {
            let name = cname + "=";
            let decodedCookie = decodeURIComponent(document.cookie);
            let ca = decodedCookie.split(';');
            for (let i = 0; i < ca.length; i++) {
                let c = ca[i];
                while (c.charAt(0) === ' ') {
                    c = c.substring(1);
                }
                if (c.indexOf(name) === 0) {
                    return c.substring(name.length, c.length);
                }
            }
            return "";
        },
        setCookie(name, value)
        {
            document.cookie = `${name}=${value}`;
        },
        __t(model, property) {
            if (this.$vuetify.lang.current === 'ar') {
                if (model.translation && model.translation[property]) {
                    return model.translation[property];
                }
            }

            return model[property];
        }
    }
});

const store = new Vuex.Store({
    state: {
        locale: 'en',
        pageTitle: '',
        drawer: true,
    },
    getters: {
        getLocale(state) {
            return state.locale;
        },
        getPageTitle(state) {
            return state.pageTitle
        },
        getDrawer(state) {
            return state.drawer;
        }
    },
    mutations: {
        setLocale(state, lang) {
            state.locale = lang;
        },
        changePageTitle(state, title) {
            state.pageTitle = title;
        },
        changeDrawer(state, value) {
            state.drawer = value;
        }
    },
    actions: {
        setTitle(context, title) {
            context.commit('changePageTitle', title);
        }
    }
});

Vue.use(Vuetify, {
    lang: {
        locales: {ar, en},
        current: 'en'
    },
    rtl: store.getters.getLocale === 'ar'
});

/**
 * The following block of code may be used to automatically register your
 * Vue components. It will recursively scan this directory for the Vue
 * components and automatically register them with their "basename".
 *
 * Eg. ./components/ExampleComponent.vue -> <example-component></example-component>
 */
Vue.component('nav-drawer', require('./components/layouts/NavDrawer').default); // navigation drawer
Vue.component('nav-toolbar', require('./components/layouts/NavToolbar').default); // toolbar
Vue.component('user-settings', require('./components/Settings').default); // toolbar

// const files = require.context('./', true, /\.vue$/i);
// files.keys().map(key => Vue.component(key.split('/').pop().split('.')[0], files(key).default));

/**
 * Next, we will create a fresh Vue application instance and attach it to
 * the page. Then, you may begin adding components to this application
 * or customize the JavaScript scaffolding to fit your unique needs.
 */

import {mapGetters, mapMutations} from "vuex";
const app = new Vue({
    vuetify: new Vuetify({
        theme: {
            themes: {
                light: {
                    primary: colors.blue.lighten1,
                }
            }
        }
    }),
    el: '#app',
    store,
    computed: {
        ...mapGetters({
            locale: 'getLocale',
            title: 'getPageTitle',
            getDrawer: 'getDrawer',
        }),
        otherLocale() {
            let lang = this.$vuetify.lang.current;
            let locale = lang === 'ar' ? 'en' : 'ar';

            let text = lang === 'ar' ? 'English' : 'العربية';
            return {
                key: locale,
                value: text
            };
        },
        drawer: {
            get: function () {
                return this.getDrawer;
            },
            set: function (val) {
                this.changeDrawer(val);
            }
        },
        right() {
            return this.$vuetify.rtl;
        }
    },
    created() {
        let html = document.querySelector('html');
        let locale = html.getAttribute('lang');

        this.setLocale(locale);
        if (this.$vuetify.breakpoint.smAndDown) {
            this.drawer = false;
        }
    },
    methods: {
        ...mapMutations(['changeDrawer']),
        setLocale(locale) {
            let currentLocale = this.$vuetify.lang.current = locale;
            if (this.locale !== currentLocale) {
                this.$store.commit('setLocale', currentLocale);
            }

        },
        changeLocale() {
            let location = window.location;
            let path = location.pathname.replace(this.locale, this.otherLocale.key);
            window.location = location.href.replace(location.pathname, path);
        }
    },
    watch: {
        locale(val) {
            let isArabic = val === 'ar';
            let content = document.querySelector('.v-content');
            let html = document.querySelector('html');
            if (isArabic) {
                content.setAttribute('style', 'text-align:right;');
                html.setAttribute('lang', 'ar');
            } else {
                content.setAttribute('style', 'text-align:inherit;');
                html.setAttribute('lang', 'en');
            }

            this.$vuetify.rtl = isArabic;
            //this.$store.commit('changeDrawer');
        }
    }
});
