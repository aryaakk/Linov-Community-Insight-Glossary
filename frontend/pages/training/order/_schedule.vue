<template>
    <div class="pt-100">
        <div class="container p-5">
            <div class="d-flex justify-content-center p-4" style="background: #F0F0F0;">
                <div class="cursor">
                    <span class="badge-rounded badge-active">1</span> &nbsp;Detail Class
                </div>
                <div class="seperate-foot" style="margin-top: 10px;margin-right:10px;margin-left:10px;"></div>
                <div class="cursor">
                    <span class="badge-rounded">2</span>&nbsp;Waiting Order
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
                        <p class="pt-2">{{ $currency(schClass ? schClass.price : 0) }}/pax</p>
                    </div>
                    <div class="col-sm-4 pl-5 pt-4 form-group " :class="errors?.quantity ? 'has-error' : ''">
                        <strong>QUANTITY</strong>
                        <div class="input-group pt-2" style="width: 110px;">
                            <span class="input-group-addon p-2" @click="form.quantity--" style="background:  #E0F3FF;color:#2E9EE9"><i class="fas fa-minus"></i></span>
                            <input v-model="form.quantity" type="number" class="form-control" value="0" style="height: 26px;">
                            <span class="input-group-addon p-2" @click="form.quantity++" style="background:  #E0F3FF;color:#2E9EE9"><i class="fas fa-plus"></i></span>
                        </div>
                        <span class="help-block" v-if="errors?.quantity">{{ errors?.quantity}}</span>
                    </div>
                    <div class="col-sm-4 pl-5 pt-4 form-group">
                        <strong>TOTAL</strong>
                        <p class="pt-2">{{ $currency(countTotal) }}/pax</p>
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

                <h4 class="mt-5"><b>No. Rekening Anda</b></h4>
                <hr>
                <div class="row">
                    <div class="form-group col-md-12" :class="errors?.account_name ? 'has-error' : ''">
                        <label for="">Nama Pemilik Rekening</label>
                        <input type="text" v-model="form.account_name" class="form-control" placeholder="Enter your name">
                        <span class="help-block" v-if="errors?.account_name">{{ errors?.account_name[0]}}</span>
                    </div>
                    <div class="form-group col-md-6" :class="errors?.account_bank ? 'has-error' : ''">
                        <label for="">Nomor Rekening</label>
                        <input type="text" v-model="form.account_bank" class="form-control" placeholder="Enter your number">
                        <span class="help-block" v-if="errors?.account_bank">{{ errors?.account_bank[0]}}</span>
                    </div>
                    <div class="form-group col-md-6" :class="errors?.bank_id ? 'has-error' : ''">
                        <label for="">Pilih Bank</label>
                        <client-only>
                        <multiselect name="bank_id" selectLabel=""  deselectLabel="" openDirection="bottom" v-model="form.bank_id" track-by="id" label="bank_name" :options="banks" :close-on-select="false" :clear-on-select="false">
                            <template slot="singleLabel" slot-scope="props">
                                <img :src="props.option.icon" style="width: 20px;">
                                {{ props.option.bank_name }}
                            </template>
                            <template slot="option" slot-scope="props">
                                <img :src="props.option.icon" style="width: 20px;">
                                {{ props.option.bank_name }}
                            </template>
                        </multiselect>
                        </client-only>
                        <span class="help-block" v-if="errors?.bank_id">{{ errors?.bank_id[0]}}</span>
                    </div>
                </div>

                <h4 class="mt-5"><b>Confirmation</b></h4>
                <hr>
                <div class="checkbox my-2" :class="errors?.is_confirm_agree ? 'has-error' : ''">
                <label><input type="checkbox" v-model="form.is_confirm_agree" value="1">&nbsp;I agree with LinovHR tearms & conditions</label>
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
                    bank_id: null,
                    class_id : null,
                    account_name: null,
                    account_bank: null,
                    quantity: 0,
                    is_confirm_agree: false,
                    participant:{
                        name: null,
                        email: null,
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
            submit(){
                this.errors = [];
                this.form.processing = true;
                this.form.class_id   = this.schClass?.id
                this.form.schedule_id= this.$route.params.schedule
                this.form.bank_id    = this.form.bank_id?.id;
                this.$postOrder(this.form).then(response => {
                    this.$router.push('/training/order/detail/'+response.data.id);
                }).catch(error => {
                    this.errors = error.response.data.errors;
                    this.form.processing = false;
                    this.$toast.error('An error occured!');
                });
            }
        }
    }
</script>