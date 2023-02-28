<template>
    <div class="pt-100" v-if="provider">
       <div class="container">
        <div class="row mt-5 p-0">
            <div class="col-md-3">
                <client-only>
                    <nav-training :sorts="[]" :categories="[]"></nav-training>
                </client-only>
            </div>
            <div class="col-md-9">
                <div class="panel-subhead py-4 d-flex justify-content-between">
                    <div class="d-flex">
                        <div class="icon-subhead">
                            <img src="/img/icon/ic_user.png">
                        </div>
                        <div class="title-subhead">
                            <label>Detail Provider</label>
                        </div>
                    </div>
                </div>
                <div class="row card p-0">
                   <div class="position-relative w-100 pb-5" :style="{'background': `url(${provider.logo2_url})`}" style="height: 250px;background-size: cover;">
                    <img :src="provider.logo_url" class="position-absolute provider-logo" style="bottom: 6px;left: 36px;">
                   </div>
                   <div class="px-5">
                    <h4><strong>{{ provider.name }}</strong></h4>
                    <div class="d-flex justify-content-between">
                        <span> {{ provider.tagline }}</span>
                        <span>{{ provider.state.name}}, {{ provider.state.country_name}}</span>
                    </div>
                    <h5 class="mt-5"><strong>Summary</strong></h5>
                    <p>
                        {{ provider.summary}}
                    </p>
                   </div>
                   <div class="mt-5">
                      <ul class="nav nav-tabs" style="border-bottom: 1px solid  #F0F0F0;">
                        <li class="text-center col-xs-6 px-0" :class="tab=='trainer' ? 'tab-active' : ''" @click="tab='trainer'"><a href="#">Our Trainer</a></li>
                        <li class="text-center col-xs-6 px-0" :class="tab=='training' ? 'tab-active' : ''" @click="tab='training'"><a href="#">Upcoming Training</a></li>
                      </ul>
                   </div>
                    <div class="d-flex justify-content-right py-3 mr-3">
                        <div class="dropdown">
                                <button type="button" class="btn btn-secondary btn-corner dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                <small>Sort By <i class="fa fa-chevron-down"></i></small>                            
                                </button>
                                <ul class="dropdown-menu postion-filter p-3">
                                    <li class="py-2 cursor" v-for="(sort, idx) in sorts">{{ sort }}</li>
                                </ul>
                        </div>
                    </div>
                    <div class="row p-5" v-if="tab=='trainer'">
                        <panel-trainer :data="trainer" v-for="(trainer, idx) in trainers" :key="idx"></panel-trainer>
                    </div>
                    <div class="row p-5" v-else>
                        <panel-training v-for="(training, idx) in trainings" :key="idx" :data="training"></panel-training>
                    </div>
                </div>
            </div>
        </div>
       </div>
    </div>
</template>
<style scoped>
.tab-active{
    background: #FCFCFC;
    font-weight: bold;
    border-bottom: 2.5px solid #62B6EE;
}

.tab-active a{
    color: #7D7D7D !important;
}

.card .nav-tabs a{
    color: #CDCDCD;
    border: 0px solid transparent !important;
}

.card .nav-tabs a:hover{
    font-weight: bold;
    color: #7D7D7D !important;
}
</style>
<script>
    export default {
        head() {
            return {
                title: `Detail Provider - ${this.provider?.name}`,
            }
        },
        layout: "default-training",
        data() {
            return {
                tab: 'trainer',
                sorts: ['Name', 'Price'],
                provider: null,
                trainers: [],
                trainings: [],
            }
        },
        async fetch(){
            await this.$getOneProvider({id: this.$route.params.detail}).then(response => {
                this.provider = response.data
             }).catch(error=>{
                return this.$nuxt.error({ statusCode: error.response.status, message:  error.response.statusText })
            })
            await this.$getAllTrainer({provider_id: this.$route.params.detail, perpage: 100}).then(response => {
                this.trainers = response.data.data
            })
            await this.$getAllTraining({provider_id: this.$route.params.detail, perpage: 100}).then(response => {
                this.trainings = response.data.data
            })
        }
    }
</script>