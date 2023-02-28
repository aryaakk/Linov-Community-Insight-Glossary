<template>
    <div>
        <div class="card mb-4">
            <form @submit.prevent="submit">
            <h5 class="card-header">Create Insight</h5>
            <div class="card-body row">
                <div class="col-9">
                    <div class="mb-3">
                        <label class="form-label">Title</label>
                        <input
                            type="text"
                            class="form-control"
                            :class="form.errors?.title ? 'is-invalid' : ''"
                            placeholder="Input title"
                            v-model="form.title"
                        />
                        <div class="invalid-feedback" v-if="form.errors?.title">{{ form.errors?.title[0] }}</div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Content</label>
                        <froala :tag="'textarea'" :config="$defaultOpt" v-model="form.detail"></froala>
                        <div class="invalid-feedback" v-if="form.errors?.detail">{{ form.errors?.detail[0] }}</div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Heading Image</label>
                        <drop-upload @getFiles="(file)=>{ form.banner=file}"></drop-upload>
                        <div class="invalid-feedback d-block" v-if="form.errors?.banner">{{ form.errors?.banner[0] }}</div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Meta Title</label>
                        <input
                            type="text"
                            class="form-control"
                            :class="form.errors?.meta_title ? 'is-invalid' : ''"
                            placeholder="Input meta title"
                            v-model="form.meta_title"
                        />
                        <div class="invalid-feedback" v-if="form.errors?.meta_title">{{ form.errors?.meta_title[0] }}</div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Meta Url</label>
                        <input
                            type="text"
                            class="form-control"
                            :class="form.errors?.slug_id ? 'is-invalid' : ''"
                            placeholder="Input meta url"
                            v-model="form.slug_id"
                        />
                        <div class="invalid-feedback" v-if="form.errors?.slug_id">{{ form.errors?.slug_id[0] }}</div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Meta Description</label>
                        <textarea class="form-control" v-model="form.meta_desc" :class="form.errors?.meta_desc ? 'is-invalid' : ''"></textarea>
                        <div class="invalid-feedback" v-if="form.errors?.meta_desc">{{ form.errors?.meta_desc[0] }}</div>
                    </div>
                </div>
                <div class="col-3">
                    <div class="mb-3">
                        <label class="form-label">Category</label>
                        <v-select
                        type="text"
                        :multiple="true"
                        :class="form.errors?.category ? 'is-invalid' : ''"
                        placeholder="Pilih Category"
                        label="name"
                        v-model="form.category"
                        :options="categoris"
                        :reduce="category=>category.name"
                        />
                        <div class="invalid-feedback" v-if="form.errors?.category">{{ form.errors?.category[0] }}</div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Visibility</label>
                        <select class="form-select" v-model="form.trx_code">
                            <option value="ART-INT">Public</option>
                            <option value="ART-PRE">Limited</option>
                        </select>
                        <div class="invalid-feedback" v-if="form.errors?.trx_code">{{ form.errors?.trx_code[0] }}</div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Status</label>
                        <select class="form-select" v-model="form.status">
                            <option value="drafted">Draft</option>
                            <option value="published">Published</option>
                        </select>
                        <div class="invalid-feedback" v-if="form.errors?.status">{{ form.errors?.status[0] }}</div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Publish Date</label>
                        <input
                            type="datetime-local"
                            class="form-control"
                            :class="form.errors?.published_date ? 'is-invalid' : ''"
                            v-model="form.published_date"
                        />
                        <div class="invalid-feedback" v-if="form.errors?.published_date">{{ form.errors?.published_date[0] }}</div>
                    </div>
                    <div class="py-3">
                        <div class="form-check form-check-inline">
                            <input v-model="form.open_discuss" class="form-check-input" type="checkbox" id="comment" value="1">
                            <label class="form-check-label" for="comment">Open Discuss</label>
                        </div>
                        <div class="form-check form-check-inline">
                            <input v-model="form.is_ads" class="form-check-input" type="checkbox" id="ads" value="1">
                            <label class="form-check-label" for="ads">Is Ads</label>
                        </div>
                    </div>
                    <div class="d-flex justify-content-between">
                        <button type="button" class="btn btn-secondary" @click="goPreview"> Preview</button>
                        <button class="btn btn-primary">Post</button>
                    </div>
                </div>
            </div>
            </form>
        </div>
    </div>
</template>

<script>
import { Editor } from '@toast-ui/vue-editor'

export default {
      head: {
        title: "Create Data Article",
      },
      components: {
            'editor': Editor
      },
      layout: "authenticated",
      data() {
        return {
            form: {
                slug_id: null,
                trx_code: 'ART-INT',
                title: null,
                detail: null,
                banner:null,
                is_ads: null,
                open_discuss: null,
                num_ads:null,
                status: 'drafted',
                published_date: null,
                category: null,
                tags: null,
                meta_title : null,
                meta_desc : null,
                processing: false,
                errors: [],
            },
            categoris: []
        };
      },
      async mounted(){
        await this.$getCategories().then(response=>{
            this.categoris = response.data
        })
      },
      methods: {
        async goPreview(){
                const form = new FormData();
                for (const key in this.form) {
                    let data = this.form[key];
                    if(key=='detail' && data){
                        data = data.replace(`<p data-f-id="pbf" style="text-align: center; font-size: 14px; margin-top: 30px; opacity: 0.65; font-family: sans-serif;">Powered by <a href="https://www.froala.com/wysiwyg-editor?pb=1" title="Froala Editor">Froala Editor</a></p>`, '')
                    }
                    if(data && data!='null') form.append(key, data);
                }  
                let idxcat = 0
                for(const value of this.form.category||[]){
                    form.append(`category[${idxcat}]`, value)
                    idxcat++
                } 
                await this.$previewInsight(form, this.id).then(response=>{
                    if(response.status==200){
                        window.open(response.data.url, "_blank");
                    }
                }).catch(e=>{
                    this.form.errors = e.response.data.errors
                    this.form.processing = false;
                })
            },
            async submit(){
                this.form.processing = true
                this.form.errors = []
                const form = new FormData();
                for (const key in this.form) {
                    let data = this.form[key];
                    if(key=='detail' && data){
                        data = data.replace(`<p data-f-id="pbf" style="text-align: center; font-size: 14px; margin-top: 30px; opacity: 0.65; font-family: sans-serif;">Powered by <a href="https://www.froala.com/wysiwyg-editor?pb=1" title="Froala Editor">Froala Editor</a></p>`, '')
                    }
                    if(key=='is_ads' || key=='open_discuss'){
                        data = data ? '1' : '0'
                    }
                    if(data && data!='null') form.append(key, data);
                }   
                let idxcat = 0
                for(const value of this.form.category||[]){
                    form.append(`category[${idxcat}]`, value)
                    idxcat++
                }
                await this.$postInsight(form).then(response=>{
                    if(response.status==201){
                        this.$toast.success("Data insight berhasil dibuat!");
                        this.$router.push(`/insight`)
                    }
                }).catch(e=>{
                    this.form.errors = e.response.data.errors
                    this.form.processing = false;
                })
            },
       }
    }
</script>