<template>
    <div id="wrapper">
        <div class="header-nav">
            <navbar :submenu="menus"></navbar>
        </div>
        <div class="content">
                <Nuxt />
        </div> 
        <section-footer></section-footer> 
    </div>
</template>
<style>
    .pt-50{
        padding-top: 6% !important;
    }
</style>
<script>
export default {
    middleware: 'guest',
    data() {
        return {
            menus : []
        }
    },
}
</script>
