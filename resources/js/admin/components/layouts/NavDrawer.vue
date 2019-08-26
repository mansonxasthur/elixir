<template>
    <v-navigation-drawer
            v-model="drawer"
            :clipped="$vuetify.breakpoint.lgAndUp"
            disable-resize-watcher
            app
    >
        <v-container fill-height fluid pa-0>
            <v-layout column>
                <v-flex shrink>
                    <v-list>

                        <v-list-item v-for="(item, i) in navItems" :key="i" :href="item.uri">
                            <v-list-item-icon>
                                <v-icon :color="getColor(item)">{{ item.icon }}</v-icon>
                            </v-list-item-icon>
                            <v-list-item-content :class="getColor(item) + '--text'">
                                <v-list-item-title v-text="item.title"></v-list-item-title>
                            </v-list-item-content>
                        </v-list-item>

                    </v-list>
                </v-flex>
                <v-flex pb-4>
                    <v-layout fill-height align-end>
                        <v-flex>
                            <v-list>

                                <v-list-item href="/ds-admin/settings">
                                    <v-list-item-icon>
                                        <v-icon text color="grey lighten-1">settings</v-icon>
                                    </v-list-item-icon>
                                    <v-list-item-content class="grey--text text-lighten-2">
                                        <v-list-item-title>Settings</v-list-item-title>
                                    </v-list-item-content>
                                </v-list-item>
                                <v-list-item @click="logout" class="hidden-md-and-up">
                                    <v-list-item-icon>
                                        <v-icon text color="grey lighten-1">exit_to_app</v-icon>
                                    </v-list-item-icon>
                                    <v-list-item-content class="grey--text text-lighten-2">
                                        <v-list-item-title>Logout</v-list-item-title>
                                    </v-list-item-content>
                                </v-list-item>

                            </v-list>
                        </v-flex>
                    </v-layout>
                </v-flex>
            </v-layout>
        </v-container>
    </v-navigation-drawer>
</template>

<script>
    import {mapMutations, mapGetters} from 'vuex';

    export default {
        name: "NavDrawer",
        data() {
            return {
                navItems: [
                    {
                        uri: '/ds-admin',
                        icon: 'dashboard',
                        title: 'Dashboard',
                    },
                    {
                        uri: '/ds-admin/admins',
                        icon: 'people',
                        title: 'Admins',
                    }
                ]
            }
        },
        computed: {
            ...mapGetters(['getDrawer']),
            drawer: {
                get: function () {
                    return this.getDrawer;
                },
                set: function (val) {
                    this.changeDrawer(val);
                }
            }
        },
        methods: {
            ...mapMutations(['changeDrawer']),
            logout() {
                axios.post('/ds-admin/logout')
                    .then(() => { window.location = '/ds-admin/login'; });
            },
            getColor(item) {
                return item.uri === window.location.pathname ? 'primary' : '';
            }
        }
    }
</script>

<style scoped>

</style>