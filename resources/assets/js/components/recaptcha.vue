<template>
    <div id="{{ $data['recaptcha-token'] }}"></div>
</template>

<script type="text/babel">
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

                    this.gResponse = '';
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
                // 如果尚未定義 grecaptchaLoaded，則代表是第一個呼叫此函式
                if ('undefined' === typeof window.grecaptchaLoaded) {
                    window.grecaptchaLoaded = function (callable) {
                        // 如果 callable 不是 undefined，代表是由 vue-recaptcha component 呼叫，此時將
                        // callable push 到 stack 中；如果是 undefined，則代表是由 recaptcha api 呼叫，
                        // 開始 rendor recaptcha
                        if ('undefined' !== typeof callable) {
                            grecaptchaLoaded.stack.push(callable);
                        } else {
                            while (callable = grecaptchaLoaded.stack.pop()) {
                                callable();
                            }

                            delete window.grecaptchaLoaded;
                        }
                    };

                    window.grecaptchaLoaded.stack = [];
                }

                window.grecaptchaLoaded(this.render);
            } else {
                this.render();
            }
        }
    }
</script>
