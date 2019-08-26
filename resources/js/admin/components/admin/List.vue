<template xmlns:v-slot="http://www.w3.org/1999/XSL/Transform">

    <section id="admins">

        <v-card>
            <v-card-title>
                <v-btn color="primary" @click="create">
                    <v-icon left>add</v-icon> New Admin
                </v-btn>
                <v-spacer></v-spacer>
                <v-text-field
                        v-model="search"
                        append-icon="search"
                        label="Search"
                        single-line
                        hide-details
                ></v-text-field>
            </v-card-title>
            <v-card-text class="px-0">
                <v-data-table
                        :headers="headers"
                        :items="admins"
                        :items-per-page="10"
                        :search="search"
                >
                    <template v-slot:item.role.name="{ item }">
                        {{ item.role.name | capitalize }}
                    </template>
                    <template v-slot:item.action="{ item }">
                        <v-btn icon small color="accent">
                            <v-icon small @click="edit(item)">edit</v-icon>
                        </v-btn>
                        <v-btn icon small color="red" dark>
                            <v-icon small @click="confirm(item)">delete</v-icon>
                        </v-btn>
                    </template>
                    <template v-slot:no-results>
                        <v-alert :value="true" color="red" icon="warning" dark>
                            Your search for "{{ search }}" found no results.
                        </v-alert>
                    </template>
                </v-data-table>
            </v-card-text>
        </v-card>

        <v-dialog v-model="dialog.show" persistent max-width="480">
            <v-card>
                <v-card-title class="headline" v-text="title"></v-card-title>
                <v-card-text>
                    <v-form ref="form">
                        <v-text-field
                                label="Name"
                                v-model="admin.name"
                        ></v-text-field>
                        <v-text-field
                                label="Email"
                                v-model="admin.email"
                        ></v-text-field>
                        <v-select
                            :items="roles"
                            item-value="id"
                            item-text="name"
                            label="Select admin role"
                            v-model="admin.role_id"
                        ></v-select>
                    </v-form>
                </v-card-text>
                <v-card-actions>
                    <v-spacer></v-spacer>
                    <v-btn color="primary"
                           text @click="cancel"
                    >cancel</v-btn>
                    <v-btn
                            color="primary"
                            text @click="save"
                            :disabled="admin.name.length < 2"
                            :loading="loading"
                    >Save</v-btn>
                </v-card-actions>
            </v-card>
        </v-dialog>

        <snackbar :active="snackbar.active" :color="snackbar.color" :message="snackbar.message"
                  @deactivate="deactivateSnackbar"></snackbar>

        <delete-dialog
                @confirm="destroy"
                @closeDialog="cancel"
                :loading="loading"
                :delete-dialog="deleteDialog"
        ></delete-dialog>

    </section>

</template>

<script>
    import Snackbar from '../../../mixins/Snackbar';
    import DeleteDialog from '../../../components/dialoges/DeleteDialog';
    export default {
        name: "Admins",
        mixins: [Snackbar],
        components: {DeleteDialog},
        filters: {
          capitalize(str) {
              if (!str) return ''
              str = str.toString()
              return str.charAt(0).toUpperCase() + str.slice(1)
          }
        },
        props: {
            roleCollection: {
                required: true,
                type: Array,
            },
            adminCollection: {
                required: true,
                type: Array,
            }
        },
        data() {
            return {
                loading: false,
                deleteDialog: false,
                dialog: {
                    show: false,
                    action: null,
                },
                search: '',
                headers: [
                    {
                        text: 'Name',
                        align: 'left',
                        value: 'name'
                    },
                    {
                        text: 'Email',
                        value: 'email'
                    },
                    {
                        text: 'Role',
                        value: 'role.name'
                    },
                    {
                        text: '',
                        align: 'center',
                        value: 'action',
                        sortable: false,
                    }
                ],
                admins: [],
                admin: null,
                defaultAdmin: {
                    name: '',
                    email: '',
                    role_id: null,
                }
            }
        },
        created() {
            this.admins = this.adminCollection;
            this.roles = this.roleCollection.map(role => {
                role.name = role.name.charAt(0).toUpperCase() + role.name.slice(1);
                return role;
            });
            this.admin = Object.assign({}, this.defaultAdmin);
        },
        methods: {
            create() {
                this.dialog.show = true;
                this.dialog.action = 'create';
            },
            save() {
                this.loading = true;
                this.dialog.action === 'create' ? this.store() : this.update();
            },
            store() {
                let vm = this;
                axios.post('/ds-admin/admins', {
                    name: vm.admin.name,
                    email: vm.admin.email,
                    role_id: vm.admin.role_id
                })
                    .then(res => {
                        vm.admins.unshift(res.data.data);
                        vm.reset();
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
                    })
            },
            edit(admin) {
                this.admin = Object.assign({}, admin);
                this.dialog.show = true;
                this.dialog.action = 'edit';
            },
            update() {
                let vm = this;
                axios.put(`/ds-admin/admins/${vm.admin.id}`, {
                    name: vm.admin.name,
                    email: vm.admin.email,
                    role_id: vm.admin.role_id,
                })
                    .then(res => {
                        let admin = vm.admins.filter(admin => admin.id === vm.admin.id)[0];
                        let i = vm.admins.indexOf(admin);
                        vm.admins.splice(i, 1, res.data.data);

                        vm.reset();
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
                    })
            },
            confirm(admin) {
                this.admin = admin;
                this.deleteDialog = true;
            },
            destroy() {
                let vm = this;
                let admin = vm.admin;

                axios.delete(`/ds-admin/admins/${admin.id}`)
                    .then(res => {
                        let i = vm.admins.indexOf(admin);
                        vm.admins.splice(i, 1);
                        vm.reset();
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
                    })
            },
            cancel() {
                this.loading = false;
                this.deleteDialog = false;
                this.dialog.show = false;
                this.dialog.action = null;
                this.admin = Object.assign({}, this.defaultAdmin);
            },
            reset() {
                this.loading = false;
                this.cancel();
            }
        },
        computed: {
            title() {
                return this.dialog.action === 'create' ? 'New Admin' : 'Edit Admin'
            }
        }
    }
</script>

<style scoped>

</style>