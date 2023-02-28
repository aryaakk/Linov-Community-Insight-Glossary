<template>
      <div class="auth-form">
                <div class="auth-box ">
                    <div class="left2">
                        <img src="/img/login/Banner.png" class="logo-img">
                    </div>
    
                    <div class="right2">
                        <div class="content card">
                            <div class="form-group text-left" style="margin-bottom: 30px;">
                                
                                <label class="lblhead">
                                    <img src="/img/login/forgot_pass.svg" style="margin-right: 15px;">
                                    Edit Password</label>
                            </div>

                            <div class="page-verification editPass">
                                <img src="/img/login/EditPassSuccess.png" class="size-img">
                            </div>
                            <div class="form-group text-center notifePass">
                                <h2>Berhasil</h2>
                            </div>
                            <div style="text-align: center; margin-top: 10px; margin-bottom: 40px;">
                                <p>
                                    Password already reset. Please login again!
                                </p>
                            </div>
                            <NuxtLink to="/auth/login" class="btn btn-primary btn-lg btn-block logverifi" style="font-weight: 600;">BACK TO LOGIN</NuxtLink>
                        </div>
                    </div>
                    <div class="clearfix"></div>
                </div>
            </div>
</template>

<script>

export default {
  head: {
    title: "Reset password success",
  },

  layout: "guest",
};
</script>