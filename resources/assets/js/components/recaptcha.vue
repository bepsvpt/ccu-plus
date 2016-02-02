<template>
    <div id="{{ $data['recaptcha-token'] }}"></div>
</template>

<script>
    export default {
        data() {
            return {
                'recaptcha-id': -1,
                'recaptcha-token': 'recaptcha-xxxxxxxx-xxxx'.replace(/[x]/g, function (c) {
                    return (Math.random() * 16 | 0).toString(16);
                })
            };
        },

        props: {
            'theme': {
                type: String,
                default: 'light'
            },
            'size': {
                type: String,
                default: 'normal'
            },
            'gResponse': {
                type: String,
                required: true,
                twoWay: true
            }
        },

        events: {
            'recaptcha-reset'() {
                if (this.$data['recaptcha-id'] > -1) {
                    grecaptcha.reset(this.$data['recaptcha-id']);
                }
            }
        },

        methods: {
            render() {
                this.$data['recaptcha-id'] = window.grecaptcha.render(this.$data['recaptcha-token'], {
                    'sitekey': '6LewOhMTAAAAAHcaysnPL99Vd9iMlYvLAOA_8Yy9',
                    'theme': this.theme,
                    'size': this.size,
                    'callback': (token) => {
                        this.gResponse = token;
                    }
                });
            }
        },

        ready() {
            if ('undefined' === typeof  window.grecaptcha) {
                window.grecaptchaLoaded = () => {
                    this.render();
                };
            } else {
                this.render();
            }
        }
    }
</script>
