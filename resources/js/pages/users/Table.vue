<template>
    <b-row>
        <b-col lg="6" class="my-1">
            <b-form-group
                    label="Сортировка"
                    label-cols-sm="3"
                    label-align-sm="right"
                    label-size="sm"
                    label-for="sortBySelect1"
                    class="mb-0"
            >
                <b-input-group size="sm">
                    <b-form-select v-model="sortBy" id="sortBySelect1" :options="sortOptions" class="w-75">
                        <template v-slot:first>
                            <option value="">-- none --</option>
                        </template>
                    </b-form-select>
                    <b-form-select v-model="sortDesc" size="sm" :disabled="!sortBy" class="w-25">
                        <option :value="false">Asc</option>
                        <option :value="true">Desc</option>
                    </b-form-select>
                </b-input-group>
            </b-form-group>
        </b-col>

        <b-col lg="6" class="my-1">
            <b-form-group
                    label="Поиск"
                    label-cols-sm="3"
                    label-align-sm="right"
                    label-size="sm"
                    label-for="filterInput"
                    class="mb-0"
            >
                <b-input-group size="sm">
                    <b-form-input
                            v-model="filter"
                            type="search"
                            id="filterInput"
                            placeholder="Введите для поиска"
                    />
                    <b-input-group-append>
                        <b-button :disabled="!filter" @click="filter = ''">Очистить</b-button>
                    </b-input-group-append>
                </b-input-group>
            </b-form-group>
        </b-col>

        <b-col sm="5" md="6" class="my-1">
            <b-form-group
                    label="На странице"
                    label-cols-sm="6"
                    label-cols-md="4"
                    label-cols-lg="3"
                    label-align-sm="right"
                    label-size="sm"
                    label-for="perPageSelect1"
                    class="mb-0"
            >
                <b-form-select
                        v-model="perPage"
                        id="perPageSelect1"
                        size="sm"
                        :options="pageOptions"
                />
            </b-form-group>
        </b-col>

        <b-col sm="7" md="6" class="my-1">
            <b-pagination
                    v-model="currentPage"
                    :total-rows="totalRows"
                    :per-page="perPage"
                    align="fill"
                    size="sm"
                    class="my-0"
            />
        </b-col>
    </b-row>
    <b-table
            show-empty
            small
            stacked="md"
            :items="users"
            :fields="fields"
            :current-page="currentPage"
            :per-page="perPage"
            :filter="filter"
            :filterIncludedFields="filterOn"
            :sort-by.sync="sortBy"
            :sort-desc.sync="sortDesc"
            :sort-direction="sortDirection"
            @filtered="onFiltered"
            :busy="loading"
            empty-text="Нет записей для отображения"
            empty-filtered-text="Нет записей, соответствующих вашему запросу"
    >
        <template v-slot:cell(name)="data">
            <b-input-group size="sm">
                <b-form-input disabled :value="data.item.name"
                              @blur="save($event.target.value,data.item.id,'name')"
                              placeholder="Введите username"/>
            </b-input-group>
        </template>
        <template v-slot:cell(email)="data">
            <b-input-group size="sm">
                <b-form-input
                        v-model="data.item.email"
                        disabled
                        type="email"
                        @change="save($event.target.value, data.item.id,'email')"
                >
                </b-form-input>
            </b-input-group>
        </template>
        <template v-slot:cell(phone)="data">
            <b-input-group size="sm">
                <b-form-input
                        v-model="data.item.phone"
                        disabled
                        type="tel"
                        @change="save($event.target.value, data.item.id,'phone')"
                >
                </b-form-input>
            </b-input-group>
        </template>
        <template v-slot:cell(telegram_chat_id)="data">
            <b-input-group size="sm">
                <b-form-input disabled :value="data.item.telegram_chat_id"
                              @blur="save($event.target.value,data.item.id,'telegram_chat_id')"
                              placeholder="Введите Telegram ID"/>
            </b-input-group>
        </template>
        <template v-slot:cell(fio_from_telegram)="data">
            <b-input-group size="sm">
                <b-form-input disabled :value="data.item.fio_from_telegram"
                              @blur="save($event.target.value,data.item.id,'fio_from_telegram')"
                              placeholder="Введите имя из телеграм"/>
            </b-input-group>
        </template>
        <template v-slot:cell(action)="data">
            <b-input-group size="sm" >
                <b-button v-if="data.item.deleted_at==null" :href="'/users/'+ data.item.id" class="btn btn-info mr-1 mb-1"
                          :disabled="data.item.id===null">
                    Подробнее
                </b-button>
                <b-button v-if="data.item.deleted_at==null" @click="remove(data.item.id)" class="btn btn-info mr-1 mb-1"
                          :disabled="data.item.id===null">
                    Удалить
                </b-button>
                <b-button v-else @click="restore(data.item.id)" class="btn btn-info mr-1 mb-1"
                          :disabled="data.item.id===null">
                    Восстановить
                </b-button>

            </b-input-group>
        </template>
        <template v-slot:table-busy>
            <div class="text-center text-primary my-2">
                <b-spinner class="align-middle"/>
                <strong>Загрузка...</strong>
            </div>
        </template>
    </b-table>
