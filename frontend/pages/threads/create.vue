<template>
    <div>
        <div class="main-home  mt-5">
          <div class="container">
            <div class="row">
                <div class="col-md-9">
                    <div class="panel-subhead col-md-12">
                        <div class="title-subhead subhead-page">
                            <label> <NuxtLink to="/">Back To Feed</NuxtLink> > New Post</label>
                        </div>
                    </div>

                    <div class="panel-subhead col-md-12 title-form">
                        <div class="icon-subhead">
                            <img src="/img/icon/ic_thread.png">
                        </div>
                        <div class="title-subhead">
                            <label>Create Threads & Discuss</label>
                        </div>
                    </div>
                    
                    <div class="panel-subhead col-md-12">
                        <div class="main-thread page-form">
                            <div class="row-ava">
                                <h4>Detail Thread</h4>
                            </div>
                            
                            <div class="row-main-thread">
                                <form enctype="multipart/form-data" class="form-thread" @submit.prevent="postThread">
                                    <div class="form-group" :class="errors?.title ? 'has-error' : ''">
                                        <label for="txtTitle" class="labellinov">Title</label>
                                        <textarea class="form-control text-thread" v-model="form.title" cols="30" rows="2" placeholder="write your question here "></textarea>
                                        <span class="help-block" v-if="errors?.title">{{ errors?.title[0]}}</span>
                                    </div>
                                    
                                    <div class="form-group"  :class="errors?.description? 'has-error' : ''">
                                        <label for="txtDesc" class="labellinov">Description</label>
                                        <textarea class="form-control text-thread" v-model="form.description" cols="30" rows="10" placeholder="whats the context of your question? "></textarea>
                                        <span class="help-block" v-if="errors?.description">{{ errors?.description[0] }}</span>
                                    </div>
                                    
                                    <div class="form-group" :class="errors?.question_id ? 'has-error' : ''">
                                        <label for="txtKategori" class="labellinov">Kategori</label>
                                        <br>
                                        <client-only>
                                        <multiselect name="question_id" openDirection="bottom" v-model="form.question_id" :max="2" :multiple="true" track-by="id" label="name" :options="categories" :close-on-select="false" :clear-on-select="false" />
                                        </client-only>
                                        <span class="help-block" v-if="errors?.question_id">{{ errors?.question_id[0]}}</span>
                                        <br>
                                        <small>Pilihan yang pertama akan dijadikan main topic</small>
                                    </div>

                                    <div class="form-group" v-if="form.photos.length>0" :class="$_errors(errors, 'photos') ? 'has-error' : ''">
                                        <label for="" class="labellinov">Photo</label>
                                        <list-photos :photos="form.photos"></list-photos>
                                        <span class="help-block" v-if="$_errors(errors, 'photos')">{{ $_errors(errors, 'photos')}}</span>
                                    </div>

                                    <div class="form-group" v-if="form.upfiles.length>0" :class="$_errors(errors, 'upfiles') ? 'has-error' : ''">
                                        <label for="" class="labellinov">File</label>
                                        <list-files :files="form.upfiles"></list-files>
                                        <span class="help-block" v-if="$_errors(errors, 'upfiles')">{{ $_errors(errors, 'upfiles')}}</span>
                                    </div>

                                    <div class="form-group" v-if="polling.description&&!pollingActive">
                                        <label for="" class="labellinov">Poll</label>
                                        <div class="post-poll-created" style="min-height: 200px;">
                                            <div class="post-poll">
                                                <h4>{{ polling.description }}</h4>
                                                <div class="opsi-poll"  v-for="(option, id) in polling.options" :key="id">
                                                    <p>{{ option }}</p>
                                                </div>
                                                <div class="footer-vote">
                                                    <span>Until {{ duration }}</span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div v-show="imageUpload"  @dragover.prevent @drop.prevent>
                                        <hr>
                                        <div class="text-center">
                                          <h4><b>Upload Your Foto</b></h4>
                                          <p>file should .jpg .png. svg</p>
                                        </div>
                                        <input type="file" ref="photo" @change="prepareUploadPhotos" style="display: none">
                                        <div @drop="prepareUploadPhotos" @click="$refs.photo.click()" class="m-3 d-flex justify-content-center" style="background: #EDF7FF;border: 2px dashed #C2E3FE;border-radius: 4px;min-height: 200px;">
                                            <img src="/img/icon/ic_photo.svg" style="max-width: 50px">
                                        </div>
                                    </div>
                                    <div v-show="fileUpload" @dragover.prevent @drop.prevent>
                                        <hr>
                                        <div class="text-center">
                                        <h4><b>Upload Your File</b></h4>
                                        <p>file should .docx .pdf</p>
                                        </div>
                                        <input type="file" ref="file" @change="prepareUploadFiles" style="display: none">
                                        <div @drop="prepareUploadFiles"  @click="$refs.file.click()" class="m-3 d-flex justify-content-center" style="background: #EDF7FF;border: 2px dashed #C2E3FE;border-radius: 4px;min-height: 200px;">
                                            <img src="/img/icon/ic_file.svg" style="max-width: 50px" >
                                        </div>
                                    </div>
                                    <div v-show="pollingActive">
                                        <hr>
                                        <div class="text-center">
                                            <h4 >Add Poll</h4>
                                        </div>
                                        <div class="form-group">
                                            <label for="txtDescPoll" class="labellinov">Description</label>
                                            <textarea v-model="polling.description" class="form-control text-thread" cols="30" rows="4" placeholder="Describe"></textarea>
                                        </div>

                                        <div class="form-group">
                                            <label class="labellinov">Poll</label>
                                            <div class="row-main-thread">
                                                <div class="post-poll-created">
                                                    <div class="input-poll-name" v-for="(option, idx) in polling.options" :key="idx">
                                                        <input v-model="polling.options[idx]" type="text" class="form-control text-poll">
                                                        <span style="position:absolute;right:10px;top:6px" @click="idx==polling.options.length-1 ? polling.options.push(''): polling.options.splice(idx,1)"><i class="fas " :class="{'fa-minus-circle': idx!=polling.options.length-1, 'fa-plus-circle': idx==polling.options.length-1}"></i></span>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="form-group option-button">
                                            <label class="labellinov title-duration mb-2">Duration</label>
                                            <span v-for="(duration, idx) in durations" :key="idx">
                                            <input v-model="polling.duration" type="radio" :id="`radio_${idx}`" name="radios" :value="duration.id">
                                            <label :for="`radio_${idx}`">{{  duration.name }}</label>
                                            </span>
                                        </div>
                                        <div class="form-group text-right pt-4">
                                            <button type="button" class="btn btn-primary" @click="pollingActive=false">Done</button>
                                        </div>
                                    </div>
                                    <div class="row py-4">
                                      <div class="col-md-8 py-2 col-sm-12" style="font-size: 20px;">
                                        <span @click="imageUpload=!imageUpload;fileUpload=false;pollingActive=false" class="mr-4" v-tooltip.top="{ content: 'Photo' }">
                                            <img src="/img/icon/ic_small_photo.svg" alt="">
                                        </span>
                                        <span @click="fileUpload=!fileUpload;imageUpload=false;pollingActive=false" class="mr-4" v-tooltip.top="{ content: 'Files' }">
                                        <img src="/img/icon/ic_small_file.svg" alt="">
                                        </span>
                                        <span @click="pollingActive=!pollingActive;imageUpload=false;fileUpload=false" class="mr-4" v-tooltip.top="{ content: 'Poll' }">
                                        <img src="/img/icon/ic_small_polling.svg" alt="">
                                        </span>
                                      </div>
                                      <div class="col-md-4 py-4 col-sm-12">
                                        <button :disabled="form.processing" type="submit" class="btn btn-primary btn-block"><i class="fas fa-file-alt"></i> Create Thread</button>
                                      </div>
                                    </div>
                                </form>
                            </div>
                            
                        </div>
                    </div>
                </div>
                <div class="col-md-3 pl-4">
                    <div class="panel-subhead col-md-12 no-padding title-sidebar">
                        <div class="icon-subhead">
                            <img src="/img/icon/ic_features.png">
                        </div>
                        <div class="title-subhead">
                            <label>Features</label>
                        </div>
                    </div>
                    <div class="col-md-12 px-4" style="padding-left: 0; padding-right: 0; margin-bottom: 15px">
                        <ads-konsultasi></ads-konsultasi>
                    </div>

                    <div class="col-md-12 px-0">
                        <div class="card p-4 qna-wording">
                            <table class="w-100">
                                <tbody>
                                    <tr data-toggle="collapse" data-target="#qna1">
                                        <td> <h4>Don't Know What To Ask?</h4></td>
                                        <td width="10" style="vertical-align: center;"><i class="fas fa-chevron-right"></i></td>
                                    </tr>
                                    <tr id="qna1" class="collapse p3">
                                        <td colspan="2">
                                            <p>Apa saja yang Anda bisa lakukan di Komunitas LinovHR?</p>
                                        </td>
                                    </tr>
                                    <tr data-toggle="collapse" data-target="#qna2">
                                        <td> <h4>Ask Career Advice</h4></td>
                                        <td width="10" style="vertical-align: center;"><i class="fas fa-chevron-right"></i></td>
                                    </tr>
                                    <tr id="qna2" class="collapse">
                                        <td colspan="2"><p>
                                        Tell the community about difficulties or obstacles you face on your daily job.
                                        Experts here are happy to give advice and help you solve them</p>
                                        </td>
                                    </tr>
                                    <tr data-toggle="collapse" data-target="#qna3">
                                        <td> <h4>Discover Job Hunting Tips And Tricks</h4></td>
                                        <td width="10" style="vertical-align: center;"><i class="fas fa-chevron-right"></i></td>
                                    </tr>
                                    <tr id="qna3" class="collapse">
                                        <td colspan="2"><p>
                                        Are you feeling nervous about an upcoming job interview or do you need help with
                                        your resume? Don't be afraid to ask for tips and tricks from more experienced members of the 
                                        community</p>
                                        </td>
                                    </tr>
                                    <tr data-toggle="collapse" data-target="#qna4">
                                        <td> <h4>Try Our Poll Feature</h4></td>
                                        <td width="10"><i class="fas fa-chevron-right"></i></td>
                                    </tr>
                                    <tr id="qna4" class="collapse">
                                        <td colspan="2"><p>
                                            Polls is a great tool to crowdsource ideas. By asking questions with polls you can get direct
                                feedback from users who ara knowledgeable about the topic</p>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                            <p>
                                Before you post, make sure to browse the site first to make sure your questions hasn't been
                                answered
                            </p>
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
  head: {
    title: "Create Thread",
  },
  layout: "default-thread",
  middleware: 'authenticated',
  data() {
    return {
      form : {
        title : '',
        description: '',
        question_id: null,
        upfiles: [],
        photos: [],
        processing: false,
      },
      polling: {
        description: null,
        options: [''],
        duration: null,
      },
      categories: [],
      durations: [],
      fileUpload: false,
      imageUpload: false,
      pollingActive: false,
      errors: [],
    }
  },
  computed: {
    duration(){
        return this.durations[this.polling.duration]?.name
    }
  },
 async mounted() {
    await this.$getCategories().then(data=>{
        this.categories = data.data
    })
    await this.$getPollDuration().then(data=>{
        this.durations = data.data
    })
  },
  methods: {
    prepareUploadPhotos(e){
        const append = e.target.files ? e.target.files : e.dataTransfer.files
        this.form.photos = [...this.form.photos, ...append];
    },
    prepareUploadFiles(e){
        const append = e.target.files ? e.target.files : e.dataTransfer.files
        this.form.upfiles = [...this.form.upfiles, ...append];
    },
    async postThread(){
        this.form.processing = true;
        this.form.errors = [];

        const form = new FormData()
        form.append('title', this.form.title)
        form.append('description', this.form.description)
        for(const idx in this.form.question_id){
            form.append(`question_id[${idx}]`, this.form.question_id[idx].id)
        }
        this.form.photos.forEach((foto, idx) => {
            form.append(`photos[${idx}]`, foto)
        });
        this.form.upfiles.forEach((file, idx) => {
            form.append(`upfiles[${idx}]`, file)
        });
        if(this.polling.description){
            form.append('polling', JSON.stringify(this.polling))
        }
        await this.$postThread(form).then(response=>{
            if(response.status==201){
                this.$toast.success("Thread berhasil dibuat!");
                this.$router.push(`/threads/${response.data.slug_id}`)
            }
        }).catch(e=>{
            this.errors = e.response.data.errors
            this.form.processing = false;
            this.$toast.error('An error occured!');
        })
    }
  }
}
</script>