<template>
    <div class="pt-100">
        <div class="container p-5">
            <div class="d-flex justify-content-center p-4" style="background: #F0F0F0;">
                <div class="cursor">
                    <span class="badge-rounded badge-active">1</span> &nbsp;Detail Class
                </div>
                <div class="seperate-foot" style="margin-top: 10px;margin-right:10px;margin-left:10px;"></div>
                <div class="cursor">
                    <span class="badge-rounded">2</span>&nbsp;Done
                </div>
            </div>
            <div class="card" style="padding: 10%;padding-top: 5%;">
                <form @submit.prevent="submit()">
                <span class="text-light"><b>CLASS DETAIL</b></span>
                <h3 class="text-light mt-2"><b>{{ schedule.title }}</b></h3>

                <h4 class="mt-5"><b>Detail Order</b></h4>
                <hr>
                <div class="row p-0">
                    <div class="col-sm-4 d-flex">
                        <img src="/img/icon/ic_stack.png" class="px-3" style="max-height: 30px;">
                        <div>
                            <strong>CATEGORY</strong>
                            <h5 class="text-light" style="margin-top: 5px;">
                            <b>Planning & Development</b>
                            </h5>
                        </div>
                    </div>
                    <div class="col-sm-4 d-flex">
                        <img src="/img/icon/ic_level.png" class="px-3" style="max-height: 30px;">
                        <div>
                            <strong>LEVEL</strong>
                            <h5 class="text-light" style="margin-top: 5px;text-transform:capitalize;;"><b>{{ schedule.level }}</b></h5>
                        </div>
                    </div>
                    <div class="col-sm-4 d-flex">
                        <img src="/img/icon/ic_calendar.png" class="px-3" style="max-height: 30px;">
                        <div>
                            <strong>DURATION</strong>
                            <h5 style="color: #353535;margin-top: 5px;"><b>{{ schedule.day }} Days</b></h5>
                        </div>
                    </div>
                    <div class="col-sm-4 pl-5 pt-4 form-group">
                        <strong>PRICE</strong>
                        <p class="pt-2">Free</p>
                    </div>
                    <div class="col-sm-4 pl-5 pt-4 form-group " :class="errors?.quantity ? 'has-error' : ''">
                        <strong>QUANTITY</strong>
                        <div class="input-group pt-2" style="width: 110px;">
                            <span class="input-group-addon p-2" style="background:  #E0F3FF;color:#2E9EE9"><i class="fas fa-minus"></i></span>
                            <input v-model="form.quantity" type="number" class="form-control" value="1" style="height: 26px;" readonly>
                            <span class="input-group-addon p-2" style="background:  rgba(188, 188, 188, 0.4);color:#2E9EE9"><i class="fas fa-plus"></i></span>
                        </div>
                        <span class="help-block" v-if="errors?.quantity">{{ errors?.quantity}}</span>
                    </div>
                    <div class="col-sm-4 pl-5 pt-4 form-group">
                        <strong>TOTAL</strong>
                        <p class="pt-2">{{ $currency(countTotal) }}</p>
                    </div>
                </div>
                <h4 class="mt-5"><b>Participants Details</b></h4>
                <hr>
                <h5>Participants 1</h5>
                <div class="row">
                    <div class="form-group col-md-12" :class="errors?.['participant.name'] ? 'has-error': ''">
                        <label for="">Name</label>
                        <input v-model="form.participant.name" type="text" class="form-control" placeholder="Enter your name">
                        <span class="help-block" v-if="errors?.['participant.name']">{{ errors?.['participant.name'][0]}}</span>
                    </div>
                    <div class="form-group col-md-6" :class="errors?.['participant.email'] ? 'has-error' : ''">
                        <label for="">Email</label>
                        <input v-model="form.participant.email" type="email" class="form-control" placeholder="Enter your email">
                        <span class="help-block" v-if="errors?.['participant.email']">{{ errors?.['participant.email'][0]}}</span>
                    </div>
                    <div class="form-group col-md-6" :class="errors?.['participant.phone'] ? 'has-error' : ''">
                        <label for="">No Telpon (Whatsapp)</label>
                        <client-only>
                        <vue-tel-input v-model="form.participant.phone" mode="international"></vue-tel-input>
                        </client-only>
                        <span class="help-block" v-if="errors?.['participant.phone']">{{ errors?.['participant.phone'][0]}}</span>
                    </div>
                </div>
                <div class="form-group" :class="errors?.['position'] ? 'has-error' : ''">
                    <label for="">Position</label>
                    <input v-model="form.position" type="text" class="form-control" placeholder="Enter your position">
                    <span class="help-block" v-if="errors?.position">{{ errors?.position[0]}}</span>
                </div>
                <div class="form-group" :class="errors?.['known_from'] ? 'has-error' : ''">
                    <label for="">Mendapatkan Info Dari Mana?</label>
                    <select v-model="form.known_from" class="form-control">
                        <option>Facebook</option>
                        <option>Instagram</option>
                        <option>Linkedin</option>
                        <option>Website</option>
                    </select>
                    <span class="help-block" v-if="errors?.known_from">{{ errors?.known_from[0]}}</span>
                </div>
                <div class="form-group" :class="errors?.['tf_upload'] ? 'has-error' : ''">
                    <label for="">Bukti Follow IG</label>
                    <input type="file" class="form-control" @change="prepareUpload">
                    <span class="help-block" v-if="errors?.tf_upload">{{ errors?.tf_upload[0]}}</span>
                </div>
                <h4 class="mt-5"><b>Confirmation</b></h4>
                <hr>
                <div class="checkbox my-2" :class="errors?.is_confirm_agree ? 'has-error' : ''">
                <label><input type="checkbox" v-model="form.is_confirm_agree" value="1" required>&nbsp;I agree with LinovHR tearms & conditions</label>
                <span class="help-block" v-if="errors?.is_confirm_agree">{{ errors?.is_confirm_agree[0]}}</span>
                </div>
                <div>
                    <button class="btn btn-primary btn-block py-4" :disabled="form.processing">Order Now</button>
                </div>
            </form>
            </div>
        </div>
    </div>
