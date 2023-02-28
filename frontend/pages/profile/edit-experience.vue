<template>
    <div class="container">
        <experiences :idx="idxExp" :data="form.experiences" 
        @getExperience="addExperience"></experiences>
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
                        <button data-toggle="modal" data-target="#experiences" class="btn btn-sm btn-primary px-3"><i class="fas fa-plus"></i></button>
                    </div>

                    <form @submit.prevent="submit" class="px-5 row">
                        <ul class="list-group">
                            <li class="list-group-item d-flex justify-content-between" v-for="(exp, idx) in form.experiences" :key="idx">
                                <div>
                                    <h4><b>{{ exp.company}}</b></h4>
                                    <span><b>{{ exp.position}}</b></span><br>
                                    <small>{{ $dayjs(exp.start_date).format('MMM-YYYY') }}&nbsp;-&nbsp;{{ $dayjs(exp.end_date).format('MMM-YYYY') }}</small>
                                </div>
                                <div class="py-5">
                                    <a href="#" @click="idxExp=idx" data-toggle="modal" data-target="#experiences" class="btn btn-sm px-2 btn-default"><i class='fas fa-edit'></i></a>
                                    <a href="#" @click="form.experiences.splice(idx, 1)" class="btn btn-sm px-2 btn-danger"><i class='fas fa-trash'></i></a>
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
import Experiences from '../../components/modals/experiences.vue';
export default {
  components: { navProfile, Experiences },
  head: {
    title: "Edit Experience",
  },
  layout: "default",
  middleware: ['authenticated', 'hasprofile'],
  data() {
        return {
            idxExp: null,
            form: {
                experiences: [],
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

        for(const key in this.form.experiences){
            for(const idx in this.form.experiences[key]){
               let data= this.form.experiences[key][idx];
               form.append(`experiences[${key}][${idx}]`,data);
            }
        }

        if(this.form.experiences.length<=0){
            form.append(`experiences`,null);
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
    addExperience(data){
        if(this.idxExp!=null){
            this.form.experiences[this.idxExp] = data
            this.idxExp = null
            return
        }
        this.form.experiences.push(data)
    }
  }
}
</script>