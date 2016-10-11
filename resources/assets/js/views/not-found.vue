<style lang="scss" scoped>
  .is-fullheight {
    min-height: calc(100vh - 50px);
  }

  .warning-container {
    &> * {
      margin: 1.5rem 0;
    }

    .warning-image {
      position: relative;

      .warning-icon {
        position: absolute;
        bottom: 0;
        margin-left: -2rem;
        color: orange;
      }
    }

    a {
      color: #00d1b2 !important;
    }
  }
</style>

<template>
  <main class="hero is-light is-fullheight">
    <div class="hero-body">
      <div class="container has-text-centered warning-container">
        <div class="warning-image">
          <img src="/assets/images/icon.png">

          <span class="icon is-large warning-icon">
            <i class="fa fa-exclamation-circle"></i>
          </span>
        </div>

        <h1 class="title">船長，這裡沒路了</h1>

        <h2 class="subtitle">
          <router-link :to="{ name: 'home' }" exact>回首頁</router-link>
        </h2>
      </div>
    </div>
  </main>
</template>

<script>
  export default {}
</script>
