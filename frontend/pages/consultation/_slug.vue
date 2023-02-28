<template>
    <div>
        <div class="main-home">
            <div class="container">
                <div class="row">
                    <div class="col-md-9">
                        <div class="panel-subhead col-md-12">
                            <div class="title-subhead subhead-page">
                                <label><NuxtLink to="/consultation">Back To Feed</NuxtLink> > Community > Thread</label>
                            </div>
                        </div>

                        <div class="panel-subhead col-md-12 title-form">
                            <div class="icon-subhead">
                                <img src="/img/icon/ic_thread.png">
                            </div>
                            <div class="title-subhead">
                                <label>Threads & Discuss</label>
                            </div>
                        </div>

                        <div class="col-md-12 mt-3 mb-4">
                            <div class="px-3" style="background-image: url('/img/wording_bg.png');background-size: cover;background-repeat:no-repeat;border-radius: 10px;">
                            <h4 class="text-white text-center" style="padding-top: 2rem;padding-bottom: 2rem">Selamat Datang Di thread HR Berpengalaman</h4>
                            </div>
                        </div>

                        <div class="panel-subhead col-md-12 title-form" v-if="thread">
                            <panel-consultation :data="thread" :link="true" @onDelete="$router.push('/consultation')"></panel-consultation>
                        </div>

                        <div class="panel-subhead col-md-12 title-form" v-if="$auth.loggedIn && validUser">
                            <div class="icon-subhead">
                                <img src="/img/icon/ic_thread.png">
                            </div>
                            <div class="title-subhead">
                                <label>Comment</label>
                            </div>
                        </div>

                        <div class="panel-subhead col-md-12" v-if="$auth.loggedIn && validUser">
                             <div class="main-thread">
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
                                                <span class="mr-4"  @click="$refs.file.click()" v-tooltip.top="{ content: 'Files' }">
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
                        </div>

                        <panel-comment-con @onDelete="deleteCommentById" class="col-md-12" :comment="comment" v-for="(comment, idx) in comments" :key="idx"></panel-comment-con>
                    </div>

                    <div class="col-md-3 px-1 pl-2">
                        <div class="title-sidebar">
                            <ads-konsultant></ads-konsultant>
                        </div>
                        <div>
                            <div class="panel-subhead col-md-12 no-padding mb-4">
                            <div class="icon-subhead">
                                <img src="/img/icon/ic_features.png">
                            </div>
                            <div class="title-subhead">
                                <label>Features</label>
                            </div>
                            </div>
                            <div class="col-md-12 px-5 mb-3">
                                <ads-konsultasi2></ads-konsultasi2>
                            </div>
                        </div>
                        <div >
                            <ads-article></ads-article>
                        </div>
                        <div class="col-md-12 px-0 py-2" v-for="(discus, idx) in relateThreads" :key="idx">
                            <div class="card-most" @click="$router.push(`/threads/${discus.id}`)" :class="discus.image ? 'text-white card-img-overlay' : ''" :style="discus.image ? `background-image: url('${discus.image.file_url}');` : ''">
                                <div class="p-4 mb-4">
                                    <div class="card-headtitle">
                                        <h5 class="card-title" :style="{'color': discus.color}">{{ discus.name }}</h5>
                                    </div>
                                    <p class="card-text" style="margin-bottom: 36px;">
                                        {{ discus.title }}
                                    </p>
                                </div>
                                <div class="p-4" style="position: absolute;bottom: 15px">
                                    <span class="span-comment mr-3"><i class="fas fa-eye span-comment2"></i>{{ discus.total_view }}</span>
                                    <span class="span-comment mr-3"><i class="fas fa-heart span-comment2"></i>{{ discus.total_love }}</span>
                                    <span class="span-comment mr-3"><i class="fas fa-comment-alt span-comment2"></i>{{ discus.total_comment }}</span>
                                </div>
                                <div class="card-bottom-line" :style="{background: discus.color}"></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>
<script>
export default {
  head() {
    return {
        title: this.thread?.title || 'Detail Consultation',
    }
  },
  layout: "default",
  data() {
    return {
        slug: this.$router.currentRoute.params.slug,
        thread: null,
        relateThreads: [],
        comments: [],
        form: {
            comment: '',
            photos: [],
            upfiles: [],
            mode: 'thread',
            processing: false,
            errors: [],
        }
    }
  },
  computed: {
    validUser(){
        return this.$auth?.user?.id==this.thread?.user_id || this.$auth?.user?.is_consultant=='1'
    }
  },
  async fetch(){
    await this.$getOneConsult(this.slug).then(data => {
        this.thread = data.data
    }).catch(error=>{
        return this.$nuxt.error({ statusCode: error.response.status, message:  error.response.statusText })
    })
    await this.$getConsultComments(this.thread.id).then(data => {
        this.comments = data.data
    })
    // await this.$getRelateThreads(this.thread.id).then(data => {
    //     this.relateThreads = data.data
    // })
  },
  methods: {
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

        try {
            const { data } =  await this.$postConsultComment(form, this.thread.id)
            this.comments.push(data)
            this.form.comment =''
            this.form.photos =[]
            this.form.upfiles =[]
            this.form.processing = false;
        } catch (e) {
            this.form.errors = e.response.data.errors
            this.form.processing = false;
            this.$toast.error('An error occured!');
        }
    },
    deleteCommentById(id){
        const comment = this.flattenItems(this.comments, 'subcomment').find(comment => comment.id == id)

        if(comment.comment_thread_id){
            let subComments = this.comments.find(comm=>comm.id==comment.comment_thread_id).subcomment
            const idx = subComments.findIndex(comment => comment.id == id)
            subComments.splice(idx, 1)
        }else{
            const idx = this.comments.findIndex(comment => comment.id == id)
            this.comments.splice(idx, 1)
        }
    },
    prepareUploadPhotos(e){
        this.form.photos = [...this.form.photos, ...e.target.files];
    },
    prepareUploadFiles(e){
        this.form.upfiles = [...this.form.upfiles, ...e.target.files];
    },
    flattenItems (items, key) {
        return items.reduce((flattenedItems, item) => {
            flattenedItems.push(item)
            if (Array.isArray(item[key])) {
                flattenedItems = flattenedItems.concat(this.flattenItems(item[key], key))
            }
            return flattenedItems
        }, [])
    },
  }
}
</script>