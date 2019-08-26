<template>
    <v-container fill-height py-5>
        <v-layout row wrap justify-center align-start>
            <v-flex xs12 sm8 md6>
                <v-card>
                    <v-card-title>
                        <h4 class="body-1 grey--text lighten-1">Profile Info</h4>
                        <v-spacer></v-spacer>
                        <v-btn icon small @click="editInfo = true" v-if="!editInfo">
                            <v-icon text small>edit</v-icon>
                        </v-btn>
                        <v-btn icon small @click="updateInfo()" v-if="editInfo">
                            <v-icon text small color="info">save</v-icon>
                        </v-btn>
                        <v-btn icon small @click="cancelInfoEdit()" v-if="editInfo">
                            <v-icon text small color="red">close</v-icon>
                        </v-btn>
                    </v-card-title>
                    <v-card-text class="pa-0">
                        <v-list v-if="editInfo">
                            <v-form ref="editInfo">
                                <v-list-item>
                                    <v-text-field
                                            label="Name"
                                            v-model="user.name"
                                            prepend-icon="person"
                                            :rules="nameRules"
                                    ></v-text-field>
                                </v-list-item>
                                <v-list-item>
                                    <v-text-field
                                            label="Email"
                                            v-model="user.email"
                                            prepend-icon="email"
                                            :rules="emailRules"
                                    ></v-text-field>
                                </v-list-item>
                            </v-form>
                        </v-list>
                        <v-list v-else>
                            <v-list-item>
                                <v-list-item-icon>
                                    <v-icon text>person</v-icon>
                                </v-list-item-icon>
                                <v-list-item-content>
                                    <v-list-item-title v-text="user.name"></v-list-item-title>
                                </v-list-item-content>
                            </v-list-item>
                            <v-list-item>
                                <v-list-item-icon>
                                    <v-icon text>email</v-icon>
                                </v-list-item-icon>
                                <v-list-item-content>
                                    <v-list-item-title v-text="user.email"></v-list-item-title>
                                </v-list-item-content>
                            </v-list-item>
                        </v-list>
                    </v-card-text>
                    <v-divider></v-divider>
                    <v-card-title>
                        <h4 class="body-1 grey--text lighten-1">Change Password</h4>
                        <v-spacer></v-spacer>
                        <v-btn icon small @click="changePassword = true"  v-if="!changePassword">
                            <v-icon text small>edit</v-icon>
                        </v-btn>
                        <v-btn icon small @click="updatePassword()" v-if="changePassword">
                            <v-icon text small color="info">save</v-icon>
                        </v-btn>
                        <v-btn icon small @click="cancelPasswordEdit()" v-if="changePassword">
                            <v-icon text small color="red">close</v-icon>
                        </v-btn>
                    </v-card-title>
                    <v-card-text v-if="changePassword">
                        <v-list>
                            <v-form ref="changePassword">
                                <v-list-item>
                                    <v-text-field
                                            label="Password"
                                            v-model="password"
                                            type="password"
                                            prepend-icon="lock"
                                            :rules="passwordRules"
                                    ></v-text-field>
                                </v-list-item>
                                <v-list-item>
                                    <v-text-field
                                            label="Confirm Password"
                                            v-model="password_confirmation"
                                            type="password"
                                            prepend-icon="lock"
                                            :rules="passwordConfirmationRules"
                                    ></v-text-field>
                                </v-list-item>
                            </v-form>
                        </v-list>
                    </v-card-text>
                </v-card>
            </v-flex>
        </v-layout>

        <snackbar :active="snackbar.active" :color="snackbar.color" :message="snackbar.message"
                  @deactivate="deactivateSnackbar"></snackbar>
    </v-container>
</template>

<script>
    import Snackbar from '../../mixins/Snackbar';
    export default {
        name: "AdminSettings",
        mixins: [Snackbar],
        props: {
            userCollection: {
                required: true,
                type: Object,
            }
        },
        created() {
            this.defaultUser = this.userCollection;
            this.user = Object.assign({}, this.defaultUser);
        },
        data() {
            return {
                editInfo: false,
                changePassword: false,
                user: null,
                defaultUser: null,
                password: '',
                password_confirmation: '',
                nameRules: [
                    v => !!v || '*name is required',
                    v => (!!v && v.length < 50) || '*name is too long',
                ],
                emailRules: [
                    v => !!v || '*email is required',
                    v => !!v && /^.{1,30}@.{1,30}\.[a-z]{2,7}$/.test(v) || '*invalid email',
                ],
                passwordRules: [
                    v => !!v || '*password is required',
                    v => (!!v && v.length >= 8) || '*password should be greater than or equal to 8',
                ],
                passwordConfirmationRules: [
                    v => !!v || '*password confirmation is required',
                    v => (!!v && v === this.password) || '*passwords should match',
                ]
            }
        },
        methods: {
            cancelInfoEdit() {
                this.editInfo = false;
                this.user = Object.assign({}, this.defaultUser);
            },
            updateInfo() {
                if (this.$refs.editInfo.validate()) {
                    let vm = this;
                    axios.put('/ds-admin/settings/info', {
                        name: vm.user.name,
                        email: vm.user.email,
                    })
                        .then(res => {
                            vm.editInfo = false;
                            vm.defaultUser = res.data.data;
                            vm.activateSnackbar('green', res.data.message);
                        })
                        .catch(err => {
                            vm.loading = false;
                            vm.dialog.show = false;
                            if ('response' in err) {
                                if (err.response.status === 422){
                                    vm.activateSnackbar('red', err.response.data.errors);
                                    return;
                                }

                                vm.reset();
                                vm.activateSnackbar('red', err.response.data.message);
                                return;
                            }

                            console.log(err);
                        });
                }
            },
            cancelPasswordEdit() {
                this.changePassword = false;
                this.password = '';
                this.password_confirmation = '';
            },
            updatePassword() {
                if (this.$refs.changePassword.validate()) {
                    let vm = this;
                    axios.put('/ds-admin/settings/password', {
                        password: vm.password,
                        password_confirmation: vm.password_confirmation,
                    })
                        .then(res => {
                            vm.cancelPasswordEdit();
                            vm.activateSnackbar('green', res.data.message);
                        })
                        .catch(err => {
                            vm.loading = false;
                            vm.dialog.show = false;
                            if ('response' in err) {
                                if (err.response.status === 422){
                                    vm.activateSnackbar('red', err.response.data.errors);
                                    return;
                                }

                                vm.reset();
                                vm.activateSnackbar('red', err.response.data.message);
                                return;
                            }

                            console.log(err);
                        });
                }
            }
        }
    }
</script>

<style scoped>

</style>