/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */

require('../bootstrap');

window.Vue = require('vue');
window.Vuex = require('vuex');
window.Vuetify = require('vuetify');

import translation from "../util/translation";

Vue.use(Vuex);
Vue.use(Vuetify);
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
        drawer: true,
    },
    getters: {
      getDrawer(state) {
          return state.drawer;
      }
    },
    mutations: {
        changeDrawer(state, value) {
            state.drawer = value;
        }
    },
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
Vue.component('admins', require('./components/admin/List').default); // admins
Vue.component('admin-settings', require('./components/Settings').default); // admins

// const files = require.context('./', true, /\.vue$/i);
// files.keys().map(key => Vue.component(key.split('/').pop().split('.')[0], files(key).default));

/**
 * Next, we will create a fresh Vue application instance and attach it to
 * the page. Then, you may begin adding components to this application
 * or customize the JavaScript scaffolding to fit your unique needs.
 */
import colors from 'vuetify/lib/util/colors';

const app = new Vue({
    vuetify: new Vuetify({
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
    }),
    el: '#app',
    store,
    created() {
        if (this.$vuetify.breakpoint.smAndDown) {
            this.$store.commit('changeDrawer', false);
        }
    }
});
