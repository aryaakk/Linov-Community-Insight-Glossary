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
                        <h4><b>Notifikasi</b></h4>
                        <hr style="border: 1px solid #F0F0F0;">
                    </div>
                    <form @submit.prevent="submit">
                    <div class="d-flex pt-4 px-5 justify-content-between">
                        <div>
                            <p><b>Notifikasi ke Email</b></p>
                        </div>
                        <div class="pt-4">
                            <label class="switch">
                                <input v-model="form.is_email" type="checkbox" checked>
                                <span class="slider round"></span>
                            </label>
                        </div>
                    </div>
                    <div class="d-flex pt-4 px-5 justify-content-between">
                        <div>
                            <p><b>Berlangganan Newsletter</b></p>
                        </div>
                        <div class="pt-4">
                            <label class="switch">
                                <input v-model="form.is_newsletter" type="checkbox" checked>
                                <span class="slider round"></span>
                            </label>
                        </div>
                    </div>
                    <div class="text-right mt-5">
                        <NuxtLink class="btn btn-secondary mr-1" to="/profile">Cancel</NuxtLink>
                        <button class="btn btn-primary">Save Data</button>
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
    title: "Edit Notifikasi",
  },
  layout: "default",
  middleware: ['authenticated', 'completed'],
  data() {
    return {
        form: {
            is_email: true,
            is_newsletter: false,
        },
    }
  },
  async mounted () {
    await this.$getNotification().then(response=>{
        this.form.is_email = response.data?.is_email=='1'?true:false;
        this.form.is_newsletter = response.data?.is_newsletter=='1'?true:false;
    });
  },
  methods: {
        submit() {
            this.$updateNotification(this.form).then(response=>{
                this.$router.push('/profile');
            });
        },
  },
}
</script>