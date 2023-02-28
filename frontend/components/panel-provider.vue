<template>
    <div >
        <NuxtLink :to="`/training/provider/${data.id}`" v-if="data">
        <div class="col-md-3 col-xs-6 px-2" style="min-height: 200px;">
            <div class="card p-0" style="position: relative;">
            <img class="h-100" src="/img/training/left-bg.png">
            <div style="position: absolute;top: 15%;left: 6%;">
                <img class="provider-logo" :src="data.logo_url">
                <h4 style="display: inline;"><strong>{{ data.name }}</strong></h4>
            </div>
            </div>
        </div>
        </NuxtLink>
    </div>
</template>

<script>
    export default {
        name: 'panel-provider',
        props: ['data'],
        data() {
            return {
                //
            }
        },
        methods: {
            //
        }
    }
</script>