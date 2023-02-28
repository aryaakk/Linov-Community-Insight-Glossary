<template>
    <div>
        <div class="wrapper-etc" style="padding-top: 10px;">
            <div class="category-banner" :class="category.banner_class">
                <img :src="`${category.image_src}`">
                <div class="container">
                    <h1>{{ category.title }}</h1>
                    <p>
                    {{ category.description }}
                    </p>
                </div>
            </div>
        </div>
        <div class="main-home">
            <div class="container">
                <div class="row" ref="threads">
                  <div class="col-md-9 col-sm-12 mt-4">
                    <div class="panel-subhead mt-4 mb-4">
                        <div class="icon-subhead">
                            <img src="/img/icon/ic_thread.png">
                        </div>
                        <div class="title-subhead">
                            <label>Thread & Discuss</label>
                        </div>
                    </div>
                    <div class="panel-subhead justify-content-right">
                        <div class="dropdown">
                            <button type="button" class="btn btn-secondary btn-corner dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">Sort By
                            <i class="fa fa-chevron-down"></i>
                            </button>
                            <ul class="dropdown-menu postion-filter" id="optiov-thread" >
                                <li @click="changeSort('lastest')"><span class="opt-color" v-if="params.sort=='lastest'"><i class="fas fa-check"></i></span>Last Update</li>
                                <li @click="changeSort('created')"><span class="opt-color" v-if="params.sort=='created'"><i class="fas fa-check"></i></span>Created Date</li>
                                <li @click="changeSort('atoz')"><span class="opt-color" v-if="params.sort=='atoz'"><i class="fas fa-check"></i></span>A to Z</li>
                                <li @click="changeSort('ztoa')"><span class="opt-color" v-if="params.sort=='ztoa'"><i class="fas fa-check"></i></span>Z to A</li>
                                <li @click="changeSort('mostlike')"><span class="opt-color" v-if="params.sort=='mostlike'"><i class="fas fa-check"></i></span>Most Likes</li>
                                <li @click="changeSort('mostview')"><span class="opt-color" v-if="params.sort=='mostview'"><i class="fas fa-check"></i></span>Most View</li>
                            </ul>
                        </div>
                    </div>

                    <button class="btn btn-primary w-100 mt-4" @click="$router.push('/threads/create')" style="height: 50px;">
                        <img src="/img/home/icon_create.png">
                        <b>Create A New Thread</b>
                    </button>

                    <div class="scrolling-pagination">
                        <panel-thread v-for="(thread, idx) in threads" :key="idx" :data="thread"></panel-thread>
                        <div v-if="$screen.width<=990 && !stopInfinite && !loading" class="text-center py-3">
                            <a href="#" @click.prevent="getThreads">Show More <i class="fas fa-chevron-down"></i></a>
                        </div>
                        <div v-show="loading"  class="d-flex justify-content-center mt-4 mb-4">
                            <img src="/img/loader.gif" style="height: 30px;margin-right: 5px;" alt="loading...">
                            <h5>Loading more awesome threads...</h5>
                        </div>
                    </div>
                </div>

                <div class="col-md-3 col-sm-12 mt-4">
                    <ads-trainevent></ads-trainevent>
                    <ads-article></ads-article>
                </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
export default {
  head() {
    return {
        title: this.category.title,
    }
  },
  layout: "default-thread",
  middleware: "completed",
  data() {
    return {
        type: this.$router.currentRoute.params.page,
        types: {
            "hr-administration" :{
                title: "HR Administration",
                description: "Gali semua hal dalam bidang HR Administration dengan detail dan menyeluruh!",
                banner_class: "bg-banner-purple",
                image_src: "/img/home/kategori/hrAdm.png",
            },
            "tax-legal":{
                title: "Tax & Legal",
                description: "Pahami hak serta kewajiban seputar tax and legal dengan baik!",
                banner_class: "bg-banner-orange",
                image_src: "/img/home/kategori/taxComp.png",
            },
            "comben":{
                title: "Comben",
                description: "Penggajian memang selalu menarik, cari tahu lebih dalam mengenai comben!",
                banner_class: "bg-banner-blue",
                image_src: "/img/home/kategori/combenComp.png",
            },
            "planning-recruitment":{
                title: "Planning & Recruitment",
                description: "Menemukan karyawan terbaik menjadi tantangan tersendiri, mari temukan cara terbaiknya di sini!",
                banner_class: "bg-banner-cyan",
                image_src: "/img/home/kategori/plreCom.png",
            },
            "perfomance-development":{
                title: "Perfomance & Development",
                description: "Ciptakan kualitas sumber daya yang unggul melalui perencanaan perfomance & development yang akurat dan tepat sasaran!",
                banner_class: "bg-banner-red",
                image_src: "/img/home/kategori/perfomComp.png",
            },
            "career-succession":{
                title: "Career & Succession",
                description: "Karyawan adalah aset perusahaan yang berharga. Tingkatkan pengelolaan career & succession demi perkembangan kinerja yang lebih baik!",
                banner_class: "bg-banner-yellow",
                image_src: "/img/home/kategori/careerComp.png",
            },
            "organization-development":{
                title: "Organization Development",
                description: "Tak hanya karyawan yang perlu pengembangan, kualitas organisasi pun juga membutuhkannya. Cari tahu lebih detail mengenai organization development bersama LinovHR!",
                banner_class: "bg-banner-green",
                image_src: "/img/home/kategori/odComp.png",
            },
            "hr-digital":{
                title: "HR Digital",
                description: "Ciptakan sistem kinerja HR yang lebih praktis dan efisien bersama HR digital dari LinovHR!",
                banner_class: "bg-banner-darkblue",
                image_src: "/img/home/kategori/digiComp.png",
            },
        },
        threads: [],
        loading: false,
        stopInfinite: false,
        params: {
            page: 1,
            sort: null,
            filter: [],
            search: null,
        }
    }
  },
  async fetch(){
    await this.getThreads();
  },
  async mounted(){
    if(this.$screen.width>990){
        window.addEventListener("scroll", this.handleScroll)
    }
  },
  unmounted() {
    if(this.$screen.width>990){
    window.removeEventListener("scroll", this.handleScroll)
    }
  },
  computed: {
    category() {
      return this.types[this.type]
    }
  },
  methods: {
    async getThreads(){
        this.loading = true
        const { data } =  await this.$getThreadsCategory({params: this.params}, this.type)
        this.threads = [...this.threads,...data.data]
        this.params.page += 1;
        this.loading = false
        this.stopInfinite = this.threads.length >= data.total
    },
    changeSort(sort){
        this.params.sort = sort
        this.params.page = 1
        this.threads = []
        this.getThreads()
    },
    changeFilter(code){
        const idx = this.params.filter.findIndex(item=>item == code)
        if(idx == -1){
            this.params.filter.push(code)
        }else{
            this.params.filter.splice(idx,1)
        }
        this.params.page = 1
        this.threads = []
        this.getThreads()
    },
    handleScroll() {
      if (!this.stopInfinite && !this.loading && this.$refs.threads && Math.floor(this.$refs.threads.getBoundingClientRect().bottom) <= window.innerHeight) {
        this.getThreads();
      }
    },
  }
}
</script>