</template>

<script>
    export default {
        head: {
            title: 'Order Training & Event',
        },
        layout: "default-training",
        middleware: ['authenticated','completed'],
        data() {
            return {
                banks: [],
                schedule: {},
                errors: [],
                form: {
                    class_id : null,
                    quantity: 1,
                    position: null,
                    known_from: null,
                    is_confirm_agree: false,
                    participant:{
                        name: this.$auth.user.name,
                        email: this.$auth.user.email,
                        phone: null,
                    },
                    processing: false,
                }
            }
        },
        computed: {
            schClass() {
                return this.schedule?.class_public ?  this.schedule.class_public : this.schedule?.class_inhouse;
            },
            countTotal() {
                return this.form.quantity * this.schClass?.price;
            }
        },
        async fetch(){
            const id = this.$route.params.schedule;

            await this.$getAllBank().then(response => {
                this.banks = response.data;
            });

            await this.$getOneSchedule({id: id}).then(response => {
                this.schedule = response.data;
            });
        },
        methods: {
            prepareUpload(e) {
                this.form.tf_upload = e.target.files[0];
            },
            submit(){
                this.errors = [];
                this.form.processing = true;
                this.form.class_id   = this.schClass?.id
                this.form.schedule_id= this.$route.params.schedule
                this.form.bank_id    = this.form.bank_id?.id;

                const form = new FormData()
                for(const key in this.form){
                    if(key=='participant' || key=='is_confirm_agree') continue
                    form.append(key, this.form[key]);
                }
                for(const key in this.form.participant){
                    form.append(`participant[${key}]`, this.form.participant[key]);
                }
                form.append('is_confirm_agree', this.form.is_confirm_agree?'1':'0')

                this.$postFreeOrder(form).then(response => {
                    this.$router.push('/training/order/event/success');
                }).catch(error => {
                    this.errors = error.response.data.errors;
                    this.form.processing = false;
                    this.$toast.error('Failed to submit! Please follow the instructions');
                });
            }
        }
    }
</script>