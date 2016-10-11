<style lang="scss" scoped>
  span.icon {
    margin-right: 5px;
  }
</style>

<template>
  <router-link
    :to="{ name: to, params: params }"
    exact
    class="nav-item"
  >
    <span class="icon is-small">
      <i :class="[iconClass(icon)]"></i>
    </span>

    <span>{{ text }}</span>
  </router-link>
</template>

<script>
  export default {
    props: {
      to: {
        required: true,
        type: String
      },

      params: {
        type: Object,
        default: () => {}
      },

      icon: {
        required: true,
        type: String
      },

      text: {
        required: true,
        type: String
      }
    },

    methods: {
      iconClass (icon) {
        return `fa fa-fw fa-${icon}`
      }
    }
  }
</script>
