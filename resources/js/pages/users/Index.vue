<template>
    <b-container fluid>
        <notifications group="message"/>

        <b-tabs content-class="mt-3">
            <b-tab title="Все" active>
                <user-table :users="users"
                       :loading="loading"
                />
            </b-tab>
            <b-tab title="Удаленные">
                <user-table :users="deleted_users"
                       :loading="loading"
                />
            </b-tab>
        </b-tabs>
    </b-container>
</template>

<script>
    import UserTable from '~/pages/users/Table'

    export default {
        components: {
            UserTable
        },
        data() {
            return {
                loading: false,
            }
        },
        computed: {
            users: function () {
                return this.$store.getters.users;
            },
            deleted_users: function () {
                return this.$store.getters.deleted_users;
            },
        },
        mounted() {
            // Users.loadData(this);
            this.loading = true;
            this.$store.dispatch('loadDataUser').then(() => {
                this.loading = false;
            })
        },
        methods:{
            sendMessage(message) {
                this.$notify({
                    group: 'message',
                    type: 'success',
                    title: 'Клиенты',
                    text: message
                });
            },
        }

    }
</script>

<style scoped>

</style>
