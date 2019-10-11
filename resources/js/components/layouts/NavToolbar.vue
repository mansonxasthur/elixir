<template>
    <v-app-bar
            :clipped-right="!isLeft"
            :clipped-left="isLeft"
            app color="primary" dark>
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
            ...mapGetters('drawer', ['getDrawer', 'isLeft'])
        },
        methods: {
            ...mapMutations('drawer', ['changeDrawer']),
            logout() {
                axios.post('/logout')
                    .then(() => { window.location = '/'; });
            }
        }
    }
</script>

<style scoped>

</style>