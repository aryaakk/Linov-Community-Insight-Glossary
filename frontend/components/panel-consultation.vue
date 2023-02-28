<template>
<div class="panel-subhead">
    <modal-delete v-if="showModalDelete" @onClicked="handleAction" context="Thread"></modal-delete>
    <div class="main-thread">
        <div class="row header-thread">
            <div class="d-flex col-sm-6 col-xs-12">
                <img :src="data?.author?.photo ? data.author.photo : '/img/home/user.png'" class="img-circle avatar-thread cursor" alt="Avatar">
                <div class="pl-4">
                    <NuxtLink :to="`/profile/${data.author.id}`">
                    <label class="cursor">{{ data.author?.name}}</label>
                    </NuxtLink>
                    <img src="/img/icon/ic_verified.svg" height="20" v-if="data.author.badge=='1'"><br>
                    <span>{{ data.human_date }}</span>
                </div>
            </div>
            <div class="col-sm-6 col-xs-12 text-right">
                <NuxtLink :to="`/category/${type.code}`" v-for="(type, idx) in data.types" :key="idx" class="p-2 mr-2 tag-label" :style="{color: type.color, background: type.color_bg }">
                    {{ type.name }}
                </NuxtLink>   
            </div>                 
            <div v-if="$auth.loggedIn" class="btn-group dropdown btn-option-p">
                <div class="btn-option dropdown-toggle" v-if="!link || $auth.loggedIn && $auth.user.id==data.user_id" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    <i class="fas fa-ellipsis-v"></i>
                </div>
                <div class="dropdown-menu postion-dropdown" id="optiov-thread">
                        <li class="item-report" @click="showModalDelete=true" data-toggle="modal" data-target="#modal-delete" v-if="$auth.loggedIn && $auth.user.id==data.user_id"><span class="opt-delete"><i class="fas fa-trash-alt"></i>&nbsp;Delete Thread</span></li>
                        <li v-if="!link" @click="$router.push(`consultation/${data.slug_id}`)">
                            <span class="opt-color"><i class="fas fa-book-open"></i></span>Open Thread
                        </li>
                </div>
            </div>
        </div>
        
        <div class="row-main-thread">
            <NuxtLink :to="`/consultation/${data.slug_id}`" v-if="!link">
                <h2 class="headTitle cursor">{{ data.title }}</h2>
            </NuxtLink>
            <h2 class="headTitle cursor" v-else>{{ data.title }}</h2>
        </div>

        <div class="row-main-thread">
            <NuxtLink :to="`/consultation/${data.slug_id}`" v-if="!link" class="cursor thread-content">
                <p style="white-space: pre-line;">
                    {{ data.description.length>234 ? data.description+'...' : data.description }}
                </p>
            </NuxtLink>
            <p class="cursor thread-content" v-else style="white-space: pre-line;">
                {{ data.description }}
            </p>
        </div>

        <thread-images :images="data.images"></thread-images>
        <thread-files :files="data.files"></thread-files>
        <div class="hiden-content-thread" v-if="!$auth.loggedIn">
            <div class="panel-ads-consultaion">
                <div class="row">
                <div class="col-md-9 col-xs-12 d-flex">
                <img src="/img/thread/consul-msg-icon.svg" class="mr-2">
                <span>Konsultasikan Masalah Karir Anda Bersama Kami, Gratis! </span>
                </div>
                <div class="col-md-2 col-xs-12 text-center">
                    <NuxtLink to="/consultation/create" class="btn px-3 btn-ads-consul">Tanya Sekarang!</NuxtLink>
                </div>
                </div>
            </div>
        </div>
        <div class="d-flex justify-content-right pt-4 mt-5" style="position: relative;">            
            <div class="pt-4">
                <span class="span-comment span-footer-thread cursor love-thread pt-4" @click="loveThread(data.id)">
                    <i class="fa-heart span-comment2 cursor" :class="{ 'red-heart' : data.love_status, 'fas': data.love_status, 'far':!data.love_status }"></i>{{ data.total_love }}
                </span>
                <span class="span-comment span-footer-thread cursor pt-4">
                    <NuxtLink :to="`/threads/${data.slug_id}`" v-if="!link">
                    <i class="far fa-comment-alt span-comment2 cursor"></i>{{ data.total_comment }}
                    </NuxtLink>
                    <span v-else><i class="far fa-comment-alt span-comment2 cursor"></i>{{ data.total_comment }}</span>
                </span>
                <span class="span-comment span-footer-thread cursor"><i class="fas fa-paper-plane cursor"></i></span>
            </div>
        </div>
    </div>
</div>
</template>
<style>
.span-comment a{
    color : #B5BBC2 !important
}
</style>
<script>
export default {
    name: "panel-thread",
    props: ["data", "link"],
    data() {
        return {
            showModalDelete: false,
        }
    },
    methods: {
        async loveThread(id) {
            if (!this.$auth.loggedIn) {
                this.$router.push("/auth/login");
                return;
            }
            const { data } = await this.$loveConsultThread({}, id);
            this.data.smallloves = data.small_loves;
            this.data.total_love = data.total_love;
            this.data.love_status= data.love_status;
        },
        async handleAction(status){
            if(status){
                if (!this.$auth.loggedIn) {
                    this.$router.push("/auth/login");
                    return;
                }
                const { status } = await this.$deleteConsultThread({}, this.data.id);
                if(status==204){
                    this.$toast.success("Thread konsultasi berhasil dihapus");
                }
                this.$emit("onDelete", this.data.id);
            }
            this.showModalDelete = false;
        },
    },
};
</script>