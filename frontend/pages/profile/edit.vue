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
                        <label>Edit Profiles</label>
                    </div>
                </div>

                <div class="card mt-4 p-4 pt-0">
                    <div class="card-header">
                        <nav-profile></nav-profile>
                    </div>

                    <div class="d-flex justify-content-center px-5 pt-5" :class="errors?.photo ? 'has-error' : ''" style="position:relative">
                         <img :src="thumbnails ? thumbnails : '/img/home/user.png'" class="img-circle" style="width: 100px;height: 100px;border: 1px solid #8E8E8E; object-fit: cover;">
                         <button class="btn-primary img-circle py-1" style="position: absolute;top:70%;right: 42%;"><i class="fas fa-pencil-alt" @click="doCrop=true"></i></button>
                         <avatar-cropper :upload-handler="preparePhotos" v-model="doCrop"/>
                         <span class="help-block" v-if="errors?.photo">{{ errors?.photo[0] }}</span>
                    </div>
                    <p class="text-center">Edit Photo</p>
                    <form @submit.prevent="submit" class="px-5 row">
                        <div class="form-group col-md-12" :class="errors?.name ? 'has-error' : ''">
                            <label>Full Name</label>
                            <input v-model="form.name" type="text" class="form-control">
                            <span class="help-block" v-if="errors?.name">{{ errors?.name[0] }}</span>
                        </div>
                        <div class="form-group col-md-12" :class="errors?.about_me ? 'has-error' : ''">
                            <label>About Me</label>
                            <textarea v-model="form.about_me" class="form-control" rows="5"></textarea>
                            <span class="help-block" v-if="errors?.about_me">{{ errors?.about_me[0] }}</span>
                        </div>
                        <div class="form-group col-md-6" :class="errors?.birthplace ? 'has-error' : ''">
                            <label>Place of Birth</label>
                            <input v-model="form.birthplace" type="text" class="form-control">
                            <span class="help-block" v-if="errors?.birthplace">{{ errors?.birthplace[0] }}</span>
                        </div>
                        <div class="form-group col-md-6" :class="errors?.birthdate ? 'has-error' : ''">
                            <label>Date of Birth</label>
                            <input v-model="form.birthdate"  type="date" class="form-control">
                            <span class="help-block" v-if="errors?.birthdate">{{ errors?.birthdate[0] }}</span>
                        </div>
                        <div class="form-group col-md-12">
                            <label>Email</label>
                            <input v-model="form.email" disabled type="email" class="form-control">
                        </div>
                        <div class="form-group col-md-12" :class="errors?.phone ? 'has-error' : ''">
                            <label>Phone Number</label>
                            <client-only>
                            <vue-tel-input v-model="form.phone" mode="international"></vue-tel-input>
                            </client-only>
                            <span class="help-block" v-if="errors?.phone">{{ errors?.phone[0] }}</span>
                        </div>
                        <div class="form-group col-md-6" :class="errors?.country_id ? 'has-error' : ''">
                            <label>Country</label>
                            <client-only>
                                <multiselect  selectLabel=""  deselectLabel="" name="country" openDirection="bottom" v-model="form.country_id" track-by="id" label="name" :options="countries"  :clear-on-select="false">
                                </multiselect>
                            </client-only>
                            <span class="help-block" v-if="errors?.country_id">{{ errors?.country_id[0] }}</span>
                        </div>
                        <div class="form-group col-md-6" :class="errors?.state_id ? 'has-error' : ''">
                            <label>Province</label>
                            <client-only>
                            <multiselect  selectLabel=""  deselectLabel="" name="state" openDirection="bottom" v-model="form.state_id" track-by="id" label="name" :options="states"  :clear-on-select="false">
                            </multiselect>
                            </client-only>
                            <span class="help-block" v-if="errors?.state_id">{{ errors?.state_id }}</span>
                        </div>
                        <div class="form-group col-md-6" :class="errors?.city_id ? 'has-error' : ''">
                            <label>City</label>
                            <client-only>
                            <multiselect  selectLabel=""  deselectLabel="" name="city" openDirection="bottom" v-model="form.city_id" track-by="id" label="name" :options="cities"  :clear-on-select="false">
                            </multiselect>
                            </client-only>
                            <span class="help-block" v-if="errors?.city_id">{{ errors?.city_id[0] }}</span>
                        </div>
                        <div class="form-group col-md-6" :class="errors?.postal_code ? 'has-error' : ''">
                            <label>Postal Code</label>
                            <input v-model="form.postal_code" type="text" class="form-control">
                            <span class="help-block" v-if="errors?.postal_code">{{ errors?.postal_code[0]}}</span>
                        </div>
                        <div class="col-md-12 pt-4">
                        <h4 class="title-head">Pekerjaan</h4>
                         <hr style="border: 1px solid #F0F0F0;">
                        </div>
                       
                        <div class="form-group col-md-12" :class="errors?.company_name ? 'has-error' : ''">
                            <label>Company</label>
                            <input v-model="form.company_name" type="text" class="form-control">
                            <span class="help-block" v-if="errors?.company_name">{{ errors?.company_name[0] }}</span>
                        </div>
                        <div class="form-group col-md-12" :class="errors?.industry_id ? 'has-error' : ''">
                            <label>Industry</label>
                            <client-only>
                            <multiselect  selectLabel=""  deselectLabel="" name="industry" openDirection="bottom" 
                            v-model="form.industry_id" track-by="id" label="name" :options="industries"  :clear-on-select="false">
                            </multiselect>
                            </client-only>
                            <span class="help-block" v-if="errors?.industry_id">{{ errors?.industry_id[0] }}</span>
                        </div>
                        <div class="form-group col-md-12" :class="errors?.position_id ? 'has-error' : ''">
                            <label>Position</label>
                            <client-only>
                            <multiselect  selectLabel=""  deselectLabel="" name="position" openDirection="bottom" 
                            placeholder="Search position"
                            v-model="form.position_id" 
                            track-by="id" label="name" 
                            :options="positions"  
                            :clear-on-select="false"
                            tag-placeholder="Add this position"
                            :taggable="true"
                            @tag="(tag)=>{ this.positions.push({name: tag});form.position_id={name: tag}}">
                            </multiselect>
                            </client-only>
                            <span class="help-block" v-if="errors?.position_id">{{ errors?.position_id[0] }}</span>
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
<style>
    .title-head{
        font-weight: 600;
        font-size: 18px;
    }
    .border-bottom-thin{
        border-bottom: 1px solid #F0F0F0;
    }
