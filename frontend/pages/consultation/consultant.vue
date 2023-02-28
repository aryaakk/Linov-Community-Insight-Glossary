<template>
        <div class="pt-100">
        <div class="container" ref="consultant">
            <div class="row py-4 mr-1 ml-1 heading-training" style="background-image: url('/img/training/bg-training.png');">
                <div class="col-md-4 text-center py-4">
                    <img src="/img/icon/ic_group_white.svg">
                </div>
                <div class="col-md-8">
                <h3>Konsultan</h3>
                <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Nibh tellus ullamcorper et risus faucibus eu. Urna consectetur gravida justo faucibus ipsum at nascetur mi. Eget dignissim ullamcorper</p>
                </div>
            </div>
            <div class="row mt-5 p-0">
                <div class="col-md-3">
                    <client-only>
                    <nav-consultant :sorts="sorts" :categories="categories" @onFilter="filterData"></nav-consultant>
                    </client-only>
                </div>
                <div class="col-md-9">
                    <div class="panel-subhead py-4 d-flex justify-content-between">
                        <div class="d-flex">
                            <div class="icon-subhead">
                                <img src="/img/icon/ic_group.png">
                            </div>
                            <div class="title-subhead">
                                <label>Konsultan</label>
                            </div>
                        </div>
                    </div>
                    <div class="row d-flex-wrap p-0">
                        <div class="col-md-3 col-xs-6 px-2 py-2" v-for="(consultant, idx) in consultants">
                            <NuxtLink :to="`/profile/${consultant.id}`">
                            <div class="card h-100 px-2 py-1">
                            <img class="card-trainer-img" :src="consultant?.profile?.photo || '/img/home/user.png'">
                            <div class="text-center">
                                <h4 class="m-0 pt-1">{{ consultant.name }}</h4>
                                <p>{{ consultant.category?.name }}</p>
                            </div>
                            </div>
                            </NuxtLink>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>
<script>
    export default {
        head: {
            title: 'Consultant',
        },
        layout: "consultant",
        data() {
            return {
                categories: [],
                sorts: ['Newest', 'A to Z', 'Z to A'],
                consultants: [],
                params: {
                    page: 1,
                    perpage: 8
                },
            }
        },
        async fetch(){
            await this.$getConsultantCategory().then(data=>{
                this.categories = data.data
            })
            await this.getConsultants();
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
                this.consultants = []
                this.getConsultants()
            },
            async getConsultants(){
                this.loading = true
                const { data } =  await this.$getConsultants({params: this.params})
                this.consultants = [...this.consultants,...data.data]
                this.params.page += 1;
                this.loading = false
                this.stopInfinite = this.consultants.length >= data.total
            },
            async handleScroll() {
                if (!this.stopInfinite && !this.loading && this.$refs.consultant && Math.floor(this.$refs.consultant.getBoundingClientRect().bottom) <= window.innerHeight) {
                    this.loading = true
                    const { data } =  await this.$getAllTrainer(this.params)
                    this.consultants = [...this.consultants,...data.data]
                    this.params.page += 1;
                    this.loading = false
                    this.stopInfinite = this.consultants.length >= data.total
                }
            },
        }
    }
</script>