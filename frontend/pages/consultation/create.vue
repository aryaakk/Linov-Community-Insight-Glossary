<template>
    <div>
        <div class="main-home">
          <div class="container">
            <div class="row">
                <div class="panel-subhead col-md-12">
                        <div class="title-subhead subhead-page ml-4">
                            <label><NuxtLink to="/consultation">Back To Feed</NuxtLink> > New Post</label>
                        </div>
                </div>
                <div class="col-md-9">
                    <div class="panel-subhead col-md-12 title-form">
                        <div class="icon-subhead">
                            <img src="/img/icon/ic_thread.png">
                        </div>
                        <div class="title-subhead">
                            <label>Create Threads & Discuss</label>
                        </div>
                    </div>

                    <div class="col-md-12 mt-3 mb-4">
                        <div class="px-3" style="background-image: url('/img/wording_bg.png');background-size: cover;background-repeat:no-repeat;border-radius: 10px;">
                        <h4 class="text-white text-center" style="padding-top: 2rem;padding-bottom: 2rem">Ceritakan Permasalahan Karir dan Pekerjaan Anda di Bawah Ini!</h4>
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
                                        <textarea class="form-control text-thread" v-model="form.title" cols="20" rows="2" placeholder="Describe"></textarea>
                                        <span class="help-block" v-if="errors?.title">{{ errors?.title[0]}}</span>
                                    </div>
                                    
                                    <div class="form-group" :class="errors?.description ? 'has-error' : ''">
                                        <label for="txtDesc" class="labellinov">Description</label>
                                        <textarea class="form-control text-thread" v-model="form.description" cols="30" rows="10" placeholder="Describe"></textarea>
                                        <span class="help-block" v-if="errors?.description">{{ errors?.description[0] }}</span>
                                    </div>
                                    
                                    <div class="form-group" :class="errors?.category_id ? 'has-error' : ''">
                                        <label class="labellinov">Category</label>
                                        <br>
                                        <client-only>
                                        <multiselect name="category_id" openDirection="bottom" v-model="form.category_id" track-by="id" label="name" :options="types" :close-on-select="false" :clear-on-select="false">
                                        </multiselect>
                                        </client-only>
                                        <span class="help-block" v-if="errors?.category_id">{{ errors?.category_id[0] }}</span>
                                        <br>
                                        <small>Pilihan yang pertama akan dijadikan main topic</small>
                                    </div>

                                    <div class="form-group" :class="errors?.user_consul_id ? 'has-error' : ''">
                                        <label class="labellinov">Consultant</label>
                                        <br>
                                        <client-only>
                                        <multiselect name="user_consul_id" openDirection="bottom" v-model="form.user_consul_id" track-by="id" label="name" :options="consultants" :close-on-select="false" :clear-on-select="false">
                                            <template slot="singleLabel" slot-scope="props">
                                                <div class="d-flex">
                                                    <img class="img-circle" :src="props.option.profile?.photo ? props.option.profile.photo_url : '/img/home/user.png'" style="width: 45px;height: 45px;object-fit: cover;">
                                                    <div class="pl-3 pt-2">
                                                        <span><b>{{ props.option.name }}</b></span><br>
                                                        <small>{{ props.option.category?.name}}</small>
                                                    </div>
                                                </div>
                                            </template>
                                            <template slot="option" slot-scope="props">
                                                <div class="d-flex">
                                                    <img class="img-circle" :src="props.option?.profile?.photo ? props.option.profile.photo_url : '/img/home/user.png'" style="width: 45px;height: 45px;object-fit: cover;">
                                                    <div class="pl-3 pt-2">
                                                        <span><b>{{ props.option.name }}</b></span><br>
                                                        <small>{{ props.option.category?.name}}</small>
                                                    </div>
                                                </div>
                                            </template>
                                        </multiselect>
                                        </client-only>
                                        <span class="help-block" v-if="errors?.user_consul_id">{{ errors?.user_consul_id }}</span>
                                    </div>

                                    <div class="form-group" v-if="form.photos.length>0"  :class="$_errors(errors, 'photos') ? 'has-error' : ''">
                                        <label for="" class="labellinov">Photo</label>
                                        <list-photos :photos="form.photos"></list-photos>
                                        <span class="help-block" v-if="$_errors(errors, 'photos')">{{ $_errors(errors, 'photos')}}</span>
                                    </div>

                                    <div class="form-group" v-if="form.upfiles.length>0" :class="$_errors(errors, 'upfiles') ? 'has-error' : ''">
                                        <label for="" class="labellinov">File</label>
                                        <list-files :files="form.upfiles"></list-files>
                                        <span class="help-block" v-if="$_errors(errors, 'upfiles')">{{ $_errors(errors, 'upfiles.0')}}</span>
                                    </div>

                                     <div v-show="imageUpload"  @dragover.prevent @drop.prevent>
                                        <hr>
                                        <div class="text-center">
                                          <h4><b>Upload Your Foto</b></h4>
                                          <p>file should .jpg .png. svg</p>
                                        </div>
                                        <input type="file" ref="photo" @change="prepareUploadPhotos" style="display: none">
                                        <div @drop="prepareUploadPhotos" @click="$refs.photo.click()" class="m-3 d-flex justify-content-center" style="background: #EDF7FF;border: 2px dashed #C2E3FE;border-radius: 4px;min-height: 200px;">
                                            <img src="/img/icon/ic_photo.svg" style="max-width: 50px" >
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
                                    <div class="row py-4">
                                      <div class="col-md-8 py-2 col-sm-12" style="font-size: 20px;">
                                        <span @click="imageUpload=!imageUpload;fileUpload=false;" class="mr-4" v-tooltip.top="{ content: 'Photo' }">
                                        <img src="/img/icon/ic_small_photo.svg" alt="">
                                        </span>
                                        <span @click="fileUpload=!fileUpload;imageUpload=false;" class="mr-4" v-tooltip.top="{ content: 'Files' }">
                                         <img src="/img/icon/ic_small_file.svg" alt="">
                                        </span>
                                      </div>
                                      <div class="col-md-4 py-2">
                                        <button :disabled="form.processing" type="submit" class="btn btn-primary btn-block"><i class="fas fa-file-alt"></i> Create Thread</button>
                                      </div>
                                    </div>
                                </form>
                            </div>
                            
                        </div>
                    </div>
                </div>
                <div class="col-md-3 pl-4">
                    <div>
                        <ads-konsultant></ads-konsultant>
                    </div>
                    <div class="panel-subhead col-md-12 title-form">
                        <div class="icon-subhead">
                            <img src="/img/icon/ic_features.png">
                        </div>
                        <div class="title-subhead">
                            <label>Features</label>
                        </div>
                    </div>

                    <div class="col-md-12 px-4">
                        <ads-konsultasi2></ads-konsultasi2>
                    </div>
                    <div class="col-md-12 px-0 pt-4">
                    <ads-article ></ads-article>
                    </div>
                </div>
            </div>
          </div>
        </div>
    </div>
