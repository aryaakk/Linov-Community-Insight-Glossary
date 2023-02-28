<template>
  <div class="col-md-4 col-xs-12 col-sm-6 px-2 py-1">
    <div class="card h-100 p-2 pb-5" style="position: relative">
      <NuxtLink :to="`/training/${data.type}/${data.slug_id}`" v-if="data">
        <img class="card-training-img" :src="data.banner_card_url" />
        <div class="p-4">
          <div
            class="d-flex justify-content-between"
            style="color:  #626262; !important;"
          >
            <div>
              <img class="card-provider-img" :src="data.provider.logo_url" />
              {{ data.provider ? data.provider.name : "" }}
            </div>
            <div>
              <span class="event-type py-2 px-1">
                <img
                  :src="
                    data.type == 'training'
                      ? '/img/icon/ic_medal.png'
                      : '/img/icon/ic_calendar.svg'
                  "
                />
                {{ data.type }} </span
              >&nbsp;
              <span class="event-type py-2 px-1">
                {{ data.type_class == "1" ? "Public" : "In House" }}
              </span>
            </div>
          </div>
          <h4>
            <strong>{{ data.title }}</strong>
          </h4>
          <table class="table-training">
            <tbody>
              <tr>
                <td class="pt-3">
                  <small
                    ><i class="fas fa-map-marker-alt"></i>
                    {{ data.location }}</small
                  >
                </td>
                <td class="pt-3">
                  <small
                    ><i class="fas fa-clock"></i>
                    {{ data.start_hour.slice(0, -3) }} -
                    {{ data.end_hour.slice(0, -3) }}</small
                  >
                </td>
              </tr>
              <tr>
                <td class="pt-3">
                  <small
                    ><i class="fas fa-calendar"></i>
                    {{ $dayjs(data.date).format("dddd, DD MMM YY") }}</small
                  >
                </td>
                <td class="pt-3">
                  <small
                    ><i class="fas fa-ticket-alt"></i>
                    {{
                      parseInt(data.price) > 0 ? $currency(data.price) : "Free"
                    }}</small
                  >
                </td>
              </tr>
            </tbody>
          </table>
        </div>
      </NuxtLink>
      <div class="py-2" style="position: absolute; left: 5px; bottom: 5px">
        <span
          class="p-2"
          style="border-radius: 10px"
          :style="{
            color: data.category.color + ' !important',
            background: data.category.color_bg,
          }"
          >{{ data.category.name }}</span
        >
      </div>
    </div>
  </div>
</template>
<style scoped>
.table-training {
  width: 100%;
  color: #626262 !important;
}
.title-training {
  font-size: 16px;
}
</style>
<script>
export default {
  name: "panel-training",
  props: ["data"],
  data() {
    return {
      //
    };
  },
  methods: {},
};
</script>
