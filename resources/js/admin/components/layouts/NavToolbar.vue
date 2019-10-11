<template>
    <v-app-bar :clipped-left="$vuetify.breakpoint.lgAndUp" app color="primary" dark>
        <v-app-bar-nav-icon @click.stop="changeDrawer(!getDrawer)"></v-app-bar-nav-icon>
        <v-toolbar-title>Application</v-toolbar-title>
        <v-spacer></v-spacer>
        <v-toolbar-items class="hidden-sm-and-down">
            <v-btn text @click="logout()">
                Logout
            </v-btn>
        </v-toolbar-items>
    </v-app-bar>
</template>

<script>
    import {mapMutations, mapGetters} from 'vuex';

    export default {
        name: "NavToolbar",
        data() {
            return {
                //
            }
        },
        computed: {
            ...mapGetters('drawer', ['getDrawer'])
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