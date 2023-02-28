<template>
  <div>
      <div class="auth-form">
                <div class="auth-box ">
                    <div class="left2">
                        <img src="/img/login/Banner.png" class="logo-img">
                    </div>
    
                    <div class="right2">
                        <div class="content forgot card">
                            <div class="form-group text-left" style="margin-bottom: 30px;">
                                
                                <label class="lblhead">
                                    <img src="/img/login/forgot_pass.svg" style="margin-right: 15px;">
                                    Edit Password</label>
                            </div>

                            <form class="form-auth-small" @submit.prevent="submit">                        
                                <div class="form-group inputEnd gap-form" style="position: relative;"  :class="form.errors?.password ? 'has-error' : ''">
                                    <label for="txtPassword" class="labellinov">New Password</label>
                                    <div class="input-group">
                                    <input v-model="form.password" :type="showPassword ? 'text' :'password'" class="form-control" name="txtpassword" placeholder="enter your password">
                                    <span class="input-group-addon" @click="showPassword=!showPassword"><a><i class="fa " :class="showPassword ? 'fa-eye' : 'fa-eye-slash'"></i></a></span>
                                    </div>
                                    <span class="help-block" v-if="form.errors?.password">{{ form.errors?.password[0] }}</span>
                                </div>
                                <div class="form-group inputEnd confirm" style="position: relative;"  :class="form.errors?.password_confirmation ? 'has-error' : ''">
                                    <label for="txtConfirm" class="labellinov">Confirm Password</label>
                                    <div class="input-group">
                                    <input v-model="form.password_confirmation" :type="showConfirm ? 'text' :'password'" class="form-control" name="txtConfirm" placeholder="enter your password">
                                    <span class="input-group-addon"  @click="showConfirm=!showConfirm"><a><i class="fa "  :class="showConfirm ? 'fa-eye' : 'fa-eye-slash'"></i></a></span>
                                    </div>
                                    <span class="help-block" v-if="form.errors?.password_confirmation">{{ form.errors?.password_confirmation }}</span>
                                </div>
                                <button :disabled="form.processing" type="submit" class="btn btn-primary btn-lg btn-block logverifi" style="font-weight: 600;" id="btnChange">CHANGE PASSWORD</button>
                            </form>
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
    title: "Reset Password",
  },

  layout: "guest",

  data() {
    return {
      form: {
        token: this.$router.currentRoute.params.token,
        email: this.$route.query.email,
        password: "",
        password_confirmation: "",
        errors: [],
        processing: false,
      },
      showPassword: false,
      showConfirm: false,
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
      this.form.errors = [];
      this.form.processing = true;
      try {
        await this.$getCsrfCookie();

        const {data} = await this.$resetPassword(this.form);
        this.form.processing = false;

        if(data.status=='passwords.reset'){
          this.$router.push("/auth/reset-success");
        }
      } catch (e) {
        this.form.processing = false;
        this.form.errors = e.response.data.errors
        this.$toast.error('An error occured!');
      }
    },
  },
};
</script>