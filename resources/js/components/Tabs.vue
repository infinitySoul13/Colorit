<template>
    <div style="width:100%">
        <div class="tabs">
            <ul>
                <li v-for="(tab, index) in tabs" :class="{ 'is-active': tab.isActive, 'ml-auto': index == 0, 'mr-auto': index == tabs.length-1 }">
                    <a :href="tab.href" @click="selectTab(tab)">{{ tab.name }}</a>
                </li>
            </ul>
        </div>

        <div class="tabs-details">
            <slot></slot>
        </div>
    </div>
</template>

<script>
    export default {
        name: "Tabs",
        data() {
            return {tabs: [] };
        },
        created() {

            this.tabs = this.$children;

        },
        methods: {
            selectTab(selectedTab) {
                this.tabs.forEach(tab => {
                    tab.isActive = (tab.name == selectedTab.name);
                });
            }
        }
    }
</script>

<style scoped>
    .tabs {
        position: relative;
        -webkit-overflow-scrolling: touch;
        -webkit-touch-callout: none;
        -webkit-user-select: none;
        -moz-user-select: none;
        -ms-user-select: none;
        user-select: none;
        -webkit-box-align: stretch;
        -ms-flex-align: stretch;
        align-items: stretch;
        display: -webkit-box;
        display: -ms-flexbox;
        display: flex;
        font-size: 1rem;
        -webkit-box-pack: justify;
        -ms-flex-pack: justify;
        justify-content: space-between;
        overflow: hidden;
        overflow-x: auto;
        white-space: nowrap;
    }

    .tabs:not(:last-child) {
        margin-bottom: 1.5rem;
    }

    .tabs a {
        -webkit-box-align: center;
        -ms-flex-align: center;
        align-items: center;
        border-bottom: 1px solid #dbdbdb;
        color: #79838b;
        display: -webkit-box;
        display: -ms-flexbox;
        display: flex;
        -webkit-box-pack: center;
        -ms-flex-pack: center;
        justify-content: center;
        margin-bottom: -1px;
        padding: 0.5em 1em;
        vertical-align: top;
    }

    .tabs a:hover {
        border-bottom-color: #fff;
        color: #fff;
    }

    .tabs li {
        display: block;
    }

    .tabs li.is-active a {
        border-bottom-color: #dd1427;
        color: #dd1427;
    }

    .tabs ul {
        -webkit-box-align: center;
        -ms-flex-align: center;
        align-items: center;
        border-bottom: 1px solid #dbdbdb;
        display: -webkit-box;
        display: -ms-flexbox;
        display: flex;
        -webkit-box-flex: 1;
        -ms-flex-positive: 1;
        flex-grow: 1;
        -ms-flex-negative: 0;
        flex-shrink: 0;
        -webkit-box-pack: start;
        -ms-flex-pack: start;
        justify-content: flex-start;
    }
    .tabs.is-toggle a {
        border: 1px solid #dbdbdb;
        margin-bottom: 0;
        position: relative;
    }

    .tabs.is-toggle a:hover {
        background-color: whitesmoke;
        border-color: #b5b5b5;
        z-index: 2;
    }

    .tabs.is-toggle li + li {
        margin-left: -1px;
    }

    .tabs.is-toggle li:first-child a {
        border-radius: 3px 0 0 3px;
    }

    .tabs.is-toggle li:last-child a {
        border-radius: 0 3px 3px 0;
    }

    .tabs.is-toggle li.is-active a {
        background-color: #dd1427;
        border-color: #dd1427;
        color: #fff;
        z-index: 1;
    }

    .tabs.is-toggle ul {
        border-bottom: none;
    }
</style>