</template>

<script>
    export default {
        name: "Table",
        props: {
            users: {
                type: Array,
                required: true,
                default: function(){
                    return [];
                }
            },
            loading: {
                type: Boolean,
                default: true
            },
        },
        data() {
            return {

                sortBy: 'name',
                sortDesc: false,

                totalRows: 1,
                currentPage: 1,
                perPage: 5,
                sortDirection: 'asc',
                filter: null,
                filterOn: [],

                pageOptions: [5, 10, 15, 25, 50, 100],

                fields: [
                    {key: 'id', label: 'ID', sortable: true, sortDirection: 'desc'},
                    {key: 'name', label: 'Имя', sortable: true, sortDirection: 'desc'},
                    {key: 'email', label: 'Email', sortable: true, sortDirection: 'desc'},
                    {key: 'phone', label: 'Телефон', sortable: true, sortDirection: 'desc'},
                    {key: 'telegram_chat_id', label: 'Telegram ID', sortable: true, sortDirection: 'desc'},
                    {key: 'fio_from_telegram', label: 'Имя в телеграм', sortable: true, sortDirection: 'desc'},
                    {key: 'action', label: 'Действия'}
                ],

                // infoModal:null,

                in_process: [],
                // loading: false,

            }
        },
        mounted() {
            this.totalRows = this.users.length;
        },
        computed: {
            sortOptions() {
                return this.fields
                    .filter(f => f.sortable)
                    .map(f => {
                        return {text: f.label, value: f.key}
                    })
            },
        },
        methods: {
            sendMessage(message) {
                this.$notify({
                    group: 'message',
                    type: 'success',
                    title: 'Клиенты',
                    text: message
                });
            },
            remove(id) {
                this.$store.dispatch('removeUser', id).then(resp => {
                    this.sendMessage(this, resp.data.message)
                })
            },
            restore(id) {
                this.$store.dispatch('restoreUser', id)
                    .then(resp => {
                        this.sendMessage(this, resp.data.message)
                    });
            },
            destroy(id) {
                this.$store.dispatch('forceDeleteUser', id).then(resp => {
                    this.sendMessage(this, resp.data.message)
                })
            },
            save(value, id, key) {
                this.$store.dispatch('saveUser', {
                    id: id,
                    key: key,
                    value: value
                }).then(resp => {
                    this.$store.commit('saveUser', resp.data.user);
                    this.sendMessage(this, resp.data.message)
                });
            },
            onFiltered(filteredItems) {
                this.totalRows = filteredItems.length;
                this.currentPage = 1
            },
        }
    }
</script>

<style scoped>

</style>