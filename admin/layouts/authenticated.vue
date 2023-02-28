<template>
    <div class="layout-wrapper layout-content-navbar">
      <div class="layout-container">
        <side-menu></side-menu>
        <div class="layout-page">
            <head-menu></head-menu>
            <div class="content-wrapper">
                <div class="container-xxl flex-grow-1 container-p-y">
                    <Nuxt/>
                </div>
            </div>
        </div>
      </div>
    </div>
</template>

<script>
    export default {
        middleware: 'authenticated'
    }
</script>