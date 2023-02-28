<template>
    <div class="pt-100">
        <div class="container" ref="providers">
            <div class="row py-4 mr-1 ml-1 heading-training" style="background-image: url('/img/training/bg-provider.png');">
                <div class="col-md-4 text-center py-4">
                    <img src="/img/training/icon_provider.svg">
                </div>
                <div class="col-md-8">
                <h3>Provider</h3>
                <p>Ada banyak provider di bidang human resources yang terdapat di LinovCommunity. Provider yang tersedia merupakan provider terbaik yang dapat membantu peningkatan skill HR.</p>
                </div>
            </div>
            <div class="row mt-5 p-0">
                <div class="col-md-3">
                    <client-only>
                    <nav-training :sorts="sorts" @onFilter="filterData"></nav-training>
                    </client-only>
                </div>
                <div class="col-md-9">
                    <div class="panel-subhead py-4 d-flex justify-content-between">
                        <div class="d-flex">
                            <div class="icon-subhead">
                                <img src="/img/icon/ic_provider.png">
                            </div>
                            <div class="title-subhead">
                                <label>Provider</label>
                            </div>
                        </div>
                    </div>
                    <div class="row p-0">
                        <panel-provider :data="provider" v-for="(provider, idx) in providers" :key="idx"></panel-provider>
                    </div>

                    <div class="panel-subhead py-4 d-flex justify-content-between">
                        <div class="d-flex">
                            <div class="icon-subhead">
                                <img src="/img/icon/ic_star.png">
                            </div>
                            <div class="title-subhead">
                                <label>Trainer</label>
                            </div>
                        </div>
                    </div>
                    <div class="row d-flex-wrap p-0">
                        <panel-trainer :data="trainer" v-for="(trainer, idx) in trainers" :key="idx"></panel-trainer>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    export default {
        head: {
            title: 'Provider',
        },
        layout: "default-training",
        data() {
            return {
                trainers: [],
                providers: [],
                sorts: ['Newest', 'A to Z', 'Z to A'],
                loading: false,
                stopInfinite: false,
                params: {
                    page: 1,
                    perpage: 8
                },
            }
        },
        async fetch(){
            await this.$getAllTrainer({perpage: 4}).then(response=>{
                this.trainers = response.data.data
            })
            await this.$getAllProvider(this.params).then(response=>{
                this.providers = response.data.data
            })
            this.params.page += 1;
        },
        mounted(){
            window.addEventListener("scroll", this.handleScroll)
        },
        unmounted() {
            window.removeEventListener("scroll", this.handleScroll)
        },
        methods: {
            async filterData(params){
                this.params.page = 1
                this.params = {...this.params,...params}
                await this.$getAllProvider(this.params).then(response=>{
                    this.providers = response.data.data
                })
            },
            async handleScroll() {
                if (!this.stopInfinite && !this.loading && this.$refs.providers && Math.floor(this.$refs.providers.getBoundingClientRect().bottom) <= window.innerHeight) {
                    this.loading = true
                    const { data } =  await this.$getAllProvider(this.params)
                    this.providers = [...this.providers,...data.data]
                    this.params.page += 1;
                    this.loading = false
                    this.stopInfinite = this.providers.length >= data.total
                }
            },
        }
    }
</script>