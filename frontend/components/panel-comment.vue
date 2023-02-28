<template>
    <div class="panel-subhead">
        <modal-delete v-if="showModalDelete" @onClicked="handleAction" context="Comment"></modal-delete>
        <div class="main-thread">
            <div class="d-flex head-comment">
                <NuxtLink :to="`/profile/${comment.author.id}`">
                <img :src="comment.author?.photo || '/img/home/user.png'" class="img-circle mr-4 avatar-nav cursor" alt="Avatar">
                <label class="lblAvaThread mt-2">{{ comment.author?.name }}</label>
                </NuxtLink>
                <img src="/img/icon/ic_verified.svg" style="height: 20px !important; margin-top: 8px;" v-if="comment.author.badge=='1'">
            </div>
            <div class="mt-4" style="min-height: 50px">
                {{ comment.comment }}
            </div>
            <thread-images :images="comment.images"></thread-images>
            <thread-files :files="comment.files"></thread-files>
            <div class="d-flex justify-content-between pt-2 mt-4" style=" border-top: 1px solid #E6E6E6;">
                <div class="pt-2">
                    <span class="cursor" style="color: #BEBDBD">{{ comment.human_date }}</span>
                </div>
                <div class="pt-2 d-flex justify-content-right">
                    <span class="span-comment span-footer-thread cursor love-thread" @click="loveComment(comment.id)">
                        <i class=" fa-heart span-comment2 cursor" :class="{ 'red-heart' : comment.love_status, 'fas': comment.love_status, 'far':!comment.love_status }"></i>{{ comment.total_love }}
                    </span>
                    <span class="span-comment span-footer-thread cursor" @click="toggleReply" v-if="comment.hasOwnProperty('subcomment')">
                        <i class="fas fa-reply span-comment2 cursor"></i> Reply
                    </span>
                    <div class="dropup " v-if="$auth.loggedIn && $auth.user.id==comment.user_id">
                        <div class="btn-option dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            <i class="fas fa-ellipsis-h"></i>
                        </div>
                        <ul class="dropdown-menu postion-dropdown p-3">
                            <!-- <li class="dropdown-item mt-2" style="cursor: pointer;"><i class="fas fa-pencil-alt opt-color"></i> Edit</li> -->
                            <li @click="showModalDelete=true" data-toggle="modal" data-target="#modal-delete" class="dropdown-item mt-2 opt-delete" style="cursor: pointer;"><i class="fas fa-trash-alt"></i> Delete</li>
                        </ul>
                    </div>
                </div>
            </div>
            <div class="pt-4" v-if="$auth.loggedIn" v-show="comment.show_reply">
                <div class="d-flex head-comment">
                    <img :src="$auth.loggedIn && $auth.user.avatar ? $auth.user.avatar : '/img/home/user.png'" class="img-circle mr-4 avatar-nav cursor" alt="Avatar">
                    <form class="w-100" @submit.prevent="postComment">
                        <div class="form-group"  :class="form.errors?.comment ? 'has-error' : ''">
                        <textarea v-model="form.comment" cols="10" rows="2" class="form-control" placeholder="enter your comment"></textarea>
                        <span class="help-block" v-if="form.errors?.comment">{{ form.errors?.comment[0] }}</span>
                        </div>
                        
                        <div class="form-group" v-if="form.photos.length>0" :class="$_errors(form.errors, 'photos') ? 'has-error' : ''">
                        <list-photos :photos="form.photos"></list-photos>
                        <span class="help-block" v-if="$_errors(form.errors, 'photos')">{{ $_errors(form.errors, 'photos')}}</span>
                        </div>
                        <div class="form-group" v-if="form.upfiles.length>0" :class="$_errors(form.errors, 'photos') ? 'has-error' : ''">
                        <list-files :files="form.upfiles"></list-files>
                        <span class="help-block" v-if="$_errors(form.errors, 'upfiles')">{{ $_errors(form.errors, 'upfiles')}}</span>
                        </div>
                        <div class="d-flex justify-content-between pt-4">
                            <div>
                                <input type="file" ref="photo" @change="prepareUploadPhotos" accept="image/*" style="display: none">
                                <span class="mr-4"  @click="$refs.photo.click()" v-tooltip.top="{ content: 'Photo' }">
                                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                    <path d="M19 3H5C3.89543 3 3 3.89543 3 5V19C3 20.1046 3.89543 21 5 21H19C20.1046 21 21 20.1046 21 19V5C21 3.89543 20.1046 3 19 3Z" stroke="#AFAFAF" stroke-width="2.19527" stroke-linecap="round" stroke-linejoin="round"/>
                                    <path d="M8.5 10C9.32843 10 10 9.32843 10 8.5C10 7.67157 9.32843 7 8.5 7C7.67157 7 7 7.67157 7 8.5C7 9.32843 7.67157 10 8.5 10Z" stroke="#AFAFAF" stroke-width="2.19527" stroke-linecap="round" stroke-linejoin="round"/>
                                    <path d="M21 15L16 10L5 21" stroke="#AFAFAF" stroke-width="2.19527" stroke-linecap="round" stroke-linejoin="round"/>
                                    </svg>
                                </span>
                                <input type="file" ref="file" @change="prepareUploadFiles" accept="	application/pdf" style="display: none">
                                <span class="mr-4" @click="$refs.file.click()" v-tooltip.top="{ content: 'Files' }">
                                    <svg width="23" height="22" viewBox="0 0 23 22" fill="none" xmlns="http://www.w3.org/2000/svg">
                                    <path d="M13.29 2.05762H6.13612C5.66179 2.05762 5.20689 2.24604 4.87148 2.58145C4.53608 2.91685 4.34766 3.37175 4.34766 3.84608V18.1538C4.34766 18.6281 4.53608 19.083 4.87148 19.4184C5.20689 19.7538 5.66179 19.9422 6.13612 19.9422H16.8669C17.3412 19.9422 17.7961 19.7538 18.1315 19.4184C18.4669 19.083 18.6553 18.6281 18.6553 18.1538V7.423L13.29 2.05762Z" stroke="#AFAFAF" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                                    <path d="M13.2891 2.05762V7.423H18.6544" stroke="#AFAFAF" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                                    <path d="M15.0777 11.894H7.92383" stroke="#AFAFAF" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                                    <path d="M15.0777 15.4712H7.92383" stroke="#AFAFAF" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                                    <path d="M9.71229 8.31738H8.81806H7.92383" stroke="#AFAFAF" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                                    </svg>
                                </span>
                            </div>
                            <button :disabled="form.processing" class="btn btn-primary">Comment</button>
                        </div>
                    </form>
                </div>
            </div>
            <div class="pt-3" v-if="haveSubcomment">
                <div v-show="comment.show_sub" class="mb-4" style="border-left: 1px solid #E6E6E6;">
                    <panel-comment @onDelete="(id)=>{ $emit('onDelete', id) }" :comment="comm" v-for="(comm, idx) in comment.subcomment" :key="idx"></panel-comment>
                </div>
                <span class="cursor" v-show="!comment.show_sub && comment.total_reply>0" @click="comment.show_sub=true">Lihat {{ comment.total_reply }} Balasan Lainnya &nbsp;<i class="fas fa-chevron-right"></i></span>
                <span class="cursor" v-show="comment.show_sub && comment.total_reply>0" @click="comment.show_sub=false">Hide Comments &nbsp;<i class="fas fa-chevron-up"></i></span>
            </div>
        </div>
    </div>
