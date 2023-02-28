<template>
<div class="panel-subhead">
    <modal-delete v-if="showModalDelete" @onClicked="handleAction" context="Thread"></modal-delete>
    <modal-report v-if="showModalReport" @OnReport="reportThread"></modal-report>
    <div class="main-thread" v-if="!data.dismiss_status">
        <div class="row header-thread">
            <div class="d-flex col-sm-6 col-xs-12">
                <img :src="data.author?.photo || '/img/home/user.png'" class="img-circle avatar-thread cursor" alt="Avatar">
                <div class="pl-4">
                    <NuxtLink :to="`/profile/${data.author.id}`">
                    <label class="cursor">{{ data.author ? data.author.name : ''}}</label>
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
                <div class="btn-option dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    <i class="fas fa-ellipsis-v"></i>
                </div>
                <div class="dropdown-menu postion-dropdown" id="optiov-thread">
                        <li class="item-report" @click="showModalDelete=true" data-toggle="modal" data-target="#modal-delete" v-if="$auth.loggedIn && $auth.user.id==data.user_id"><span class="opt-delete"><i class="fas fa-trash-alt"></i>&nbsp;Delete Thread</span></li>
                        <li class="item-report" @click="showModalReport=true" data-toggle="modal" data-target="#modal-report" v-else><span class="opt-delete"><i class="fas fa-exclamation-triangle icn-li"></i>Report</span></li>
                        <li class="btnDismiss"  @click="dismissThread(data.id)"><span class="opt-color"><i class="fas fa-ban icn-li"></i></span>Dismiss</li>
                        <li v-if="!link" @click="$router.push(`threads/${data.slug_id}`)">
                            <span class="opt-color"><i class="fas fa-book-open"></i></span>Open Thread
                        </li>
                        <li v-if="edit" @click="$router.push(`threads/edit/${data.id}`)">
                            <span class="opt-color"><i class="fas fa-edit"></i></span>Edit Thread
                        </li>
                </div>
            </div>
        </div>
        
        <div class="row-main-thread">
            <NuxtLink :to="`/threads/${data.slug_id}`" v-if="!link">
                <h2 class="headTitle cursor">{{ data.title }}</h2>
            </NuxtLink>
            <h2 class="headTitle cursor" v-else>{{ data.title }}</h2>
        </div>

        <div class="row-main-thread">
            <NuxtLink :to="`/threads/${data.slug_id}`" v-if="!link" class="cursor thread-content">
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

        <div class="row-main-thread mt-3" v-if="data.pollings">
            <div class="post-poll">
                <h4>{{ data.pollings.description }}</h4>
                <div class="opsi-poll" style="position: relative;" @click="!data.pollings.poll_status && isValidPollDate(data.pollings.end_date) ? pollingThread(data.id, data.pollings.id, option.id) : ''" v-for="(option, id) in data.pollings.details" :key="id">
                    <span v-if="data.pollings.total_vote>0 && (!$auth.loggedIn || data.pollings.poll_status==1)" :style="{'width': getPollPrecent(data.pollings.total_vote, option.counter)+'%'}" class="poll-bar"></span>
                    <span v-if="data.pollings.total_vote>0 && (!$auth.loggedIn || data.pollings.poll_status==1)" class="poll-label">{{ parseFloat(getPollPrecent(data.pollings.total_vote, option.counter)).toFixed(1) }} %</span>
                    <p style="z-index: 1;">{{ option.poll_name }}</p>
                </div>
                <div class="footer-vote">
                    <span>{{ data.pollings.total_vote||0 }} Vote</span>
                    <span v-if="isValidPollDate(data.pollings.end_date)">• Polling ends in {{ getDiffPollDay(data.pollings.end_date) }} days</span>
                </div>
            </div>
        </div>
        
        <div class="d-flex justify-content-between pt-4" style="position: relative;">
            <div class="d-flex">
                <div style="position: relative;min-width: 70px;" v-show="data.smallloves.length>0">
                    <img :src="love.photo ? love.photo : '/img/home/user.png'" v-for="(love, idx) in data.smallloves" :key="idx" class="ava-footer cursor img-circle avatar-nav" :class="{'one': idx==0, 'two': idx==1, 'three': idx==2}">
                </div>
                <!-- <span class="px-2 cursor pt-4 mr-2">{{ data.total_love }} Users Likes</span> -->
                <span class="span-comment span-footer-thread cursor love-thread pt-4" @click="loveThread(data.id)">
                    <i class="fa-heart span-comment2 cursor" :class="{ 'red-heart' : data.love_status, 'fas': data.love_status, 'far':!data.love_status }"></i>{{ data.total_love }}
                </span>
                
                <span class="span-comment span-footer-thread cursor pt-4">
                    <NuxtLink :to="`/threads/${data.slug_id}`" v-if="!link">
                    <i class="far fa-comment-alt span-comment2 cursor"></i>{{ data.total_comment }}
                    </NuxtLink>
                    <span v-else><i class="far fa-comment-alt span-comment2 cursor"></i>{{ data.total_comment }}</span>
                </span>
            </div>                
            <div class="pt-4 d-flex">
                <span class="span-comment span-footer-thread cursor bookmark-thread" @click="bookMarkThread(data.id)">
                    <i :class="data.bookmark_status ? 'fas' : 'far'" class=" fa-bookmark cursor"></i>
                </span>
                <div class="dropdown">
                    <span class="cursor" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"><i class="fas fa-paper-plane cursor"></i></span>
                    <ul class="dropdown-menu" id="optiov-thread"  style="transform: translate3d(-114px, 5px, 0px) !important;">
                        <li><span><i class="fab fa-facebook"></i></span>Facebook</li>
                        <li><span><i class="fab fa-twitter"></i></span>Twitter</li>
                        <li><span><i class="fab fa-linkedin"></i></span>Linkedin</li>
                    </ul>
                </div>

            </div>
        </div>
    </div>
    <div class="main-thread dismiss-main" v-else>
        <div>
            <span class="badge color-info badge-undo">!</span>
            <label class="label-undo">Anda Telah Dismiss thread ini, jika ingin melihat thread ini harap menekan tombol undo</label>
            <button type="button" class="btn-undo" @click="dismissThread(data.id)">
                Undo
            </button>
        </div>
    </div>
