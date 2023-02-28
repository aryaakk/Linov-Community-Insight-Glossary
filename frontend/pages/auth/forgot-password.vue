<template>
<div class="auth-form">
          <div class="auth-box ">
              <div class="left2">
                  <img src="/img/login/Banner.png" class="logo-img">
              </div>

              <div class="right2">
                  <div class="content forgot card">
                      <div class="form-group text-left" style="margin-bottom: 30px;">
                          
                          <label class="lblhead">
                              <img src="/img/login/edit_pass.svg" style="margin-right: 15px;">
                              Forgot Password</label>
                      </div>

                      <div class="page-verification editPass" style="padding-top: 10px; margin-top:35px; padding-left:70px;">
                          <img src="/img/login/pageForgotPass.png" class="size-img4">
                      </div>
                      
                      <div style="text-align: center; margin-top: 10px; margin-bottom: 20px;">
                          <p>
                            Enter your phone number and registered email. We'll send you insturctions to reset your password.
                          </p>
                      </div>

                      <form class="form-auth-small was-validated" @submit.prevent="submit">
                          <div class="form-group" :class="form.errors?.phone ? 'has-error' : ''">
                              <label for="txtPhoneNumberForgot" class="labellinov"  >
                                  Phone Number
                              </label>
                              <vue-tel-input v-model="form.phone"></vue-tel-input>
                              <span class="help-block" v-if="form.errors?.phone">{{ form.errors?.phone[0] }}</span>
                          </div>

                          <div class="form-group" id="divEmail" :class="form.errors?.email ? 'has-error' : ''">
                              <label for="txtEmailForgot" class="labellinov">Email</label>
                              <input type="email" class="form-control" name="txtEmailForgot"  v-model="form.email" placeholder="enter your email">
                              <span class="help-block" v-if="form.errors?.email">{{ form.errors?.email[0] }}</span>
                          </div>
                          <button :disabled="form.processing" type="submit" class="btn btn-primary btn-lg btn-block logverifi" style="font-weight: 600;" id="btnSend">SEND TO EMAIL</button>
                      </form>
                  </div>
              </div>
              <div class="clearfix"></div>
          </div>
      </div>
</template>

<script>
export default {
  head: {
    title: "Forgot Password",
  },

  layout: "guest",

  data() {
    return {
      form: {
        phone: "",
        email: "",
        errors: [],
        processing: false,
      },
    };
  },

  methods: {
    async submit() {
      this.form.processing = true;
      this.form.errors = [];

      try {
        await this.$getCsrfCookie();
        const { data } = await this.$forgotPassword(this.form);
        this.form.processing = false;
        if(data.status=='passwords.sent'){
            this.$router.push("/auth/reset-sent");
        }
      } catch (e) {
        this.form.processing = false;
        this.form.errors = e.response.data.errors
      }
    },
  },
};
</script>