<template>
    <v-dialog v-model="deleteDialog" max-width="300px">
        <v-card color="red" dark>
            <v-card-title>
                Delete Confirmation!
            </v-card-title>
            <v-card-text>
                Are you sure you want to delete this item?
            </v-card-text>
            <v-card-actions>
                <v-btn
                        :loading="loading"
                        text
                        @click="confirm">Yes</v-btn>
                <v-btn text @click="closeDialog">Cancel</v-btn>
            </v-card-actions>
        </v-card>
    </v-dialog>
</template>

<script>
    export default {
        name: "DeleteDialog",
        props: {
            loading: {
                required: true,
                type: Boolean
            },
            deleteDialog: {
                required: true,
                type: Boolean
            },
        },
        methods: {
            confirm(){
                this.$emit('confirm');
            },
            closeDialog() {
                this.$emit('closeDialog');
            }
        }
    }
</script>

<style scoped>

</style>