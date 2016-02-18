<template>
    <app-header></app-header>

    <main class="container">
        <router-view></router-view>
    </main>

    <app-footer></app-footer>
</template>

<script type="text/babel">
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
                if (response.data.hasOwnProperty('nickname')) {
                    this.$data['user'] = response.data;
                }
            });
        }
    }
</script>
