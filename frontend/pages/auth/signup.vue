<template>
    <div  class="auth-form">
                <div class="auth-box">
                    <div class="left2">
                        <img src="/img/login/Banner.png" class="logo-img">
                    </div>
    
                    <div class="right2">
                        <div class="content card" style="padding-top: 27px;">
                            <div class="form-group text-left" style="margin-bottom: 15px;">
                                <label class="lblhead">
                                <img src="/img/login/signup.svg" style="margin-right: 15px;">
                                    Sign Up</label>
                            </div>
                            <form class="form-auth-small" id="frmSignUP" enctype="multipart/form-data" @submit.prevent="submit">
                                <div class="form-group bottom-error-name" :class="form.errors?.name ? 'has-error' : ''">
                                    <label for="txtName" class="labellinov" >
                                        Full Name 
                                        <span class="has-errorForm">*</span>
                                    </label>
                                    <input type="text" v-model="form.name" name="txtName" class="form-control" placeholder="enter your name">
                                    <span class="help-block" v-if="form.errors?.name">{{ form.errors?.name[0] }}</span>
                                </div>
                                <div class="form-group bottom-error-email" :class="form.errors?.email ? 'has-error' : ''">
                                    <label for="txtEmail" class="labellinov" >Email</label>
                                    <span class="has-errorForm">*</span>
                                    <input type="email" v-model="form.email" class="form-control" name="txtEmail" placeholder="enter your email">
                                    <span class="help-block" v-if="form.errors?.email">{{ form.errors?.email[0] }}</span>
                                </div>
                                <div class="form-group inputEnd gap-form2" :class="form.errors?.password ? 'has-error' : ''" style="position: relative;">
                                   <label for="txtpassword" class="labellinov" >Password <span class="has-errorForm">*</span></label>
                                    <div class="input-group">
                                      <input :type="showPassword ? 'text' :'password'" v-model="form.password" class="form-control" name="txtPassword"  placeholder="enter your password">
                                      <span class="input-group-addon" @click="showPassword=!showPassword"><a><i class="fa " :class="showPassword ? 'fa-eye' : 'fa-eye-slash'"></i></a></span>
                                    </div>
                                    <span class="help-block" v-if="form.errors?.password">{{ form.errors?.password[0] }}</span>
                                </div>  
                                <div class="bottom">
                                    <span class="helper-text">Have an account?&nbsp;
                                    <NuxtLink to="/auth/login">Sign In</NuxtLink>
                                    </span>
                                </div>                          
                                <button :disabled="form.processing" type="submit" class="btn btn-primary btn-lg btn-block" style="margin-bottom: 10px;">SIGN UP</button>
                                
                                <div class="lineLog">
                                    <hr class="dxuhGw jj2gEQ">
                                    <span class="WX6IsA">OR</span>
                                    <hr class="dxuhGw jj2gEQ">
                                </div>
                                <a :href="`${$config.baseURL}/login/google`" type="button" class="btn btn-social w-100 mb-3">
                                    <img src="/img/login/icnGoogle.png" class="social-icon">
                                    <span class="social-label">Sign In with Google</span>
                                </a>
                                
                                <a :href="`${$config.baseURL}/login/facebook`" type="button" class="btn btn-social w-100 mb-3">
                                    <img src="/img/login/icnFb.png" class="social-icon">
                                    <span class="social-label">Sign In with Facebook</span>
                                </a>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
</template>
<script>
export default {
  head: {
    title: "Register",
  },

  layout: "guest",

  data() {
    return {
      form: {
        name: "",
        email: "",
        password: "",
        terms: true,
        processing: false,
        errors: [],
      },
      showPassword: false,
    };
  },
  watch:{
    'form.password' : function (password){
        let passUpper = false
        let passLower = false
        let passNumber = false
        
        this.form.errors.password = [];
        if(password.length < 8){
          this.form.errors.password.unshift('The password at least 8 characters');
        }
        if(password.match(/[A-Z]/g)){
          passUpper = true
        }
        if(password.match(/[a-z]/g)){
          passLower = true
        }
        if(password.match(/[0-9]/g)){
          passNumber = true
        }
        if(!passUpper){
          this.form.processing = true;
          this.form.errors.password.unshift('The password must at least 1 uppercase');
        }
        if(!passLower){
          this.form.processing = true;
          this.form.errors.password.unshift('The password must at least 1 lowercase');
        }
        if(!passNumber){
          this.form.processing = true;
          this.form.errors.password.unshift('The password must at least 1 number');
        }
        if(passUpper && passLower && passNumber){
          this.form.processing = false;
          this.form.errors.password = undefined;
        }
      }
  },
  methods: {
    async submit() {
      this.form.processing = true;
      this.form.errors = [];

      try {
        await this.$registerUser(this.form);
        await this.$auth.loginWith("laravelSanctum", { data: this.form });

        this.form.processing = false;
      } catch (e) {
        this.form.processing = false;
        this.form.errors = e.response.data.errors;
      }
    },
  },
};
</script>
