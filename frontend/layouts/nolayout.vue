<template>
    <div id="wrapper">
        <div class="content pt-50">
                <Nuxt />
        </div> 
    </div>
</template>
<script>
export default {
    middleware: 'guest',
}
</script>
