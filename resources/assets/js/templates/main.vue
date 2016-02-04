<style lang="scss">
    html {
        font-family: "Lato", "Roboto", sans-serif;
        font-size: 16px;
        overflow-y:scroll;
    }

    body {
        display: flex;
        min-height: 100vh;
        flex-direction: column;
    }

    main {
        flex: 1 0 auto;
        margin-top: 20px !important;
    }
</style>

<template>
    <app-header></app-header>

    <main class="container">
        <router-view></router-view>
    </main>

    <app-footer></app-footer>
</template>

<script>
    import header from './layouts/header.vue';
    import footer from './layouts/footer.vue';
    import events from '../components/events';

    export default {
        data() {
            return {
                user: null
            };
        },

        components: {
            'app-header': header,
            'app-footer': footer
        },

        events: events,

        created() {
            this.$http.get('/api/v1/profile').then((response) => {
                if (response.data.hasOwnProperty('username')) {
                    this.$data['user'] = response.data;
                }
            });
        }
    }
</script>
