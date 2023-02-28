<template>
    <div class="container-xxl">
      <div class="authentication-wrapper authentication-basic container-p-y">
        <div class="authentication-inner">
            <Nuxt/>
        </div>
      </div>
    </div>
</template>

<script>
export default {
    middleware: 'guest'
}
</script>
