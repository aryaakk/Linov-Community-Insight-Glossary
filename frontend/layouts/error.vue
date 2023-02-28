<template>
    <div class="pt-100">
        <component :is="errorPage" :error="error" />
    </div>
  </template>
  
  <script>
import err404 from '~/components/errors/404.vue'
import errNocode from '~/components/errors/no-code.vue'
export default {
  name: 'nuxt-error',
  layout: 'default', // optional
  props: ['error'],
  computed: {
    errorPage : function(){
        switch (this.error.statusCode) {
            case 404:
                return err404
            default:
                return errNocode
        }
    }
  }
}
  </script>