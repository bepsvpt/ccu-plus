<template>
    <header>
        <nav>
            <div class="nav-wrapper container">
                <a v-link="{name: 'home'}" class="brand-logo">Logo</a>
                <ul id="nav-mobile" class="right hide-on-med-and-down">
                    <li><a>課程評論</a></li>
                    <li><a>Ecourse Lite</a></li>
                    <template v-if="$parent['sign-in']">
                        <li><a v-link="{name: 'auth.sign-out'}">登出</a></li>
                    </template>
                    <template v-else>
                        <li><a>登入</a></li>
                        <li><a v-link="{name: 'auth.sign-up'}">註冊</a></li>
                    </template>
                </ul>
            </div>
        </nav>
    </header>
</template>

<script>
    export default {
    }
</script>
