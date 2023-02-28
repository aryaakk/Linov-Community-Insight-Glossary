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
                                <img src="/img/login/verified.png" class="size-img3">
                            </div>
                            <div style="text-align: center; margin-top: 10px;">
                                <p>
                                    Your account has been verified. <span v-if="!$auth.loggedIn">Please try to login again!</span>
                                </p>
                                <nuxt-link v-if="!$auth.loggedIn" class="btn btn-primary btn-block mt-4" to="/auth/login">Login</nuxt-link>
                                <nuxt-link v-else class="btn btn-primary btn-block mt-4" :to="this.$store.state.redirectUrl ? this.$store.state.redirectUrl : '/'">Back to Home</nuxt-link>
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
    title: "Email Verified",
  },
  layout: "default",
}
</script>