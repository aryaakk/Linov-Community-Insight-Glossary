<template>
    <ul class="nav nav-setting">
        <li :class="menu.url==$route.path ? 'setting-active' : ''" v-for="(menu, idx) in menuData" :key="idx">
            <a href="#" v-if="menu.title=='Logout'" style="color:#E74357">{{ menu.title }}</a>
            <NuxtLink :to="menu.url" v-else>{{ menu.title }}</NuxtLink>
        </li>
    </ul>
</template>
<style scoped>
.setting-active{
    background: #FCFCFC;
    border-right: 2.5px solid #62B6EE;
    font-weight: bold;
}
@media only screen and (max-width: 990px) {
  .nav-setting{
    display: flex;
    justify-content: space-between;
  }
  .setting-active{
      background: #FCFCFC;
      border-right: 0px !important;
      border-bottom: 2.5px solid #62B6EE;
      font-weight: bold;
  }
  .nav-setting li{
    text-align: center;
    width: 33%;
  }
}
</style>
<script>
export default {
  name: "nav-setting",
  props: ["active","type"],
  computed:{
    menuData: function(){
      return this.menus.filter(menu=>menu.type==(this.type||'edit'))
    }
  },
  data() {
    return {
      menus: [
        {'title': 'Detail Account', 'url': '/profile/edit', 'type': 'edit'},
        {'title': 'Verifikasi', 'url': '/profile/verification', 'type': this.$config.baseURL!='https://community-api.linovhr.com'? 'edit':'hidden'},
        {'title': 'Logout', 'url': '#', 'type': 'edit'},
        {'title': 'Password', 'url': '/profile/edit-password', 'type': 'setting'},
        {'title': 'Notification', 'url': '/profile/edit-notification', 'type': 'setting'},
        // {'title': 'Bantuan & Dukungan', 'url': '/profile/help-support', 'type': 'setting'},
      ]
    }
  }
}
</script>