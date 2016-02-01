<template>
    <div id="{{ recaptcha }}"></div>
</template>

<script>
    export default {
        data() {
            return {
                recaptcha: 'xxxxxxxx-xxxx-4xxx-yxxx-xxxxxxxxxxxx'.replace(/[xy]/g, function(c) {
                    let r = Math.random() * 16 | 0,
                        v = c == 'x' ? r : (r & 0x3 | 0x8);

                    return v.toString(16);
                })
            };
        },

        props: ['theme', 'size'],

        methods: {
            render(tries = 0) {
                // give up if trying more than 10 times.
                if (10 === tries) {
                    return;
                } else if ('undefined' === typeof grecaptcha) {
                    setTimeout(() => {
                        this.render(++tries);
                    }, 250);

                    return;
                }

                grecaptcha.render(this.recaptcha, {
                    'sitekey': '6LewOhMTAAAAAHcaysnPL99Vd9iMlYvLAOA_8Yy9',
                    'theme': this.theme || 'light',
                    'size': this.size || 'normal'
                });
            }
        },

        ready() {
            this.render();
        }
    }
</script>
