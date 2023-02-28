<template>
    <div class="col-md-3 col-xs-6 px-2 py-2">
        <NuxtLink :to="`/training/trainer/${data.id}`" v-if="data">
        <div class="card h-100 p-4">
        <img class="card-trainer-img" :src="data.photo_url">
        <div class="text-center">
            <h4 class="m-0 pt-3">{{ data.name }}</h4>
        </div>
        </div>
        </NuxtLink>
    </div>
</template>
<script>
    export default {
        name: 'panel-trainer',
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