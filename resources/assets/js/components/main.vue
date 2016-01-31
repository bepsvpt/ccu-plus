<template>
    <app-header></app-header>

    <main>
        <router-view></router-view>
    </main>

    <app-footer></app-footer>
</template>

<script>
    import header from './layouts/header.vue';
    import footer from './layouts/footer.vue';

    export default {
        data() {
            return {
                'sign-in': null
            };
        },

        components: {
            'app-header': header,
            'app-footer': footer
        },

        created() {
            this.$http.get('/api/v1/profile').then((response) => {
                if (Object.keys(response.data).length > 0) {
                    this.$data['sign-in'] = response.data;
                }
            });
        }
    }
</script>
