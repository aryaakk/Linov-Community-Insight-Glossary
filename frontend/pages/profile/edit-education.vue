<template>
    <div class="container">
        <educations :idx="idxEdu" :data="form.educations" 
        @getEducation="getEducation"></educations>
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
                        <button data-toggle="modal" data-target="#educations" class="btn btn-sm btn-primary px-3"><i class="fas fa-plus"></i></button>
                    </div>

                    <form @submit.prevent="submit" class="px-5 row">
                        <ul class="list-group">
                            <li class="list-group-item d-flex justify-content-between" v-for="(edu, idx) in form.educations" :key="idx">
                            <div>
                                <h5 style="font-weight: 600;">{{ edu.university_id?.name || edu.university_name || edu.other_university }}</h5>
                                <span>{{  edu.major_id?.name || edu.major_name || edu.other_major }} - {{  edu.title_degree_id?.degree || edu.degree_name || edu.other_title }}</span><br>
                                <small>{{ $dayjs(edu.start_date).format('MMM-YYYY') }}&nbsp;-&nbsp;{{ $dayjs(edu.end_date).format('MMM-YYYY') }}</small>
                            </div>
                            <div>
                                <a href="#" class="btn btn-sm px-2 btn-default" @click="idxEdu=idx" data-toggle="modal" data-target="#educations"><i class='fas fa-edit'></i></a>
                                <span class="btn btn-sm px-2 btn-danger" @click="form.educations.splice(idx,1)"><i class='fas fa-trash'></i></span>
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
import Educations from '../../components/modals/educations.vue';
export default {
  components: { navProfile, Educations },
  head: {
    title: "Edit Education",
  },
  layout: "default",
  middleware: ['authenticated', 'hasprofile'],
  data() {
        return {
            idxEdu: null,
            form: {
                educations: [],
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
        for(const key in this.form.educations){
            for(const idx in this.form.educations[key]){
               let data= this.form.educations[key][idx];
               if(Object.prototype.toString.call(data) === '[object Object]'){
                data = data?.id
               }
               form.append(`educations[${key}][${idx}]`,data);
            }
        }
        if(this.form.educations.length<=0){
            form.append(`educations`,null);
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
    getEducation(data){
        if(this.idxEdu!=null){
            this.form.educations[this.idxEdu] = data
            this.idxEdu = null
            return
        }
        this.form.educations.push(data)
    }
  }
}
</script>