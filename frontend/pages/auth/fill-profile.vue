<template>
<div class="auth-form">
  <div id="wrapper" class="menuAccDetails">
                <div class="auth-box ">
                    <div class="left2">
                        <img src="/img/login/Banner.png" class="logo-img">
                    </div>
    
                    <div class="right2">
                        <div class="content card">
                            <div class="form-group text-left" style="margin-bottom: 30px;">
                                
                                <label class="lblhead">
                                    <img src="/img/login/accdetail.svg" style="margin-right: 15px;">
                                    Account Details</label>
                            </div>
                        
                            <form class="form-auth-small was-validated" @submit.prevent="submit">
                                <div class="form-group" :class="form.errors?.phone ? 'has-error' : ''">
                                    <label for="txtPhoneNumber" class="labellinov"  >Phone Number</label>
                                    <vue-tel-input mode="international" v-model="form.phone"  v-on:country-changed="countryChanged"></vue-tel-input>
                                    <span class="help-block" v-if="form.errors?.phone">{{ form.errors?.phone[0] }}</span>
                                </div>

                                <div class="form-group" :class="form.errors?.company_name ? 'has-error' : ''">
                                    <label for="txtCompany" class="labellinov">Company</label>
                                    <input type="text" v-model="form.company_name" class="form-control"  name="txtCompany" placeholder="enter your company">
                                    <span class="help-block" v-if="form.errors?.company_name">{{ form.errors?.company_name[0] }}</span>
                                </div>
                                
                                <div class="form-group" :class="form.errors?.industry_id ? 'has-error' : ''">
                                    <label for="txtIndustry" class="labellinov" >Industry</label>
                                    <client-only>
                                      <multiselect  selectLabel=""  deselectLabel="" name="txtIndustry" openDirection="bottom" 
                                      v-model="form.industry_id" 
                                      track-by="id" label="name" 
                                      :options="industries"  
                                      :clear-on-select="false">
                                      </multiselect>
                                    </client-only>
                                    <span class="help-block" v-if="form.errors?.industry_id">{{ form.errors?.industry_id[0] }}</span>
                                </div>
                                
                                <div class="form-group" :class="form.errors?.position_id ? 'has-error' : ''">
                                    <label for="txtPosition" class="labellinov">Position</label>
                                    <client-only>
                                    <multiselect  selectLabel=""  deselectLabel="" name="position" openDirection="bottom" 
                                    v-model="form.position_id" 
                                    placeholder="Search position"
                                    track-by="id" label="name" 
                                    :options="positions"  
                                    :clear-on-select="false"
                                    tag-placeholder="Add this position"
                                    :taggable="true"
                                    @tag="(tag)=>{ this.positions.push({name: tag});form.position_id={name: tag}}">
                                    </multiselect>
                                    </client-only>
                                    <span class="help-block" v-if="form.errors?.position_id">{{ form.errors?.position_id[0] }}</span>
                                </div>
                                <button type="submit" :disabled="form.processing" class="btn btn-primary btn-lg btn-block btn-next" style="font-weight: 600;">NEXT</button>
                                
                            </form>
                            <div class="lineLog2" style="margin-top:20%;">
                                <span class="WX6IsA2 badge bag">1</span>
                                <span class="WX6IsA2">Sign Up</span>
                                <div class="seperate-foot"></div>
                                <span class="WX6IsA2 badge">2</span>
                                <span class="WX6IsA2 spancolor">Account Details</span>
                                <div class="seperate-foot"></div>
                                <span class="WX6IsA2 badge bag">3</span>
                                <span class="WX6IsA2">Verification</span>
                            </div>
                        </div>
                    </div>
                    <div class="clearfix"></div>
                </div>
            </div>
        </div>
</template>

<script>
export default {
  head: {
    title: "Complate Profile",
  },
  layout: "default",
  middleware: ["authenticated", "profile"],

  data() {
    return {
      form: {
        country: null,
        phone: null,
        company_name: null,
        industry_id: '',
        position_id: '',
        errors: [],
        processing: false,
      },
      industries: [],
      positions: []
    };
  },
  async mounted(){
    await this.$getPositions().then(data=>{
      this.positions = data.data;
    });
    await this.$getIndustries().then(data=>{
      this.industries = data.data;
    });
  },
  methods: {
    async submit() {
      this.form.processing = true;
      this.form.errors = [];
      const redirectUrl = localStorage.getItem('redirectUrl')
      try {
        const form = Object.assign({}, this.form)
        form.industry_id = this.form.industry_id.id
        form.position_id = this.form.position_id?.id || '0'
        form.other_position= this.form.position_id?.id ? this.form.position_id.name : ''
        const {data} = await this.$complateProfile(form);
        this.form.processing = false;
        // window.location.reload()
        window.location.href =redirectUrl || '/'
        localStorage.removeItem('redirectUrl')
      } catch (e) {
        this.form.errors = e.response?.data?.errors;
        this.form.processing = false;
        this.$toast.error('An error occured!');
      }
    },
    async countryChanged(country) {
      this.form.country = country
    },
  },
};
</script>
