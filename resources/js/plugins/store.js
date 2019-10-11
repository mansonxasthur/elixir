import Vue from "vue";
import Vuex from "vuex";

Vue.use(Vuex);

export default new Vuex.Store({
    modules: {
        drawer: {
            namespaced: true,

            state: {
                visible: true,
                left: true,
            },

            getters: {
                getDrawer(state) {
                    return state.visible;
                },
                isLeft(state) {
                    return state.left;
                },
            },

            mutations: {
                changeDrawer(state, value) {
                    state.visible = value;
                },
                setAlignment(state, value) {
                    state.left = value !== 'ar';
                }
            },
        },
    }
});