</template>
<script>
import adsArticle from '~/components/ads-article.vue'
export default {
  components: { adsArticle },
  head: {
    title: "Create Consultation",
  },
  layout: "default",
  middleware: 'authenticated',
  data() {
    return {
      form : {
        title : '',
        description: '',
        category_id: null,
        user_consul_id: null,
        upfiles: [],
        photos: [],
        processing: false,
      },
      types: [],
      consultants: [],
      users: [],
      errors: [],
      fileUpload: false,
      imageUpload: false,
    }
  },
 async mounted() {
    await this.$getCategories().then(data=>{
        this.types = data.data
    })

    await this.$getConsultants().then(data=>{
        this.consultants = data.data
        let id = this.$route.query.consultant
        this.form.user_consul_id = this.consultants.find(user=>{ return user.id==id})
    })
  },
  watch:{
    '$route.query.consultant': function (id) {
        this.form.user_consul_id = this.consultants.find(user=>{ return user.id==id})
    },
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
        form.append('user_consul_id', this.form.user_consul_id?.id)
        form.append('category_id', this.form.category_id?.id)

        this.form.photos.forEach((foto, idx) => {
            form.append(`photos[${idx}]`, foto)
        });
        this.form.upfiles.forEach((file, idx) => {
            form.append(`upfiles[${idx}]`, file)
        });

        await this.$postConsultThread(form).then(response=>{
            if(response.status==201){
                this.$toast.success("Thread konsultasi berhasil di buat");
                this.$router.push(`/consultation/${response.data.slug_id}`)
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