</template>

<script>

export default {
  name: "panel-comment",
  props: ["comment"],
  data() {
    return {
        form:{
            comment:'',
            photos: [],
            upfiles: [],
            mode: 'comment',
            processing: false,
            errors: [],
        },
        showModalDelete: false,
    }
  },
  computed:{
    haveSubcomment(){
      return this.comment.hasOwnProperty('subcomment') && this.comment.subcomment.length>0;
    }
  },
  methods: {
    async loveComment(id){
        if(!this.$auth.loggedIn){
            this.$router.push('/auth/login');
            return
        }
        const { data } =  await this.$loveComment({}, id);
        this.comment.total_love = data.total_love;
        this.comment.love_status= data.love_status;

    },
    async postComment(){
        this.form.processing = true;
        this.form.errors = [];

        const form = new FormData()
        form.append('comment', this.form.comment)
        this.form.photos.forEach((foto, idx) => {
            form.append(`photos[${idx}]`, foto)
        });
        this.form.upfiles.forEach((file, idx) => {
            form.append(`upfiles[${idx}]`, file)
        });
        form.append('mode', this.form.mode)
        form.append('thread_id', this.comment.thread_id)
        
        try {
            const { data } =  await this.$postComment(form, this.comment.id)
            this.form.comment =''
            this.form.photos =[]
            this.form.upfiles =[]
            this.comment.subcomment.push(data)
            this.comment.total_reply = this.comment.total_reply+1;
            this.comment.show_reply = false
            this.form.processing = false;
        } catch (e) {
            this.form.errors = e.response.data.errors
            this.form.processing = false;
        }
    },
    async handleAction(status){
        if(status){
            if (!this.$auth.loggedIn) {
                this.$router.push("/auth/login");
                return;
            }
            const { data } = await this.$deleteComment({}, this.comment.id);
            this.$toast.success("Komentar berhasil di hapus");
            this.$emit('onDelete', this.comment.id);
        }
        this.showModalDelete = false;
    },
    toggleReply(){
        if(!this.$auth.loggedIn){
            this.$router.push('/auth/login');
            return
        }

        this.comment.show_reply = !this.comment.show_reply;
    },
    prepareUploadPhotos(e){
        this.form.photos = [...this.form.photos, ...e.target.files];
    },
    prepareUploadFiles(e){
        this.form.upfiles = [...this.form.upfiles, ...e.target.files];
    },
  }
}
</script>