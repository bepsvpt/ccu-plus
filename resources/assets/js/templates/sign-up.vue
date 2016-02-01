<template>
    <h2 class="center">帳號註冊</h2>

    <div class="row">
        <form @submit.prevent="signUp()" class="col l6 offset-l3 s12">
            <div class="row">
                <div class="input-field col s12">
                    <i class="material-icons prefix">perm_identity</i>
                    <input
                        v-model="form.username"
                        id="username"
                        type="text"
                        class="validate"
                        maxlength="9"
                        length="9"
                        required
                    >
                    <label for="username">學號</label>
                </div>
            </div>

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
                    <label for="password">密碼</label>
                </div>
            </div>

            <div class="row">
                <div class="input-field col s12">
                    <i class="material-icons prefix">contacts</i>
                    <input
                        v-model="form.nickname"
                        id="nickname"
                        type="text"
                        class="validate"
                        maxlength="12"
                        length="12"
                        required
                    >
                    <label for="nickname">暱稱</label>
                </div>
            </div>

            <div class="row">
                <div class="input-field col s12">
                    <i class="material-icons prefix">email</i>
                    <input
                        v-model="form.email"
                        id="email"
                        type="email"
                        class="validate"
                        maxlength="48"
                        length="48"
                        required
                    >
                    <label for="email">信箱</label>
                </div>
            </div>

            <div class="row">
                <div class="col s12">
                    <recaptcha></recaptcha>
                </div>
            </div>

            <div class="row">
                <div class="col s12">
                    <button class="btn waves-effect waves-light right" type="submit" name="action">
                        <span>註冊</span><i class="material-icons right">send</i>
                    </button>
                </div>
            </div>
        </form>
    </div>
</template>

<script>
    export default {
        data() {
            return {
                form: {}
            };
        },

        methods: {
            signUp() {
                this.$http.post('/api/v1/auth/sign-up', this.form).then((response) => {
                    this.$dispatch('http-success', response)
                }, (response) => {
                    this.$dispatch('http-error', response);
                });
            }
        }
    }
</script>
