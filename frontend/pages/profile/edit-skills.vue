<template>
    <div class="container">
        <skills :idx="idxExp" :data="form.skills" 
        @getSkills="addSkill"></skills>
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
                        <button data-toggle="modal" data-target="#skills" class="btn btn-sm btn-primary px-3"><i class="fas fa-plus"></i></button>
                    </div>

                    <form @submit.prevent="submit" class="px-5 row">
                        <div class="p-4">
                            <span class="px-1" v-for="(skill, idx) in form.skills">
                                <span class="badge bg-secondary" style="font-size: 15px;">
                                {{ skill }} <span class="px-1"></span>
                                </span>
                            </span>
                        </div>
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
import Skills from '../../components/modals/skills.vue';
export default {
  components: { navProfile, Skills },
  head: {
    title: "Edit Skills",
  },
  layout: "default",
  middleware: ['authenticated', 'hasprofile'],
  data() {
        return {
            idxExp: null,
            form: {
                skills: [],
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

        for(const key in this.form.skills){
            let data= this.form.skills[key];
            form.append(`skills[${key}]`,data);
        }
        if(this.form.skills.length<=0){
            form.append(`skills`,null);
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
    addSkill(data){
        if(this.idxExp!=null){
            this.form.skills[this.idxExp] = data
            this.idxExp = null
            return
        }
        this.form.skills=data
    }
  }
}
</script>