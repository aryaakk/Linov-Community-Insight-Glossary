<template>
<div v-if="consultants.length>0">
    <div class="panel-subhead  px-0 mt-4 mb-4">
        <div class="icon-subhead">
            <img src="/img/icon/ic_group.png">
        </div>
        <div class="title-subhead w-100 px-2 d-flex justify-content-between">
            <label>Rekomendasi Konsultan</label>
            <NuxtLink to="/consultation/consultant" v-if="total>4">See All</NuxtLink>
        </div>
    </div>      
    <div class="px-2 " :class="$screen.width<=990 ? 'scrollable': ''">
        <div class="pl-2 pb-3" v-for="(consultant, idx) in consultants" :key="idx">
          <div class="card p-2 d-flex">
            <div>
              <NuxtLink :to="`profile/${consultant.id}`">
              <img :src="consultant?.profile?.photo_url || '/img/home/user.png'" style="width: 80px;object-fit: cover;">
              </NuxtLink>
            </div>
            <div class="px-2">
                <NuxtLink :to="`profile/${consultant.id}`" style="color: #4E4E4E;">
                <h5><b>{{ consultant.name }}</b></h5>
                </NuxtLink>
                <p>{{ consultant.category?.name }}</p>
                <NuxtLink :to="`/consultation/create?consultant=${consultant.id}`" class="btn px-2 btn-sm btn-primary"><small>Konsultasi Sekarang!</small></NuxtLink>
            </div>
          </div>
        </div>
    </div>
</div>
</template>

<script>
export default {
  name: "ads-konsultant",
  data() {
    return {
      consultants : [],
      total: 0,
    }
  },
  async mounted(){
    await this.$getConsultants({params: {perpage: 3, recomended: true}}).then(response=>{
        this.consultants = response.data.data
        this.total = response.data.total
    })
  },
  methods: {

  },
}
</script>