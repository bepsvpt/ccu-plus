<template>
    <div id="{{ recaptcha }}"></div>
</template>

<script>
    export default {
        data() {
            return {
                recaptcha: 'recaptcha-xxxxxxxx-xxxx'.replace(/[x]/g, function (c) {
                    return (Math.random() * 16 | 0).toString(16);
                })
            };
        },

        props: {
            'theme': String,
            'size': String,
            'gRecaptchaResponse': {
                type: String,
                required: true,
                twoWay: true
            }
        },

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
                    'size': this.size || 'normal',
                    'callback': (token) => {
                        this.gRecaptchaResponse = token;
                    }
                });
            }
        },

        ready() {
            this.render();
        }
    }
</script>
