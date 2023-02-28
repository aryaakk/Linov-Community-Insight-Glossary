<template>
    <div class=" auth-form">
        <div class="auth-box">
            <div class="left2">
                <img src="/img/login/banner2.png" class="logo-img">
            </div>

            <div class="right2">
                <div class="content card">
                    <div class="form-group text-left" style="margin-bottom: 30px;">
                        <label class="lblhead">
                        <img src="/img/login/login.svg" style="margin-right: 15px;">
                            Login Account </label>
                    </div>
                    <form class="form-auth-small" enctype="multipart/form-data" @submit.prevent="submit">
                        <div class="form-group bottom-error-email" :class="form.errors?.email ? 'has-error' : ''">
                            <label for="txtEmail" class="labellinov">Email</label>
                            <span class="has-errorForm">*</span>2
                            <input type="email" v-model="form.email" class="form-control" name="txtEmail" placeholder="enter your email">
                            <span class="help-block" v-if="form.errors?.email">{{ form.errors?.email[0] }}</span>
                        </div>
                        
                        <div class="form-group inputEnd gap-form2" style="position: relative;" :class="form.errors?.password ? 'has-error' : ''">
                            <label for="txtpassword" class="labellinov">Password</label>
                            <span class="has-errorForm">*</span>
                            <div class="input-group">
                            <input :type="showPassword ? 'text' :'password'" v-model="form.password" class="form-control" name="txtPassword"  placeholder="enter your password">
                            <span class="input-group-addon" @click="showPassword=!showPassword"><i class="fa " :class="showPassword ? 'fa-eye' : 'fa-eye-slash'"></i></span>
                            </div>
                            <span class="help-block" v-if="form.errors?.password">{{ form.errors?.password[0] }}</span>
                        </div>                              
                        <div class="form-group clearfix">
                            <label class="fancy-checkbox element-left">
                                <input type="checkbox" v-model="form.remember">
                                <span>Remember me</span>
                            </label>
                            
                            <label class="fancy-checkbox element-right">
                                <span class="helper-text">
                                  <NuxtLink to="/auth/forgot-password" style="cursor: pointer;">Forgot Password</NuxtLink>
                                </span>
                            </label>
                        </div>
                        <button :disabled="form.processing" type="submit" class="btn btn-primary btn-lg btn-block" style="margin-bottom: 18px;">SIGN IN</button>
                        
                        <div class="lineLog">
                            <hr class="dxuhGw jj2gEQ">
                            <span class="WX6IsA">OR</span>
                            <hr class="dxuhGw jj2gEQ">
                        </div>

                        <div class="bottom">
                            <span class="helper-text">No Account Yet?&nbsp;
                            <NuxtLink to="/auth/signup">Sign Up</NuxtLink>
                            </span>
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
    title: "Login",
  },
  layout: "guest",
  data() {
    return {
      form: {
        email: "",
        password: "",
        remember: false,
        processing: false,
        errors: [],
      },
      showPassword: false,
    };
  },
  methods: {
    async submit() {
      this.form.processing = true;
      this.form.errors = [];

      try {
        await this.$auth.loginWith("laravelSanctum", { data: this.form });
        this.$router.push(this.$store.state.redirectUrl ? this.$store.state.redirectUrl : '/')
        this.form.processing = false;
      } catch (e) {
        this.form.errors = e.response?.data?.errors
        this.form.processing = false;
      }
    },
  },
};
</script>