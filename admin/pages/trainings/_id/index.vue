<template>
    <div>
        <div class="card p-3">
            <div class="card-header">
                <h4>Detail Event</h4>
                <hr>
            </div>
            <div class="card-body detail-data">
                <table class="w-100">
                    <tr>
                        <td colspan="2" ><h6>Judul</h6></td>
                    </tr>
                    <tr>
                        <td class="pb-3">{{ event.title }}</td>
                    </tr>
                    <tr>
                        <td colspan="2" ><h6>Providers</h6></td>
                    </tr>
                    <tr>
                        <td class="pb-3">{{ event.provider?.name }}</td>
                    </tr>
                    <tr>
                        <td colspan="2" ><h6>Suitable for</h6></td>
                    </tr>
                    <tr>
                        <td class="pb-3">{{ event.sustainable }}</td>
                    </tr>
                    <tr>
                        <td colspan="2" ><h6>Summary</h6></td>
                    </tr>
                    <tr>
                        <td class="pb-3" v-html="event.summary"></td>
                    </tr>
                    <tr>
                        <td colspan="2" ><h6>Class</h6></td>
                    </tr>
                    <tr>
                        <td class="pb-3">{{  }}</td>
                    </tr>
                    <tr>
                        <td colspan="2" ><h6>Location</h6></td>
                    </tr>
                    <tr>
                        <td class="pb-3">{{ event.location }}</td>
                    </tr>
                    <tr>
                        <td colspan="2" ><h6>Level</h6></td>
                    </tr>
                    <tr>
                        <td class="pb-3">{{ event.level }}</td>
                    </tr>
                    <tr>
                        <td colspan="2" ><h6>Categories</h6></td>
                    </tr>
                    <tr>
                        <td class="pb-3">{{ event.category?.name }}</td>
                    </tr>
                    <tr>
                        <td colspan="2" ><h6>Training Status:</h6></td>
                    </tr>
                    <tr>
                        <td class="pb-3">{{ event.status }}</td>
                    </tr>
                    <tr>
                        <td colspan="2" ><h6>Order Status:</h6></td>
                    </tr>
                    <tr>
                        <td class="pb-3">{{ ''}}</td>
                    </tr>
                    <tr>
                        <td colspan="2" ><h6>Picture:</h6></td>
                    </tr>
                    <tr>
                        <td class="pb-3">
                            <a :href="event.banner_url" target="_blank">{{event.banner}}</a>
                            <a :href="event.banner_card_url" target="_blank">{{event.banner_card}}</a>
                        </td>
                    </tr>
                    <tr>
                        <td colspan="2" ><h6>Banner Ads:</h6></td>
                    </tr>
                    <tr>
                        <td class="pb-3"><a :href="event.banner_slide_url" target="_blank">{{ event.banner_slide }}</a></td>
                    </tr>
                    <tr>
                        <td ><h6>Minimum Order</h6></td>
                        <td ><h6>Maximum Order</h6></td>
                    </tr>
                    <tr>
                        <td class="pb-3">{{ event.classes?.[0]?.min_order }}</td>
                        <td class="pb-3">{{ event.classes?.[0]?.max_order }} </td>
                    </tr>
                    <tr>
                        <td ><h6>Minimum Participant</h6></td>
                        <td ><h6>Maximum Participant</h6></td>
                    </tr>
                    <tr>
                        <td class="pb-3">{{ event.classes?.[0]?.min_participant }}</td>
                        <td class="pb-3">{{ event.classes?.[0]?.max_participant }} </td>
                    </tr>
                    <tr>
                        <td colspan="2" ><h6>Kurs:</h6></td>
                    </tr>
                    <tr>
                        <td class="pb-3">{{ event.classes?.[0]?.kurs_dollar=='0' ? 'IDR' : 'USD' }}</td>
                    </tr>
                    <tr>
                        <td colspan="2" ><h6>Price:</h6></td>
                    </tr>
                    <tr>
                        <td class="pb-3">{{  event.classes?.[0]?.price }}</td>
                    </tr>
                    <tr>
                        <td colspan="2" ><h6>Discount:</h6></td>
                    </tr>
                    <tr>
                        <td class="pb-3">{{  event.classes?.[0]?.is_discount=='1' ? 'YES' : '' }}</td>
                    </tr>
                    <tr>
                        <td colspan="2" ><h6>Price Discount:</h6></td>
                    </tr>
                    <tr>
                        <td class="pb-3">{{  event.classes?.[0]?.price_discount }}</td>
                    </tr>
                </table>
                <table class="table">
                    <thead>
                        <tr>
                            <th>Start Date</th>
                            <th>End Date</th>
                            <th>Start Hour</th>
                            <th>End Hour</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr v-for="(sch, idx) in schedules">
                            <td>{{ sch.date }}</td>
                            <td>{{ sch.end_date }} </td>
                            <td>{{ sch.start_hour }}</td>
                            <td>{{ sch.end_hour }}</td>
                        </tr>
                    </tbody>
                </table>
                <div class="mt-4">
                    <div v-for="(syll, idx) in syllabus">
                        <h4>{{ syll.title }} <small>{{ syll.sub_title }}</small></h4>
                        <ul class="list-group py-3 px-2">
                        <li class="list-group-item" v-for="(sub, idx2) in syll.details">
                            <p class="mb-0"><strong>{{ sub.head }}</strong></p>
                            <p class="mb-0"><small>{{ sub.description }}</small></p>
                        </li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>
<style>
    .detail-data h6{
        border-bottom: 1px solid #E6E6E6;
        margin-right: 20px;
        padding-bottom: 6px;
    }
</style>
<script>
export default {
      head: {
        title: "Detail Training",
      },
      layout: "authenticated",
      data() {
        return {
            id: this.$router.currentRoute.params.id,
            syllabus: [],
            event: {},
            schedules: [],

        }
      },
      async mounted(){
        await this.$getOneTrainings(this.id).then(response=>{
            this.event = response.data
        })
        await this.$getSchedule(this.id).then(response=>{
            this.schedules = response.data
        })
        await this.$getAllSyllabus(this.id).then(response=>{
            this.syllabus = response.data
        })
      },
}
</script>