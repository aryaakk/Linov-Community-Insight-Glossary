<template>
    <div class="px-1 py-2 nav-profile">
        <ul class="nav nav-tabs d-flex" style="flex-wrap: wrap;">
            <li class="px-0" style="flex: 1;" :class="$route.path==menu.url? 'active' : ''" v-for="(menu, idx) in menus" :key="idx">
               <NuxtLink :to="menu.url">{{ menu.title }}</NuxtLink>
            </li>
        </ul>
    </div>
</template>
<style>
    .nav-profile .nav-tabs li a{
        font-weight: 600;
        width: 100%;
        border: 1px solid #e0e0e0;
    }

    .nav-profile .nav-tabs .active a{
        background: #FCFCFC !important;
    }

    .nav-profile .nav-tabs li{
        background: #f5f4f4;
    }

    .nav-profile .nav-tabs li:hover{
        background: #fffefe;
    }
</style>
<script>
export default {
  name: "nav-profile",
  data() {
    return {
        menus: [
            {title: 'Profile', url: '/profile/edit'},
            {title: 'Socmed', url: '/profile/edit-social'},
            {title: 'Experience', url: '/profile/edit-experience'},
            {title: 'Education', url: '/profile/edit-education'},
            {title: 'Skills', url: '/profile/edit-skills'},
            {title: 'Certificate', url: '/profile/edit-certificate'},
        ]
    }
  }
}
</script>