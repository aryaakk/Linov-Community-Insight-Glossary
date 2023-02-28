<template>
  <div  v-if="loading" class="preloader" id="preloader" style=" position: fixed;
        width: 100%;
        height: 100%;
        top: 0;
        left: 0;
        z-index: 100000;
        backface-visibility: hidden;
        background: rgba(0, 0, 0, 0.5);">
      <div class="preloader_img" style="width: 200px;
            height: 200px;
            position: absolute;
            left: 48%;
            top: 48%;
            background-position: center;
            z-index: 999999">
            <img src="/img/loader.gif" style=" width: 50px;" alt="loading...">
        </div>
  </div>
</template>

<script>
  export default {
    data() {
      return {
         loading: false
      }
    },
    methods: {
      start() {
        this.loading = true
      },
      finish() {
        this.loading = false
      }
    }
  }
</script>