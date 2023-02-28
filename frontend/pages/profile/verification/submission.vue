<template>
    <div class="container">
         <div class="row pt-50 mt-4">
            <div class="col-md-4">
                <div class="panel-subhead mt-4">
                    <div class="icon-subhead">
                        <img src="/img/icon/ic_user.png">
                    </div>
                    <div class="title-subhead">
                        <label>Consultant Submission</label>
                    </div>
                </div>
                 <div class="card p-0 pt-1 mt-4">
                    <nav-setting></nav-setting>
                 </div>
            </div>
            <div class="col-md-8" style="padding-top: 50px;">
                <div class="card mt-4 p-4 pt-0">
                    <div class="form-group pt-4 px-5">
                        <h4 class="title-head"><strong>Verficiation HR</strong></h4>
                        <hr cl style="border: 1px solid #F0F0F0;">
                    </div>
                    <form @submit.prevent="submit" class="px-5 row">
                        <div class="form-group col-md-12">
                            <label>Nama</label>
                            <input v-model="profile.name" type="text" class="form-control" disabled>
                        </div>
                        <div class="form-group col-md-12">
                            <label>Email</label>
                            <input v-model="profile.email" type="email" class="form-control" disabled>
                        </div>
                        <div class="form-group col-md-12">
                            <label>Phone Number <span class="required">*</span></label>
                            <client-only>
                            <vue-tel-input disabled mode="international" v-model="profile.phone"></vue-tel-input>
                            </client-only>
                        </div>
                        <div class="form-group col-md-12" :class="errors?.category ? 'has-error' : ''">
                            <label>Spesialisasi / bidang yang diajukan <span class="required">*</span></label>
                            <client-only>
                            <multiselect  selectLabel=""  deselectLabel="" name="categories" openDirection="bottom" :multiple="true" v-model="form.category" track-by="id" label="name" :options="categories"  :clear-on-select="false">
                            </multiselect>
                            </client-only>
                            <span class="help-block" v-if="errors?.category">{{ errors?.category[0] }}</span>
                        </div>
                        <div class="form-group col-md-12" :class="errors?.reason ? 'has-error' : ''">
                            <label>Alasan Pengajuan <span class="required">*</span></label>
                            <textarea v-model="form.reason" class="form-control" placeholder="Tulis alasanmu"></textarea>
                            <span class="help-block" v-if="errors?.reason">{{ errors?.reason[0] }}</span>
                        </div>
                        <div class="row px-4">
                            <div class="form-group col-md-12">
                                <h4><b>Berkas</b></h4>
                                <hr style="border: 1px solid #F0F0F0;">
                            </div>
                            <div  class="form-group col-md-12"  :class="errors?.['upfiles.ktp'] ? 'has-error' : ''">
                                <label>Scan KTP</label>
                                <drop-upload label="KTP" @getFile="(file)=>{ upfiles.ktp=file }"></drop-upload>
                                <span class="help-block" v-if="errors?.['upfiles.ktp']">{{ errors?.['upfiles.ktp'][0] }}</span>
                            </div>
                            <div  class="form-group col-md-12" :class="errors?.['upfiles.ijazah'] ? 'has-error' : ''">
                                <label>Surat Keterangan/ Ijazah</label>
                                <drop-upload label="Ijazah" @getFile="(file)=>{ upfiles.ijazah=file }"></drop-upload>
                                <span class="help-block" v-if="errors?.['upfiles.ijazah']">{{ errors?.['upfiles.ijazah'][0] }}</span>
                            </div>
                            <div  class="form-group col-md-12" :class="errors?.['upfiles.foto'] ? 'has-error' : ''">
                                <label>Pass Foto</label>
                                <drop-upload label="Pass Foto" @getFile="(file)=>{ upfiles.foto=file }"></drop-upload>
                                <span class="help-block" v-if="errors?.['upfiles.foto']">{{ errors?.['upfiles.foto'][0] }}</span>
                            </div>
                            <div class="form-group col-md-12" :class="errors?.['upfiles.cv'] ? 'has-error' : ''">
                                <label>Curriculum Vitae (Optional)</label>
                                <drop-upload label="CV" @getFile="(file)=>{ upfiles.cv=file }"></drop-upload>
                                <span class="help-block" v-if="errors?.['upfiles.cv']">{{ errors?.['upfiles.cv'][0] }}</span>
                            </div>
                        </div>
                        <div class="form-group col-md-12 text-right mt-4">
                            <NuxtLink class="btn btn-secondary mr-1" to="/profile">Cancel</NuxtLink>
                            <button class="btn btn-primary" :disabled="form.processing">Save Data</button>
                        </div>
                    </form>
                </div>
            </div>
         </div>
    </div>
</template>
<style scoped>
.required{
    color: #E74357
}
</style>
<script>
    export default {
        layout: "default",
        middleware: ['authenticated', 'completed'],
        head: {
            title: "Verifikasi Data",
        },
        data() {
            return {
                countries: [],
                states: [],
                cities: [],
                categories: [],
                profile: {},
                errors: [],
                form: {
                    category: [],
                    reason: null,
                    processing: false,
                },
                upfiles: {
                    cv: null,
                    ktp: null,
                    ijazah: null,
                    foto: null,
                },
            }
        },
        async mounted(){
            await this.$getProfile().then((response)=>{
                this.profile = response.data;
                this.profile.phone = this.profile.phone || ''
            });
            await this.$getCountry(this.form).then((response)=>{
                this.countries = response.data;
            });
            await this.$getState(this.form).then((response)=>{
                this.states = response.data;
            });
            await this.$getCity(this.form).then((response)=>{
                this.cities = response.data;
            });
            await this.$getConsultantCategory().then((response)=>{
                this.categories = response.data;
            });
        },
          methods: {
            async submit(){
                this.form.processing = true;
                this.errors = [];
                const form = new FormData()
                for (const key in this.form) {
                    let data = this.form[key];
                    if(typeof this.form[key] == 'object'){
                        data = JSON.stringify(this.form[key]);
                    }
                    if(data) form.append(key, data);
                }
                for(const key in this.upfiles){
                    form.append(`upfiles[${key}]`, this.upfiles[key]);
                }
                try {
                    const { data, status} =  await this.$submitConsultant(form)
                    if(status==200){
                        this.$toast.success('Submit verification data successfull');
                        this.$router.push('/profile')
                    }
                } catch (e) {
                    this.errors = e.response.data.errors
                    this.form.processing = false;
                    this.$toast.error('An error occured!');
                }
            },
          }
    }
</script>