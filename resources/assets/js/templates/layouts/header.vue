<style lang="scss">
    header {
        user-select: none;

        nav {
            background-color: #283f50;
        }

        .brand-logo img {
            vertical-align: middle;
        }
    }
</style>

<template>
    <header>
        <nav>
            <div class="nav-wrapper container">
                <a v-link="{name: 'home'}" class="brand-logo">
                    <img src="/assets/images/logo.svg" alt="Logo" height="42">
                </a>
                <ul id="nav-mobile" class="right hide-on-med-and-down">
                    <li><a v-link="{name: 'courses'}"><i class="fa fa-book fa-fw"></i> 課程評論</a></li>
                    <li><a v-link="{name: 'ecourse-lite'}"><i class="fa fa-cloud fa-fw"></i> Ecourse Lite</a></li>
                    <li><a v-link="{name: 'home'}"><i class="fa fa-exchange fa-fw"></i> 二手交易</a></li>
                    <template v-if="$parent.$data['user']">
                        <li><a><i class="fa fa-user fa-fw"></i> {{ $parent.$data['user'].nickname }}</a></li>
                        <li><a @click="signOut()"><i class="fa fa-sign-out fa-fw"></i> 登出</a></li>
                    </template>
                    <template v-else>
                        <li><a data-target="sign-in-modal" class="modal-trigger"><i class="fa fa-sign-in fa-fw"></i> 登入</a></li>
                        <li><a v-link="{name: 'sign-up'}"><i class="fa fa-user-plus fa-fw"></i> 註冊</a></li>
                    </template>
                </ul>
            </div>
        </nav>

        <template v-if="! $parent.$data['user']">
            <!-- Sign In Modal -->
            <div id="sign-in-modal" class="modal">
                <div class="modal-content">
                    <h4 class="center">登入</h4>
                    <div class="row">
                        <form @submit.prevent="signIn()" id="sign-in-form" class="col s12">
                            <div class="row">
                                <div class="input-field col s12">
                                    <i class="material-icons prefix">person</i>
                                    <input
                                        v-model="form.username"
                                        id="username"
                                        type="text"
                                        class="validate"
                                        required
                                    >
                                    <label for="username">帳號</label>
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
                        </form>
                    </div>
                </div>
                <div class="modal-footer">
                    <button
                        class="btn waves-effect waves-light"
                        type="submit"
                        name="action"
                        form="sign-in-form"
                    >
                        <span>登入</span><i class="material-icons right">send</i>
                    </button>
                </div>
            </div>
        </template>
    </header>
</template>

<script>
    export default {
        data() {
            return {
                form: {}
            };
        },

        methods: {
            signIn() {
                this.$http.post('/api/v1/auth/sign-in', this.form).then((response) => {
                    this.$dispatch('http-response', response, {
                        'modal-close': '#sign-in-modal'
                    });

                    this.form = {};

                    this.$parent.$data['user'] = response.data;
                }, (response) => {
                    this.$dispatch('http-response', response);
                });
            },

            signOut() {
                this.$http.get('/api/v1/auth/sign-out').then((response) => {
                    this.$parent.$data['user'] = null;
                });
            }
        }
    }
</script>
