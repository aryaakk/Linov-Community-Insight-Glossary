<template>
    <div class="pt-100">
        <div class="container" ref="events">
            <div class="row py-4 mr-1 ml-1 heading-training" style="background-image: url('/img/training/bg-event.png');">
                <div class="col-md-4 text-center py-4">
                    <img src="/img/training/icon_event.svg">
                </div>
                <div class="col-md-8">
                <h3>Event</h3>
                <p>Ikuti berbagai event bersama LinovCommunity dan tingkatkan kemampuan serta skill di bidang human resources. Didalam LinovCommnuity terdapat banyak pilihan event untuk diikuti.</p>
                </div>
            </div>
            <div class="row mt-5 p-0">
                <div class="col-md-3">
                    <client-only>
                    <nav-training :sorts="sorts" :classes="classes" :categories="categories" @onFilter="filterData"></nav-training>
                    </client-only>
                </div>

                <div class="col-md-9">
                    <div class="panel-subhead py-4 d-flex justify-content-between">
                        <div class="d-flex">
                            <div class="icon-subhead">
                                <img src="/img/icon/ic_event.png">
                            </div>
                            <div class="title-subhead">
                                <label>Event</label>
                            </div>
                        </div>
                    </div>
                    <div class="row d-flex-wrap p-0">
                        <panel-training v-for="(event, idx) in events" :key="idx" :data="event"></panel-training>
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
                    <p><u><NuxtLink to="/training/trainer">View All</NuxtLink></u></p>
                  </div>

                  <div class="row p-0">
                    <panel-trainer :data="trainer" v-for="(trainer, idx) in trainers" :key="idx"></panel-trainer>
                  </div>

                <div class="panel-subhead py-4 d-flex justify-content-between">
                    <div class="d-flex">
                      <div class="icon-subhead">
                          <img src="/img/icon/ic_provider.png">
                      </div>
                      <div class="title-subhead">
                          <label>Provider</label>
                      </div>
                    </div>
                    <p><u><NuxtLink to="/training/provider">View All</NuxtLink></u></p>
                  </div>

                  <div class="row p-0">
                    <panel-provider :data="provider" v-for="(provider, idx) in providers" :key="idx"></panel-provider>
                  </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    export default {
        head: {
            title: 'Event',
        },
        layout: "default-training",
        data() {
            return {
                categories: [],
                events: [],
                trainers: [],
                providers: [],
                sorts: ['Popular', 'Newest', 'Highest Price', 'Lowest Price'],
                classes: ['All', 'Public', 'In House'],
                loading: false,
                stopInfinite: false,
                params: {
                    page: 1,
                    perpage: 9
                },
            }
        },
        mounted(){
            window.addEventListener("scroll", this.handleScroll)
        },
        unmounted() {
            window.removeEventListener("scroll", this.handleScroll)
        },
        async fetch(){
            await this.$getCategories().then(data=>{
                this.categories = data.data
            })
            await this.$getAllEvent(this.params).then(response=>{
                this.events = response.data.data
            })
            await this.$getAllTrainer({perpage: 4}).then(response=>{
                this.trainers = response.data.data
            })
            await this.$getAllProvider({perpage: 4}).then(response=>{
                this.providers = response.data.data
            })
            this.params.page += 1;
        },
        methods: {
            async filterData(params){
                this.params.page = 1
                this.params = {...this.params,...params}
                await this.$getAllEvent(this.params).then(response=>{
                    this.events = response.data.data
                })
            },
            async handleScroll() {
                if (!this.stopInfinite && !this.loading && this.$refs.events && Math.floor(this.$refs.events.getBoundingClientRect().bottom) <= window.innerHeight) {
                    this.loading = true
                    const { data } =  await this.$getAllEvent(this.params)
                    this.events = [...this.events,...data.data]
                    this.params.page += 1;
                    this.loading = false
                    this.stopInfinite = this.events.length >= data.total
                }
            },
        }
    }
</script>