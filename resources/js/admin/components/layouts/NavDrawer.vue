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
                    <v-list shaped>

                        <v-list-item-group v-model="item" color="primary">
                            <v-list-item v-for="(item, i) in navItems" :key="i" :href="item.uri">
                                <v-list-item-icon>
                                    <v-icon>{{ item.icon }}</v-icon>
                                </v-list-item-icon>
                                <v-list-item-content>
                                    <v-list-item-title v-text="item.title"></v-list-item-title>
                                </v-list-item-content>
                            </v-list-item>
                        </v-list-item-group>

                    </v-list>
                </v-flex>
                <v-flex pb-4>
                    <v-layout fill-height align-end>
                        <v-flex>
                            <v-list>

                                <v-list-item href="/mx-admin/settings">
                                    <v-list-item-icon>
                                        <v-icon text color="grey lighten-1">mdi-settings</v-icon>
                                    </v-list-item-icon>
                                    <v-list-item-content class="grey--text text-lighten-2">
                                        <v-list-item-title>Settings</v-list-item-title>
                                    </v-list-item-content>
                                </v-list-item>
                                <v-list-item @click="logout" class="hidden-md-and-up">
                                    <v-list-item-icon>
                                        <v-icon text color="grey lighten-1">mdi-exit-to-app</v-icon>
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
                item: null,
                navItems: [
                    {
                        uri: '/mx-admin',
                        icon: 'mdi-view-dashboard',
                        title: 'Dashboard',
                    },
                    {
                        uri: '/mx-admin/admins',
                        icon: 'mdi-account-multiple',
                        title: 'Admins',
                    }
                ]
            }
        },
        created() {
          Object.keys(this.navItems).forEach(key => {
              if(this.navItems[key].uri === window.location.pathname) {
                  this.item = Number(key);
              }
          })
        },
        computed: {
            ...mapGetters('drawer', ['getDrawer']),
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
            ...mapMutations('drawer', ['changeDrawer']),
            logout() {
                axios.post('/mx-admin/logout')
                    .then(() => { window.location = '/mx-admin/login'; });
            }
        }
    }
</script>

<style scoped>

</style>