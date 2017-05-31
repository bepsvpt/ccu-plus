<template>
    <header>
        <nav class="user-select-none">
            <div class="nav-wrapper container">
                <a v-link="{name: 'home'}" class="brand-logo">
                    <img src="/assets/images/logo.svg" alt="Logo">
                </a>

                <a data-activates="mobile-menu" class="button-collapse"><i class="material-icons">menu</i></a>

                <!-- Desktop -->
                <ul id="nav-mobile" class="right hide-on-med-and-down">
                    <li><a v-link="{name: 'courses.index'}"><i class="fa fa-book fa-fw"></i> 課程評論</a></li>
                    <li><a v-if="$root.$data.user" v-link="{name: 'ecourse-lite'}"><i class="fa fa-cloud fa-fw"></i> Ecourse Lite</a></li>

                    <template v-if="$root.$data.user">
                        <li><a><i class="fa fa-user fa-fw"></i> {{ $root.$data.user.nickname }}</a></li>
                        <li><a @click="signOut()"><i class="fa fa-sign-out fa-fw"></i> 登出</a></li>
                    </template>

                    <template v-else>
                        <li><a data-target="sign-in-modal" class="modal-trigger"><i class="fa fa-sign-in fa-fw"></i> 登入</a></li>
                        <li><a v-link="{name: 'sign-up'}"><i class="fa fa-user-plus fa-fw"></i> 註冊</a></li>
                    </template>
                </ul>

                <!-- Mobile -->
                <ul id="mobile-menu" class="side-nav">
                    <li><a v-link="{name: 'courses.index'}"><i class="fa fa-book fa-fw"></i> 課程評論</a></li>
                    <li><a v-if="$root.$data.user" v-link="{name: 'ecourse-lite'}"><i class="fa fa-cloud fa-fw"></i> Ecourse Lite</a></li>

                    <template v-if="$root.$data.user">
                        <li><a><i class="fa fa-user fa-fw"></i> {{ $root.$data.user.nickname }}</a></li>
                        <li><a @click="signOut()"><i class="fa fa-sign-out fa-fw"></i> 登出</a></li>
                    </template>

                    <template v-else>
                        <li><a data-target="sign-in-modal" class="modal-trigger"><i class="fa fa-sign-in fa-fw"></i> 登入</a></li>
                        <li><a v-link="{name: 'sign-up'}"><i class="fa fa-user-plus fa-fw"></i> 註冊</a></li>
                    </template>
                </ul>
            </div>
        </nav>

        <template v-if="! $root.$data.user">
            <!-- Sign In Modal -->
            <div id="sign-in-modal" class="modal">
                <div class="modal-content">
                    <h4 class="center">登入</h4>

                    <form @submit.prevent="signIn()">
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

                        <div class="row">
                            <div class="input-field col s12">
                                <button
                                    type="submit"
                                    class="btn waves-effect waves-light right"
                                >
                                    <span>登入</span><i class="material-icons right">send</i>
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </template>
    </header>
</template>

<script type="text/babel">
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

                    this.$root.$data.user = response.data;

                    ga('send', 'event', 'User', 'sign-in');
                }, (response) => {
                    this.$dispatch('http-response', response);
                });
            },

            signOut() {
                this.$http.get('/api/v1/auth/sign-out').then((response) => {
                    this.$root.$data.user = null;

                    this.$router.go({name: 'home'});
                });
            }
        },

        created() {
            $(document).on('click', '#mobile-menu a', function () {
                $('#sidenav-overlay').click();
            });
        }
    }
</script>