</style>
<script>
import navProfile from '../../components/nav-profile.vue';
export default {
  components: { navProfile },
  head: {
    title: "Edit Profile",
  },
  layout: "default",
  middleware: ['authenticated', 'hasprofile'],
  data() {
        return {
            doCrop: false,
            errors: [],
            countries: [],
            states: [],
            cities: [],
            industries: [],
            positions: [],
            thumbnails: this.$auth.user.avatar,
            form: {
                processing: false
            },
        }
  },
  async mounted(){
    await this.$getIndustries(this.form).then((response)=>{
        this.industries = response.data;
    });
    await this.$getPositions(this.form).then((response)=>{
        this.positions = response.data;
    });
    await this.$getProfile().then((response)=>{
        this.form = {...this.form,...response.data};
        if(!this.form.position_id && !this.form.other_position){
            this.positions.push({name: this.form.other_position});
            this.form.position_id={name: this.form.other_position}
        }
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
  },
   watch : {
    'form.country_id':function(val) {
        this.$getState(this.form).then((response)=>{
            this.states = response.data;
        });
    },
    'form.state_id':function(val) {
        this.$getCity(this.form).then((response)=>{
            this.cities = response.data;
            // this.form.city=null
        });
    },
  },
  methods: {
    async submit(){
        this.form.processing = true;
        this.errors = [];
        const except= ['id', 'socials', 'processing', 'errors', 'country_id', 'state_id', 'skills'];
        const form = new FormData()
        for (const key in this.form) {
            if(except.includes(key)) continue;
            let data = this.form[key];
            if(typeof this.form[key] == 'object' && key !='photo'){
                data = this.form[key]?.id;
            }
            if(key=='position_id' && !this.form[key]?.id){
                form.append('position_id', 0)
                form.append('other_position', this.form[key]?.name)
            }
            if(data) form.append(key, data);
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
    async preparePhotos(croper){
        const canvas = croper.getCroppedCanvas();
        canvas.toBlob((blob) => {
            this.form.photo = blob
        });

        this.thumbnails = canvas.toDataURL();
    },
  }
}
</script>