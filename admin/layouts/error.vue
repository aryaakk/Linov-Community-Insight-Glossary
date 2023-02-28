<template>
    <div>
      <div class="authentication-wrapper authentication-basic container-p-y">
        <div class="authentication-inner">
          <div class="misc-wrapper">
            <h2 class="mb-2 mx-2">{{ error.statusCode }}</h2>
            <p class="mb-4 mx-2">Oops! 😖 {{ error.message }}</p>
            <nuxt-link to="/" class="btn btn-primary">Back to home</nuxt-link>
            <div class="mt-3">
            </div>
          </div>
        </div>
      </div>
    </div>
  </template>
  
  <script>
export default {
  name: 'nuxt-error',
  layout: 'authenticated', // optional
  props: ['error'],
}
  </script>