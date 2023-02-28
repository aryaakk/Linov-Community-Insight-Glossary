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
                                    <img src="/img/login/edit_pass.svg" style="margin-right: 15px;">
                                    Forgot Password</label>
                            </div>

                            <div class="page-verification editPass">
                                <img src="/img/login/pageForgotPassDone.png" class="size-img">
                            </div>
                            <div class="form-group text-center notifePass" style="margin-right: 10px;">
                                <h2>Berhasil</h2>
                            </div>
                            <div style="text-align: center; margin-top: 10px; margin-bottom: 40px;">
                                <p>
                                    Password has been reset. <br>Please check your email!
                                </p>
                            </div>
                            <button type="submit" class="btn btn-primary btn-lg btn-block logverifi" style="font-weight: 600;" id="btnLogin">CEK EMAIL</button>
                            
                            
                        </div>
                    </div>
                    <div class="clearfix"></div>
                </div>
            </div>
</template>

<script>

export default {
  head: {
    title: "Reset password sent",
  },

  layout: "guest",
};
</script>