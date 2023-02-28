<template>
  <div class="auth-form">
   <div id="wrapper" class="signup-form">
                <div class="auth-box ">
                    <div class="left2">
                        <img src="/img/login/Banner.png" class="logo-img">
                    </div>
    
                    <div class="right2">
                        <div class="content card">
                            <div class="form-group text-left" style="margin-bottom: 30px;">
                                
                                <label class="lblhead">
                                    <img src="/img/login/verification.svg" style="margin-right: 15px;">
                                    Verification</label>
                            </div>

                            <div class="page-verification" style="margin-top: 53px;">
                                <img src="/img/login/pageVerifi.png" class="size-img3">
                            </div>
                            <div style="text-align: center; margin-top: 10px;">
                                <p>
                                    Your account ready to use! <br>Please check your email {{ $auth.user.email }}
                                </p>
                            </div>
                            <button type="submit" :disabled="processing" v-if="!verificationLinkSent" class="btn btn-primary btn-lg btn-block" style="font-weight: 600;" @click="resendVerification()">KIRIM ULANG EMAIL</button>
                            
                            <div class="lineLog2" style="margin-top:25%;">
                                <span class="WX6IsA2 badge bag">1</span>
                                <span class="WX6IsA2">Sign Up</span>
                                <div class="seperate-foot"></div>
                                <span class="WX6IsA2 badge bag">2</span>
                                <span class="WX6IsA2">Account Details</span>
                                <div class="seperate-foot"></div>
                                <span class="WX6IsA2 badge">3</span>
                                <span class="WX6IsA2 spancolor">Verification</span>
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
    title: "Verify Email",
  },
  layout: "default",
  middleware: ["authenticated","verified"],

  data() {
    return {
      processing: false,
      form: {
        status: "",
      },
    };
  },

  mounted() {
     this.form.status !== "verification-link-sent" ? this.resendVerification() : this.$router.push("/");
  },

  computed: {
    verificationLinkSent() {
      return this.form.status === "verification-link-sent";
    },
  },

  methods: {
    async resendVerification() {
      this.processing = true;
      try {
        const { data } = await this.$verifyEmail();
        this.form.status = data.status;
        this.processing = false;
      } catch (error) {
        //this.form.status = "verification-link-sent";
        this.processing = false;
      } 
    },
  },
};
</script>