<style lang="scss" scoped>
  .warning-container {
    &> *:not(:last-child) {
      margin-bottom: 2rem;
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

    .title {
      color: #000;
    }

    .subtitle a {
      color: #00d1b2 !important;
    }

    .quote {
      color: grey;
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

        <h1 class="title">船長，這裡是無風帶</h1>

        <h2 class="subtitle is-4">
          <router-link :to="{ name: 'home' }" exact>回首頁</router-link>
        </h2>

        <p v-if="!saying">&nbsp;</p>

        <transition name="fade">
          <em v-if="saying" class="quote">{{ saying }}</em>
        </transition>
      </div>
    </div>
  </main>
</template>

<script>
  export default {
    data () {
      return {
        quote: {
          content: '',
          author: ''
        }
      }
    },

    computed: {
      saying () {
        if (! this.quote.content) {
          return ''
        }

        return `${this.quote.content} - ${this.quote.author}`
      }
    },

    methods: {
      fetchQuote () {
        this.$http.get('https://quotes.rest/qod.json').then(response => {
          const quote = response.body.contents.quotes.shift()

          this.quote.content = quote.quote
          this.quote.author = quote.author
        }, response => {
          this.quote.content = 'The Higher We Dreamed, the Closer We Succeed.'
          this.quote.author = 'Dreamers'
        })
      }
    },

    created () {
      this.fetchQuote()
    }
  }
</script>