</div>
</template>

<script>
export default {
    name: "panel-thread",
    props: ["data", "link", "edit"],
    data() {
        return {
            showModalDelete: false,
            showModalReport: false,
        }
    },
    methods: {
        async loveThread(id) {
            if (!this.$auth.loggedIn) {
                this.$router.push("/auth/login");
                return;
            }
            const { data } = await this.$loveThread({}, id);
            this.data.smallloves = data.small_loves;
            this.data.total_love = data.total_love;
            this.data.love_status = data.love_status;
        },
        async bookMarkThread(id) {
            if (!this.$auth.loggedIn) {
                this.$router.push("/auth/login");
                return;
            }
            const { data } = await this.$bookMarkThread({}, id);
            this.data.bookmark_status = data.bookmark_status;
        },
        async dismissThread(id) {
            if (!this.$auth.loggedIn) {
                this.$router.push("/auth/login");
                return;
            }
            const { data } = await this.$dismissThread({}, id);
            this.data.dismiss_status = data.dismiss_status;
        },
        async pollingThread(id, poll_id, detail_id) {
            if (!this.$auth.loggedIn) {
                this.$router.push("/auth/login");
                return;
            }
            const { data } = await this.$pollingThread({ poll_id: poll_id, detail_id: detail_id }, id);
            this.data.pollings = data
        },
        async handleAction(status){
            if(status){
                if (!this.$auth.loggedIn) {
                    this.$router.push("/auth/login");
                    return;
                }
                const { status} = await this.$deleteThread({}, this.data.id);
                if(status==204){
                    this.$toast.success("Thread deleted successfully");
                }
                this.$emit("onDelete", this.data.id);
            }
            this.showModalDelete = false;
        },
        async reportThread(param){
            if (!this.$auth.loggedIn) {
                this.$router.push("/auth/login");
                return;
            }
            const { data, status } = await this.$reportThread(param, this.data.id);
    
            if(status==200){
             this.$emit("onDelete", data.thread_id)
             this.$toast.success("This thread has been reported. Thank you for letting us know. We will take action on this report as soon as possible.");
            }
            this.showModalReport = false;
        },
        isValidPollDate(date) {
            return this.$dayjs().isBefore(this.$dayjs(date));
        },
        getDiffPollDay(date) {
            const diff = this.$dayjs(date).diff(this.$dayjs(), "days");
            return diff;
        },
        getPollPrecent(total_vote, counter){
            return ((counter/total_vote)*100);
        }
    },
};
</script>