<template>
    <div class="container">
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

                <div class="card mt-4 p-4 pt-0">
                    <div class="card-header">
                        <nav-profile></nav-profile>
                    </div>

                    <form @submit.prevent="submit" class="px-5 mt-4 row">
                        <div class="form-group col-md-12">
                            <div class="d-flex justify-content-between px-4 py-4" v-for="(social, idx) in socials" :key="idx">
                                <span><img :src="social.icon" width="20"> <strong>{{ social.name}}</strong></span>
                                <span>
                                    <span @click="social.show_input=!social.show_input" class="cursor:pointer" v-if="!social.show_input&&form.socials[social.id].url">
                                    {{ form.socials[social.id].url }}
                                    </span>
                                    <span class="opt-color" v-show="!social.show_input" style="cursor: pointer;" v-else> <i class="fas fa-link" @click="social.show_input=!social.show_input"></i></span>
                                    <div v-if="social.show_input" class="input-group" style="width: 300px">
                                        <input type="url" v-model="form.socials[social.id].url" class="form-control" placeholder="Ex. fb.me/name">
                                        <span class="input-group-addon btn-default" @click="social.show_input=!social.show_input">
                                            <i class="fas fa-save"></i>
                                        </span>
                                    </div>
                                </span>
                            </div>
                        </div>
                        <div class="form-group col-md-12 text-right mt-5">
                            <NuxtLink class="btn btn-secondary mr-1" to="/profile">Cancel</NuxtLink>
                            <button class="btn btn-primary" :disabled="form.processing">Save Profile</button>
                        </div>
                    </form>
                </div>
            </div>
         </div>
    </div>
</template>
<script>
import navProfile from '../../components/nav-profile.vue';
export default {
  components: { navProfile },
  head: {
    title: "Edit Social Media",
  },
  layout: "default",
  middleware: ['authenticated', 'hasprofile'],
  data() {
        return {
            socials: [],
            form: {
                socials: [],
            },
        }
  },
  async mounted(){
    await this.$getProfile().then((response)=>{
        this.form = {...this.form,...response.data};
    });
    await this.$getSocials(this.form).then((response)=>{
        this.socials = response.data;
        this.form.socials = {}
        this.socials.forEach(social => {
            this.form.socials[social.id] = {social_media_id: social.id, url: social.data?.url};
        });
    });
  },
  methods: {
    async submit(){
        this.form.processing = true;
        this.errors = [];
        const except= [ 'socials', 'processing', 'errors'];
        const form = new FormData()
        for(const key in this.form.socials){
            form.append(`socials[${key}][social_media_id]`, key);
            form.append(`socials[${key}][url]`, this.form.socials[key].url);
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
  }
}
</script>