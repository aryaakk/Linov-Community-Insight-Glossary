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
                    <nav-setting type="setting"></nav-setting>
                 </div>
            </div>
            <div class="col-md-8">
                <div class="panel-subhead mt-4">
                    <div class="icon-subhead">
                        <img src="/img/icon/ic_lists.png">
                    </div>
                    <div class="title-subhead">
                        <label>Edit profiles</label>
                    </div>
                </div>

                <div class="card mt-4 p-4">
                    <div class="px-5">
                        <h4><b>  Change Password</b></h4>
                        <hr style="border: 1px solid #F0F0F0;">
                    </div>
                    <form @submit.prevent="submit" class="px-5 row">
                        <div class="form-group col-md-12">
                            <p>Are you sure want to change password? </p>
                        </div>
                        <div class="form-group col-md-12" :class="form.errors?.old_password ? 'has-error' : ''">
                            <label>Old Password</label>
                            <div class="input-group">
                                <input v-model="form.old_password" :type="showPassword? 'text' :'password'" class="form-control">
                                <span class="input-group-addon" @click="showPassword=!showPassword"><i class="fas" :class="showPassword ? 'fa-eye-slash' : 'fa-eye'"></i></span>
                            </div>
                            <span v-if="form.errors?.old_password" class="help-block">{{ form.errors?.old_password[0] }}</span>
                        </div>
                        <div class="form-group col-md-6" :class="form.errors?.new_password ? 'has-error' : ''">
                            <label>New Password</label>
                            <div class="input-group">
                                <input v-model="form.new_password" :type="showPassword2? 'text' :'password'" class="form-control">
                                <span class="input-group-addon" @click="showPassword2=!showPassword2"><i class="fas" :class="showPassword2 ? 'fa-eye-slash' : 'fa-eye'"></i></span>
                            </div>
                            <span v-if="form.errors?.new_password" class="help-block">{{ form.errors?.new_password[0] }}</span>
                        </div>
                        <div class="form-group col-md-6" :class="form.errors?.new_password_confirmation ? 'has-error' : ''">
                            <label>Confirm Password</label>
                            <div class="input-group">
                                <input v-model="form.new_password_confirmation" :type="showPassword3? 'text' :'password'" class="form-control">
                                <span class="input-group-addon" @click="showPassword3=!showPassword3"><i class="fas" :class="showPassword3 ? 'fa-eye-slash' : 'fa-eye'"></i></span>
                            </div>
                            <span v-if="form.errors?.new_password_confirmation" class="help-block">{{ form.errors?.new_password_confirmation[0] }}</span>
                        </div>
                        <div class="form-group col-md-12 text-right mt-4">
                            <NuxtLink class="btn btn-secondary mr-1" to="/profile">Cancel</NuxtLink>
                            <button :disabled="form.processing" class="btn btn-primary">Save Password</button>
                        </div>
                    </form>
                </div>
            </div>
         </div>
    </div>
</template>

<script>
export default {
  head: {
    title: "Edit Password",
  },
  layout: "default",
  middleware: ['authenticated', 'completed'],
  data() {
    return {
      showPassword: false,
      showPassword2: false,
      showPassword3: false,
      form : {
        processing: false,
        errors :[],
        old_password: '',
        new_password: '',
        new_password_confirmation: '',
      }
    }
  },
  methods: {
    async submit(){
        this.form.processing = true;
        this.form.errors = [];

        try {
            const { data } =  await this.$updatePassword(this.form)
            this.$router.push('/profile')
        } catch (e) {
            this.form.errors = e.response.data.errors
            this.form.processing = false;
            this.$toast.error('An error occured!');
        }
    },
    getErrors(key){
        return this.form.errors?.[key]?.[0]
    }
  }
}
</script>