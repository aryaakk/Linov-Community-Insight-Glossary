<template>
    <div>
        <div class="main-home mt-5">
            <div class="container">
                <div class="row">
                    <div class="col-md-9">
                        <div class="panel-subhead col-md-12">
                            <div class="title-subhead subhead-page">
                                <label><NuxtLink to="/">Back To Feed</NuxtLink> > Community > Thread</label>
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

                        <div class="panel-subhead col-md-12 title-form" v-if="thread">
                            <panel-thread :data="thread" :link="true" @onDelete="$router.push('/')"></panel-thread>
                        </div>

                        <div class="panel-subhead col-md-12 title-form">
                            <div class="icon-subhead">
                                <img src="/img/icon/ic_thread.png">
                            </div>
                            <div class="title-subhead">
                                <label>Comment</label>
                            </div>
                        </div>

                        <div class="panel-subhead col-md-12" >
                             <div class="main-thread">
                                <div class="d-flex head-comment" v-if="$auth.loggedIn">
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
                                                    <img style="width: 25px;!important" src="/img/icon/ic_small_photo.svg" alt="">
                                                </span>
                                                <input type="file" ref="file" @change="prepareUploadFiles" accept="	application/pdf" style="display: none">
                                                <span class="mr-4"  @click="$refs.file.click()" v-tooltip.top="{ content: 'Files' }">
                                                    <img style="width: 25px;!important" src="/img/icon/ic_small_file.svg" alt="">
                                                </span>
                                            </div>
                                            <button :disabled="form.processing" class="btn btn-primary">Comment</button>
                                        </div>
                                    </form>
                                </div>
                                <div class="d-flex justify-content-center" v-else>
                                    <NuxtLink class="btn btn-primary" to="/auth/login">LOGIN TO COMMENT</NuxtLink>
                                </div>
                             </div>
                        </div>

                        <panel-comment @onDelete="deleteCommentById" class="col-md-12" :comment="comment" v-for="(comment, idx) in comments" :key="idx"></panel-comment>
                    </div>

                    <div class="col-md-3 pl-2">
                        <div class="panel-subhead col-md-12 no-padding title-sidebar mb-4">
                            <div class="icon-subhead">
                                <img src="/img/icon/ic_features.png">
                            </div>
                            <div class="title-subhead">
                                <label>Features</label>
                            </div>
                        </div>
                        <div class="col-md-12 px-4 mb-3">
                            <ads-konsultasi2></ads-konsultasi2>
                        </div>
                        <div class="panel-subhead col-md-12 no-padding title-sidebar mb-4" style="margin-top: 10px;">
                            <div class="icon-subhead">
                                <img src="/img/icon/ic_related.png">
                            </div>
                            <div class="title-subhead">
                                <label>Related Questions</label>
                            </div>
                        </div>
                        <div class="col-md-12 px-0" :class="$screen.width<=990 ? 'scrollable': ''">
                            <div class="content p-2" v-for="(discus, idx) in relateThreads" :key="idx">
                                <div class="card-most" @click="$router.push(`/threads/${discus.id}`)" :class="{'text-white card-img-overlay': !discus.image, 'w-100': $screen.width>990}" :style="discus.image ? `background-image: url('${discus.image.file_url}');` : ''">
                                    <div class="p-4 mb-4">
                                        <div class="card-headtitle" :style="{'background': (discus.image ? 'rgba(255, 255, 255, 0.4)' : discus.color_bg)}">
                                            <h5 class="card-title" :style="{'color': (discus.image ? '#FFFFFF' : discus.color)}">{{ discus.name }}</h5>
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
    </div>
</template>
<script>

export default {
  head() {
    return {
        title: this.thread?.title || 'Detail Thread',
    }
  },
  layout: "default-thread",
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
  async fetch(){
    await this.$getOneThread(this.slug).then(data => {
        this.thread = data.data
    }).catch(error=>{
        return this.$nuxt.error({ statusCode: error.response.status, message:  error.response.statusText })
    })
    await this.$getComments(this.thread.id).then(data => {
        this.comments = data.data
    })
    await this.$getRelateThreads(this.thread?.id).then(data => {
        this.relateThreads = data.data
    })
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
            await this.$postComment(form, this.thread.id).then(response=>{
                this.comments.push(response.data)
                this.form.comment =''
                this.form.photos =[]
                this.form.upfiles =[]
                this.form.processing = false;
            })
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