<template>
  <section class="hero is-light is-fullheight">
    <div class="hero-body">
      <div class="container has-text-centered">
        <div>
          <img src="/assets/images/icon.png">

          <span class="icon is-large">
            <i class="fa fa-exclamation-circle"></i>
          </span>
        </div>

        <h1 class="title">
          船長，這裡沒路了
        </h1>

        <h2 class="subtitle">
          <router-link :to="{ name: 'home' }">回首頁</router-link>
        </h2>
      </div>
    </div>
  </section>
</template>

<script>
  export default {}
</script>
