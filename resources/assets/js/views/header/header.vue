<style lang="scss" scoped>
  nav.nav {
    background-color: #ffb900;

    .nav-left {
      img {
        height: 3rem;
      }
    }
  }
</style>

<template>
  <nav class="nav">
    <div class="container">
      <div class="nav-left"></a>
        <router-link :to="{ name: 'home' }" exact class="nav-item is-brand">
          <img src="/assets/images/logo.svg" alt="CCU PLUS">
        </router-link>
      </div>

      <span class="nav-toggle">
        <span></span>
        <span></span>
        <span></span>
      </span>

      <div class="nav-right nav-menu">
        <nav-item :to="'home'" :icon="'home'" :text="'首頁'"></nav-item>
        <nav-item :to="'home'" :icon="'book'" :text="'課程評論'"></nav-item>
        <nav-item :to="'home'" :icon="'shopping-cart'" :text="'二手交易'"></nav-item>
        <nav-item :to="'home'" :icon="'cloud'" :text="'Ecourse Lite'"></nav-item>
        <nav-item :to="'home'" :icon="'sign-out'" :text="'登出'"></nav-item>
        <nav-item :to="'home'" :icon="'sign-in'" :text="'登入'"></nav-item>
        <nav-item :to="'home'" :icon="'user-plus'" :text="'註冊'"></nav-item>
      </div>
    </div>
  </nav>
</template>

<script>
  import NavItem from './nav-item.vue'

  export default {
    components: {
      NavItem
    }
  }
</script>
