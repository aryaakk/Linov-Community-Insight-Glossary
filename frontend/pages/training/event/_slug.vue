<template>
    <div class="pt-100">
        <div class="container" v-if="event">
            <div class="row mt-5 p-0">
                <div class="col-md-8">
                  <div class="panel-subhead py-4">
                        <div class="icon-subhead">
                            <img src="/img/icon/ic_description.png">
                        </div>
                        <div class="title-subhead">
                            <label>Description</label>
                        </div>
                  </div>
                  <div class="card mt-4">
                    <div class="d-flex justify-content-between">
                        <div class="p-2">
                        <img class="card-provider-img" :src="event?.provider?.logo_url">{{ event?.provider?.name }}</div>
                        <div>
                            <span class="event-type p-2">
                            <img src="/img/icon/ic_medal.png"> {{ event.type }}
                            </span>
                            &nbsp;
                            <span class="event-type p-2">
                            {{ event.type_class=='1' ? 'Public' : 'In House' }}
                            </span>
                        </div>
                    </div>
                    <div class="mt-4">
                        <img :src="event.banner_url" class="w-100" style="max-height: 360px;object-fit: cover;">
                    </div>
                    <h3 class="mt-5 text-light px-3"><strong>{{ event.title }}</strong></h3>
                    <hr>
                    <div class="row p-0">
                        <div class="col-sm-6 pb-2 d-flex">
                            <img src="/img/icon/ic_stack.png" class="px-3 icon-event">
                            <div>
                                <label class="label-event">CATEGORY</label>
                                <h5 class="text-light" style="margin-top: 5px;">
                                <b>{{ event.category?.name }}</b>
                                </h5>
                            </div>
                        </div>
                        <div class="col-sm-6 pb-2 d-flex">
                            <img src="/img/icon/ic_calendar.png" class="px-3 icon-event">
                            <div>
                                <label class="label-event">SCHEDULE</label>
                                <h5 style="color: #353535;margin-top: 5px;">
                                    <strong>{{ $dayjs(event.date).format('dddd, DD MMMM YYYY') }} {{ event.start_hour.slice(0,-3)}} - {{ event.end_hour.slice(0,-3)}}</strong>
                                </h5>
                            </div>
                        </div>
                        <div class="col-sm-6 pb-2 d-flex">
                            <img src="/img/icon/ic_level.png" class="px-3 icon-event">
                            <div>
                                <label class="label-event">LEVEL</label>
                                <h5 class="text-light" style="margin-top: 5px;text-transform:capitalize;;"><b>{{ event.level }}</b></h5>
                            </div>
                        </div>
                        <div class="col-sm-6 pb-2 d-flex">
                            <img src="/img/icon/ic_level.png" class="px-3 icon-event">
                            <div>
                                <label class="label-event">LOCATION</label>
                                <h5 class="text-light" style="margin-top: 5px;text-transform:capitalize;;"><b>{{ event.location }}</b></h5>
                            </div>
                        </div>
                    </div>
                    <hr>
                    <div class="px-3">
                        <h4><b>Suitable For</b></h4>
                        <p class="justify-text">{{ event.sustainable }}</p>
                        <h4 class="pt-4"><b>Summary</b></h4>
                        <div class="justify-text" v-html="event.summary">
                        </div>
                    </div>
                    <div class="row px-3" v-if="event.trainers.length>0">
                        <h4 class="col-md-12"><b>Trainer / Speaker</b></h4>
                        <div class="col-md-6" v-for="(trainer, idx) in event.trainers" :key="idx">
                            <div class="d-flex m-1 p-1" style="border: 1px solid #E1F1FC;" >
                                <!-- <img :src="trainer.photo_url" style="min-width: 100px;object-fit: cover;"> -->
                                <div class="p-4">
                                    <h5 class="text-light">{{ trainer.name }}</h5>
                                    <p>{{ trainer.position}} {{ trainer.company_name }}</p>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="px-3" v-if="syllabus.length>0">
                        <h4><b>Syllabus</b></h4>
                        <div>
                            <table class="mt-3 w-100">
                                <tbody>
                                    <template v-for="(item, idx) in syllabus">
                                    <tr style="background: #EDF7FD;">
                                        <td colspan="2" align="center" class="p-4"> 
                                            <strong class="text-light">{{ item.title}}</strong> &nbsp;{{ item.sub_title }}
                                        </td>
                                    </tr>
                                    <tr :style="{background: idx%2==0 ? '#F0F0F0' : ''}" v-for="(detail, idx) in item.details" :key="idx">
                                        <td width="8%" align="center">
                                            <span class="px-4 py-3 text-light" style="background: #E6E6E6;border-radius: 4px;"><b>{{ detail.icon }}</b></span>
                                        </td>
                                        <td>
                                            <h5 class="text-light">
                                                <b>{{ detail.head }}</b>
                                            </h5>
                                            <p>{{ detail.description }}</p>
                                        </td>
                                    </tr>
                                    </template>
                                </tbody>
                            </table>
                        </div>
                    </div>
                  </div>
                </div>
                <div class="col-md-4">
                  <div class="panel-subhead py-4">
                        <div class="icon-subhead">
                            <img src="/img/icon/ic_order.png">
                        </div>
                        <div class="title-subhead">
                            <label>Order</label>
                        </div>
                  </div>
                  <div class="card p-4 mt-4">
                     <form @submit.prevent="submit">
                    <div class="form-group">
                        <label class="pb-3"><strong>Date</strong></label>
                        <client-only>
                        <multiselect name="user_consul_id" selectLabel=""  deselectLabel="" openDirection="bottom" v-model="form.schedule" track-by="id" label="name" :options="schedules">
                            <template slot="singleLabel" slot-scope="props">
                                {{ props.option.day }} Hari 
                                ({{ $dayjs(props.option.date).format('DD MMM YY')+' '+props.option.start_hour.slice(0, -3) }} - 
                                {{ $dayjs(props.option.end_date).format('DD MMM YY')+' '+props.option.end_hour.slice(0, -3) }})
                            </template>
                            <template slot="option" slot-scope="props">
                                {{ props.option.day }} Hari 
                                ({{ $dayjs(props.option.date).format('DD MMM YY')+' '+props.option.start_hour.slice(0, -3) }} - 
                                {{ $dayjs(props.option.end_date).format('DD MMM YY')+' '+props.option.end_hour.slice(0, -3) }})
                            </template>
                        </multiselect>
                        </client-only>
                    </div>
                    <div class="form-group">
                        <label class="pb-3"><strong>Price</strong></label>
                        <div class="d-flex">
                            <div class="py-4 pr-2"><img class="mt-1" src="/img/icon/ic_ticket.png"></div>
                            <h2 class="price-tag">{{ finalPrice>0 ? $currency(finalPrice) : 'Free' }}</h2>
                        </div>
                    </div>
                    <div class="form-group" v-tooltip.bottom="orderValid.message">
                        <button :disabled="!orderValid.status" class="btn btn-primary btn-block">Order Now</button>
                    </div>
                    </form>
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
             title: `${this.event?.title || 'Detail'}`,
           }
        },
        layout: "default-training",
        data() {
            return {
                event: null,
                syllabus: [],
                schlists: [],
                form: {
                    class_type: 1,
                    schedule: null,
                },
            }
        },
        computed: {
            schedules() {
                return this.schlists[this.form.class_type] || []
            },
            classes(){
                return this.event.classes.find(data => {return data.type_class==this.form.class_type})
            },
            finalPrice(){
                return (parseFloat(this.classes?.price)-parseFloat(this.classes?.price_discount)) || 0
            },
            orderValid(){
                const schedule = this.form.schedule
                if(!schedule){
                    return {status: false, message: "A schedule must be selected before continuing with registration."}
                }
                if(parseInt(schedule.max_participant)<parseInt(schedule.order_total)){
                    return {status: false, message: "This schedule has hit its participant limit. Please select another schedule for registration!"}
                }
                if(parseInt(schedule.max_order)<=parseInt(schedule.order_count)){
                    return {status: false, message: "Oops, it seems you've already registered and reached the ticket limit."}
                }
                return {status: true, message: null}
            }
        },
        async fetch(){
            await this.$getOneTrainingEvent({type:'event' , id: this.$route.params.slug}).then(response=>{
                 this.event = response.data;
                 this.form.class_type = this.event.type_class
            }).catch(error=>{
                return this.$nuxt.error({ statusCode: error.response.status, message:  error.response.statusText })
            })
            await this.$getAllSyllabus({id: this.event.id}).then(response =>{
                this.syllabus = response.data;
            });

            await this.$getSchedule({id: this.event.id}).then(response =>{
                this.schlists = response.data;
                this.form.schedule = this.schedules?.[0] || null
            });
        },
        methods: {
            submit() {
                if(this.classes?.price>0){
                    this.$router.push('/training/order/'+this.form.schedule.id);
                    return
                }
                this.$router.push('/training/order/event/'+this.form.schedule.id);
            },
        },
    }
</script>