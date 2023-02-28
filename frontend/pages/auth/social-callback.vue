<template>
    <div class="container d-flex justify-content-center" style="display: relative;">
        <img src="/img/loader.gif" style="height: 50px;position: absolute;top:45%" alt="loading...">
    </div>
</template>

<script>
export default {
    data() {
        return {
            token: this.$route.query.token ? this.$route.query.token : null,
        }
    },
    layout: "nolayout",
    async mounted() {
        const params = new URLSearchParams(new URL(atob(this.token)).search);
        const redirectUrl = localStorage.getItem('redirectUrl')
        try {
            await this.$auth.loginWith("laravelSanctum", { data: {
                expires: params.get('expires'), 
                token: params.get('token'), 
                signature: params.get('signature'), 
            }});
            this.$router.push(redirectUrl || '/')
            if(this.$auth.user?.hasProfile){
            localStorage.removeItem('redirectUrl')
            }
        } catch (e) {
            console.log(e.response.data.errors)   
        }
    }
}
</script>
