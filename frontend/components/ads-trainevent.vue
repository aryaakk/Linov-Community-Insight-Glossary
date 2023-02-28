<template>
    <div v-if="trainEvents && trainEvents.length>0">
        <div class="panel-subhead pl-3 mt-4 mb-4">
            <div class="icon-subhead">
                <img src="/img/icon/ic_event.png">
            </div>
            <div class="title-subhead w-100 px-2 d-flex justify-content-between">
                <label>Training & Event</label>
                <NuxtLink to="/training">See All</NuxtLink>
            </div>
        </div>
        <div class="px-2" :class="$screen.width<=990 ? 'scrollable': ''">
            <div class="content" v-for="(trainEvent, idx) in trainEvents" :key="idx">
            <div class="card-side list-training mb-2" :class="$screen.width>990? 'w-100': ''">
                <NuxtLink :to="`/training/${trainEvent.type}/${trainEvent.slug_id}`" style="color:  #626262; !important;">
                <img class="card-training-img" style="height: 139px" :src="trainEvent.banner_card_url">
                <div class="py-3 d-flex justify-content-between">
                    <div>
                    <img class="card-provider-img" :src="trainEvent.provider.logo_url"> 
                    {{ trainEvent.provider?.name }}
                    </div>
                    <div>
                        <span class="event-type py-2 px-1">
                        <img :src="trainEvent.type=='training' ? '/img/icon/ic_medal.png' : '/img/icon/ic_calendar.svg'"> {{ trainEvent.type }}
                        </span>&nbsp;
                        <span class="event-type py-2 px-1">
                        {{ trainEvent.type_class=='1' ? 'Public' : 'In House' }}
                        </span>
                    </div>
                </div>
                <h4><strong>{{ trainEvent.title }}</strong></h4>
                <table class="table-training" style="width: 100%">
                    <tbody>
                    <tr>
                    <td class="pt-3"><small><i class="fas fa-map-marker-alt"></i> {{ trainEvent.location }}</small></td>
                    <td class="pt-3"><small><i class="fas fa-clock"></i> {{ trainEvent.start_hour.slice(0, -3) }} - {{ trainEvent.end_hour.slice(0, -3) }}</small></td>
                    </tr>
                    <tr>
                    <td class="pt-3"><small><i class="fas fa-calendar"></i> {{ $dayjs(trainEvent.date).format('dddd, DD MMM YY') }}</small></td>
                    <td class="pt-3"><small><i class="fas fa-ticket-alt"></i> {{ parseInt(trainEvent.price)>0 ?$currency(trainEvent.price) : 'Free' }}</small></td>
                    </tr>
                    </tbody>
                </table>
                <div class="detail-train">
                    <span class="p-2" style="background: #F8F6FF;border-radius: 10px;" :style="{color: trainEvent.category.color+' !important'}">{{ trainEvent.category.name }}</span>
                </div>
                </NuxtLink>
            </div>
            </div>
        </div>
    </div>
</template>

<script>
export default {
  name: "ads-trainevent",
  data() {
    return {
      trainEvents : []
    }
  },
  async mounted(){
    await this.$getAllTrainingEvent({type:'all', perpage: 3}).then(response=>{
        this.trainEvents = response.data.data
    })
  },
  methods: {

  },
}
</script>