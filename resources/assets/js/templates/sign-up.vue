<template>
    <h2 class="center">帳號註冊</h2>

    <div class="row">
        <form @submit.prevent="signUp()" class="col l6 offset-l3 s12">
            <!-- username -->
            <div class="row">
                <div class="input-field col s12">
                    <i class="material-icons prefix">person</i>
                    <input
                        v-model="form.username"
                        id="username"
                        type="text"
                        pattern="\d{9}"
                        class="validate"
                        maxlength="9"
                        autofocus
                        required
                    >
                    <label for="username">單一入口帳號</label>
                </div>
            </div>

            <!-- password -->
            <div class="row">
                <div class="input-field col s12">
                    <i class="material-icons prefix">lock</i>
                    <input
                        v-model="form.password"
                        id="password"
                        type="password"
                        class="validate"
                        required
                    >
                    <label for="password">單一入口密碼</label>
                </div>
            </div>

            <!-- nickname -->
            <div class="row">
                <div class="input-field col s12">
                    <i class="material-icons prefix">contacts</i>
                    <input
                        v-model="form.nickname"
                        id="nickname"
                        type="text"
                        class="validate"
                        pattern=".{3,}"
                        title="至少需三個字"
                        maxlength="12"
                        length="12"
                        required
                    >
                    <label for="nickname">暱稱</label>
                </div>
            </div>

            <!-- recaptcha -->
            <div class="row">
                <div class="col s12">
                    <recaptcha :g-response.sync="form['g-recaptcha-response']"></recaptcha>
                </div>
            </div>

            <!-- submit -->
            <div class="row">
                <div class="col s12">
                    <button type="submit" class="btn waves-effect waves-light right">
                        <span>註冊</span><i class="material-icons right">send</i>
                    </button>
                </div>
            </div>
        </form>
    </div>
</template>

<script type="text/babel">
    import Recaptcha from '../components/recaptcha.vue';

    export default {
        data() {
            return {
                form: {
                    'g-recaptcha-response': ''
                }
            };
        },

        components: {
            Recaptcha
        },

        methods: {
            signUp() {
                this.$http.post('/api/v1/auth/sign-up', this.form).then((response) => {
                    this.$dispatch('http-response', response, {
                        redirect: {
                            name: 'home'
                        },
                        messages: {
                            toastSuccess: ['註冊成功']
                        }
                    });

                    this.$root.$data.user = response.data;

                    ga('send', 'event', 'User', 'sign-up');
                }, (response) => {
                    this.$dispatch('http-response', response);
                });
            }
        }
    }
</script>
