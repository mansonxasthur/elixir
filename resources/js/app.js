/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */

require('./bootstrap');

window.Vue = require('vue');

import store from './plugins/store';
import vuetify from './plugins/vuetify';

/**
 * The following block of code may be used to automatically register your
 * Vue components. It will recursively scan this directory for the Vue
 * components and automatically register them with their "basename".
 *
 * Eg. ./components/ExampleComponent.vue -> <example-component></example-component>
 */
Vue.component('nav-drawer', require('./components/layouts/NavDrawer').default); // navigation drawer
Vue.component('nav-toolbar', require('./components/layouts/NavToolbar').default); // navigation toolbar
Vue.component('user-settings', require('./components/Settings').default); // settings

/**
 * Next, we will create a fresh Vue application instance and attach it to
 * the page. Then, you may begin adding components to this application
 * or customize the JavaScript scaffolding to fit your unique needs.
 */

import {mapGetters, mapMutations} from "vuex";
import localization from './mixins/localization';

const app = new Vue({
    el: '#app',
    vuetify,
    store,
    mixins: [localization],
    computed: {
        ...mapGetters('drawer', {
            getDrawer: 'getDrawer',
        }),
        drawer: {
            get: function () {
                return this.getDrawer;
            },
            set: function (val) {
                this.changeDrawer(val);
            }
        },
    },
    created() {
        if (this.$vuetify.breakpoint.smAndDown) {
            this.drawer = false;
        }
    },
    methods: {
        ...mapMutations('drawer', ['changeDrawer']),
    },
});
