<template>
<div class="dropdown" v-if="$auth.loggedIn">
    <a class="dropdown-toggle mr-3" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" style="color: #4A4A4A;font-size: 20px;position: relative;">
        <i class="far fa-bell mt-3"></i>
        <span class="notif-number">{{ $auth.user.unreadNotif }}</span>
    </a>
    <div class="dropdown-menu" style="min-width: 283px;" :style="{'transform': $screen.width<=990 ? 'translate3d(-250px, 5px, 0px) !important': ''}">
        <div class="text-center border-notification" v-if="$auth.user.notifications.length<=0">
            <h5><i class="far fa-bell-slash"></i> Tidak Ada Notifikasi</h5>
        </div>
        <template v-else>
        <div class="d-flex p-1 border-notification" v-for="(notif, idx) in $auth.user.notifications" :key="idx">
            <div>
                <img :src="notif.icon" style="width: 25px;height: 25px;">
            </div>
            <div class="w-100 pl-2 notif-content">
                <NuxtLink :to="notif.path">
                <label class="d-flex justify-content-between">
                    <strong>{{ notif.message_title }}</strong>
                    <small class="px-1">12:30</small>
                </label>
                <label>
                    <small v-html="notif.message"></small>
                </label>
                </NuxtLink>
            </div>
        </div>
        </template>
        <div class="text-center p-2">
            <NuxtLink to="/notifications"><b>Show More</b></NuxtLink>
        </div>
    </div>
</div>
</template>
<style>
    .notif-content label{
        color: #4A4A4A !important;
        cursor: pointer;
    }

    .notif-number{
        position: absolute;
        border-radius: 50%;
        top: -2px;
        right: -9px;
        font-size: 10px;
        background: #e44a01;
        color: #FFFF;
        min-width: 16px;
        padding: 0 15%;
        text-align: center;
    }
</style>