<template>
    <div class="container">
        <certification :idx="idxCert" :data="form.certificates" 
        @getCertificate="addCertificate"></certification>
         <div class="row pt-50 mt-4">
            <div class="col-md-4">
                <div class="panel-subhead mt-4">
                    <div class="icon-subhead">
                        <img src="/img/icon/ic_user.png">
                    </div>
                    <div class="title-subhead">
                        <label>Settings</label>
                    </div>
                </div>
                 <div class="card p-0 pt-1 mt-4">
                    <nav-setting></nav-setting>
                 </div>
            </div>
            <div class="col-md-8">
                <div class="panel-subhead mt-4">
                    <div class="icon-subhead">
                        <img src="/img/icon/ic_lists.png">
                    </div>
                    <div class="title-subhead">
                        <label>Edit Profile</label>
                    </div>
                </div>

                <div class="card mt-4 p-4 pt-2">
                    <div class="card-header">
                        <nav-profile></nav-profile>
                    </div>
                    <div class="px-5 py-2 text-right border-bottom-thin">
                        <button data-toggle="modal" data-target="#certificates" class="btn btn-sm btn-primary px-3"><i class="fas fa-plus"></i></button>
                    </div>

                    <form @submit.prevent="submit" class="px-5 row">
                        <ul class="list-group">
                        <li class="list-group-item d-flex justify-content-between" v-for="(cert, idx) in form.certificates" :key="idx">
                            <div>
                                <h5 style="font-weight: 600;">{{ cert.title }} - {{ cert.organization }}</h5>
                                <span>{{  cert.link  }}</span><br>
                                <small>{{ $dayjs(cert.start_date).format('MMM-YYYY') }}&nbsp;-&nbsp;{{ $dayjs(cert.end_date).format('MMM-YYYY') }}</small>
                            </div>
                            <div>
                                <span class="btn btn-sm px-2 btn-default" @click="idxCert=idx" data-toggle="modal" data-target="#certificates"><i class='fas fa-edit'></i></span>
                                <span class="btn btn-sm px-2 btn-danger"  @click="form.certificates.splice(idx,1)"><i class='fas fa-trash'></i></span>
                            </div>
                        </li>
                    </ul>
                        <div class="form-group col-md-12 text-right mt-5">
                            <NuxtLink class="btn btn-secondary mr-1" to="/profile">Cancel</NuxtLink>
                            <button class="btn btn-primary" :disabled="form.processing">Save</button>
                        </div>
                    </form>
                </div>
            </div>
         </div>
    </div>
</template>
<script>
import navProfile from '../../components/nav-profile.vue';
import Certification from '../../components/modals/certification.vue';
export default {
  components: { navProfile, Certification },
  head: {
    title: "Edit Experience",
  },
  layout: "default",
  middleware: ['authenticated', 'hasprofile'],
  data() {
        return {
            idxCert: null,
            form: {
                certificates: [],
            },
        }
  },
  async mounted(){
    await this.$getProfile().then((response)=>{
        this.form = {...this.form,...response.data};
    });
  },
  methods: {
    async submit(){
        this.form.processing = true;
        this.errors = [];
        const form = new FormData()

        for(const key in this.form.certificates){
            for(const idx in this.form.certificates[key]){
               let data= this.form.certificates[key][idx];
               form.append(`certificates[${key}][${idx}]`,data);
            }
        }

        if(this.form.certificates.length<=0){
            form.append(`certificates`,null);
        }

        await this.$updateProfile(form).then(response=>{
            this.form.processing = false;
            this.$toast.success('Profile updated successfully');
            this.$router.push('/profile');
        }).catch(error=>{
            this.form.processing = false;
            this.errors = error.response?.data?.errors;
            this.$toast.error('Failed to update profile. Please follow the instructions');
        })
    },
    addCertificate(data){
        if(this.idxCert!=null){
            this.form.certificates[this.idxCert] = data
            this.idxCert = null
            return
        }
        this.form.certificates.push(data)
    }
  }
}
</script>