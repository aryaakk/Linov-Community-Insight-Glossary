<template>
    <div>
        <schedule :idx="idxSch" :data="form.schedules" @getSchedule="addSchedule"></schedule>
        <div class="card mb-4">
            <form @submit.prevent="submit">
            <h5 class="card-header">Create Event</h5>
            <div class="card-body">
                <div class="mb-3">
                    <label class="form-label">Title</label>
                    <input
                        type="text"
                        class="form-control"
                        :class="form.errors?.title ? 'is-invalid' : ''"
                        placeholder="Input nama"
                        v-model="form.title"
                    />
                    <div class="invalid-feedback" v-if="form.errors?.title">{{ form.errors?.title[0] }}</div>
                </div>
                <div class="mb-4">
                    <label class="form-label">Provider</label>
                    <v-select
                        type="text"
                        :class="form.errors?.provider_id ? 'is-invalid' : ''"
                        placeholder="Pilih Provider"
                        label="name"
                        :options="providers" 
                        :reduce="provider=>provider.id"
                        v-model="form.provider_id"
                    />
                    <div class="invalid-feedback" v-if="form.errors?.provider_id">{{ form.errors?.provider_id[0] }}</div>
                </div>
                <div class="mb-3">
                    <label class="form-label">Suitable</label>
                    <textarea 
                        class="form-control"
                        :class="form.errors?.sustainable ? 'is-invalid' : ''"
                        placeholder="Input Tagline"
                        v-model="form.sustainable" style="min-height: 50px"></textarea>
                    <div class="invalid-feedback" v-if="form.errors?.sustainable">{{ form.errors?.sustainable[0] }}</div>
                </div>
                <div class="mb-3">
                    <label class="form-label">Summary</label>
                    <froala :tag="'textarea'" :config="$defaultOpt" v-model="form.summary"></froala>
                    <div class="invalid-feedback" v-if="form.errors?.summary">{{ form.errors?.summary[0] }}</div>
                </div>
                <div class="mb-3">
                    <label class="form-label">Location</label>
                    <input
                        type="text"
                        class="form-control"
                        :class="form.errors?.location ? 'is-invalid' : ''"
                        placeholder="Input Lokasi"
                        v-model="form.location"
                    />
                    <div class="invalid-feedback" v-if="form.errors?.location">{{ form.errors?.location[0] }}</div>
                </div>
                <div class="mb-3">
                    <label class="form-label">Level</label>
                    <select class="form-select" v-model="form.level">
                        <option value="beginner">Beginner</option>
                        <option value="intermediate">Intermediate</option>
                        <option value="advanced">Advanced</option>
                    </select>
                    <div class="invalid-feedback" v-if="form.errors?.level">{{ form.errors?.level[0] }}</div>
                </div>
                <div class="mb-3">
                    <label class="form-label">Status</label>
                    <select class="form-select" v-model="form.status">
                        <option value="published">Published</option>
                        <option value="banned">Banned</option>
                        <option value="canceled">Canceled</option>
                    </select>
                    <div class="invalid-feedback" v-if="form.errors?.status">{{ form.errors?.status[0] }}</div>
                </div>
                <div class="mb-3" v-show="form.status=='published'">
                    <label class="form-label">Publish Date</label>
                    <input
                        type="date"
                        class="form-control"
                        :class="form.errors?.published_date ? 'is-invalid' : ''"
                        placeholder="Tgl Publish"
                        v-model="form.published_date"
                    />
                    <div class="invalid-feedback" v-if="form.errors?.published_date">{{ form.errors?.published_date[0] }}</div>
                </div>
                <div class="mb-4">
                    <label class="form-label">Category</label>
                    <v-select
                        type="text"
                        :class="form.errors?.type_question_id ? 'is-invalid' : ''"
                        placeholder="Pilih Category"
                        label="name"
                        :options="categoris" 
                        :reduce="category=>category.id"
                        v-model="form.type_question_id"
                    />
                    <div class="invalid-feedback" v-if="form.errors?.type_question_id">{{ form.errors?.type_question_id[0] }}</div>
                </div>
                <div class="mb-4">
                    <label class="form-label">Trainer</label>
                    <v-select
                        type="text"
                        :multiple="true"
                        :class="form.errors?.trainer_id ? 'is-invalid' : ''"
                        placeholder="Pilih Trainer"
                        label="name"
                        :options="trainers" 
                        v-model="form.trainer_id"
                    />
                    <div class="invalid-feedback" v-if="form.errors?.trainer_id">{{ form.errors?.trainer_id[0] }}</div>
                </div>
                <div class="mb-3">
                    <label class="form-label">Is Advertise</label>
                    <div class="form-check form-switch mb-2">
                    <input class="form-check-input" :class="form.errors?.is_ads ? 'is-invalid' : ''" v-model="form.is_ads" type="checkbox" id="is_active"/>
                    <label class="form-check-label" for="is_active">{{ form.is_ads ? 'Active': 'Inactive'}}</label>
                    <div class="invalid-feedback" v-if="form.errors?.is_ads">{{ form.errors?.is_ads[0] }}</div>
                    </div>
                </div>
                <div class="mb-3">
                    <label class="form-label">Num Ads</label>
                    <input
                        type="number"
                        class="form-control"
                        :class="form.errors?.num_ads ? 'is-invalid' : ''"
                        placeholder="Urutan Banner"
                        v-model="form.num_ads"
                    />
                    <div class="invalid-feedback" v-if="form.errors?.num_ads">{{ form.errors?.num_ads[0] }}</div>
                </div>
                <div class="mb-3" v-show="form.is_ads=='1'">
                    <label class="form-label">Banner Slider</label>
                    <drop-upload title="Drag & drop (1440 x 300)" @getFiles="(file)=>{ form.banner_slide=file}"></drop-upload>
                    <div class="invalid-feedback d-block" v-if="form.errors?.banner_slide">{{ form.errors?.banner_slide[0] }}</div>
                </div>
                <div class="mb-3">
                    <label class="form-label">Banner Detail</label>
                    <drop-upload title="Drag & drop (730 x 370)" @getFiles="(file)=>{ form.banner=file}"></drop-upload>
                    <div class="invalid-feedback d-block" v-if="form.errors?.banner">{{ form.errors?.banner[0] }}</div>
                </div>
                <div class="mb-3">
                    <label class="form-label">Banner Card</label>
                    <drop-upload title="Drag & drop (270 x 140)" @getFiles="(file)=>{ form.banner_card=file}"></drop-upload>
                    <div class="invalid-feedback d-block" v-if="form.errors?.banner_card">{{ form.errors?.banner_card[0] }}</div>
                </div>
                <div class="mb-3">
                    <div class="form-check form-check-inline">
                        <input class="form-check-input" v-model="form.class.type_class" type="radio" name="type_class" id="public" value="1">
                        <label class="form-check-label" for="public">
                            Public
                        </label>
                    </div>
                    <div class="form-check form-check-inline">
                        <input class="form-check-input" v-model="form.class.type_class" type="radio" name="type_class" id="inhouse" value="2">
                        <label class="form-check-label" for="inhouse">
                            In House
                        </label>
                        </div>
                </div>
                <div class="py-3">
                    <h5>Events {{ form.class.type_class=='1'? 'Public' : 'In House'}}</h5>
                    <hr>
                </div>
                <div class="mb-3">
                    <label class="form-label">Last Order</label>
                    <input
                        type="date"
                        class="form-control"
                        :class="form.errors?.['class.last_order_date'] ? 'is-invalid' : ''"
                        placeholder="Last Order"
                        v-model="form.class.last_order_date"
                    />
                    <div class="invalid-feedback" v-if="form.errors?.['class.last_order_date']">{{ form.errors?.['class.last_order_date'][0] }}</div>
                </div>
                <div class="mb-3 row">
                    <div class="col-12 col-md-6">
                        <label class="form-label">Minimum Participans</label>
                        <input
                            type="number"
                            class="form-control"
                            :class="form.errors?.['class.min_participant'] ? 'is-invalid' : ''"
                            placeholder="Min Participant"
                            v-model="form.class.min_participant"
                        />
                        <div class="invalid-feedback" v-if="form.errors?.['class.min_participant']">{{ form.errors?.['class.min_participant'][0] }}</div>
                    </div>
                    <div class="col-12 col-md-6">
                        <label class="form-label">Maximum Participants</label>
                        <input
                            type="number"
                            class="form-control"
                            :class="form.errors?.['class.max_participant'] ? 'is-invalid' : ''"
                            placeholder="Max Participant"
                            v-model="form.class.max_participant"
                        />
                        <div class="invalid-feedback" v-if="form.errors?.['class.max_participant']">{{ form.errors?.['class.max_participant'][0] }}</div>
                    </div>
                </div>
                <div class="mb-3 row">
                    <div class="col-12 col-md-6">
                        <label class="form-label">Minimum Order</label>
                        <input
                            type="number"
                            class="form-control"
                            :class="form.errors?.['class.min_order'] ? 'is-invalid' : ''"
                            placeholder="Min Participant"
                            v-model="form.class.min_order"
                        />
                        <div class="invalid-feedback" v-if="form.errors?.['class.min_order']">{{ form.errors?.['class.min_order'][0] }}</div>
                    </div>
                    <div class="col-12 col-md-6">
                        <label class="form-label">Maximum Order</label>
                        <input
                            type="number"
                            class="form-control"
                            :class="form.errors?.['class.max_order'] ? 'is-invalid' : ''"
                            placeholder="Max Participant"
                            v-model="form.class.max_order"
                        />
                        <div class="invalid-feedback" v-if="form.errors?.['class.max_order']">{{ form.errors?.['class.max_order'][0] }}</div>
                    </div>
                </div>
                <div class="mb-3 row">
                    <div class="col-6 col-md-3">
                        <label class="form-label">Kurs</label>
                        <select 
                        class="form-control" 
                        :class="form.errors?.['class.kurs_dollar'] ? 'is-invalid' : ''"
                        v-model="form.class.kurs_dollar">
                            <option value="0">IDR</option>
                            <option value="1">USD</option>
                        </select>
                        <div class="invalid-feedback" v-if="form.errors?.['class.kurs_dollar']">{{ form.errors?.['class.kurs_dollar'][0] }}</div>
                    </div>
                    <div class="col-6 col-md-3">
                        <label class="form-label">Price</label>
                        <input
                            type="number"
                            class="form-control"
                            :class="form.errors?.['class.price'] ? 'is-invalid' : ''"
                            placeholder="Price"
                            v-model="form.class.price"
                        />
                        <div class="invalid-feedback" v-if="form.errors?.['class.price']">{{ form.errors?.['class.price'][0] }}</div>
                    </div>
                    <div class="col-6 col-md-3">
                        <label class="form-label">Price Discount</label>
                        <input
                            type="number"
                            class="form-control"
                            :class="form.errors?.['class.price_discount'] ? 'is-invalid' : ''"
                            placeholder="Price"
                            :readonly="form.class.is_discount=='0'"
                            v-model="form.class.price_discount"
                        />
                        <div class="invalid-feedback" v-if="form.errors?.['class.price_discount']">{{ form.errors?.['class.price_discount'][0] }}</div>
                    </div>
                    <div class="col-6 col-md-3">
                        <label class="form-label">Discount</label>
                        <div class="form-check form-switch">
                            <input 
                            :class="form.errors?.['class.is_discount'] ? 'is-invalid' : ''"
                            v-model="form.class.is_discount"
                            class="form-check-input" type="checkbox" id="discount" value="1">
                            <label class="form-check-label" for="discount">{{ form.class.is_discount=='1' ? 'Active' : 'Inactive'}}</label>
                        </div>
                        <div class="invalid-feedback" v-if="form.errors?.['class.is_discount']">{{ form.errors?.['class.is_discount'][0] }}</div>
                    </div>
                </div>
                <div class="pt-3 d-flex justify-content-between">
                    <h5 class="d-block">Schedule {{ form.class.type_class=='1'? 'Public' : 'In House'}}</h5>
                    <span class="link" data-bs-toggle="modal" data-bs-target="#schedule"><small><i class='bx bx-plus-circle'></i>&nbsp;Tambahkan Jadwal</small></span>
                </div>
                <hr>
                <div class="mb-3 table-basic table-responsive text-nowrap">
                    <table class="table">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Start Date</th>
                                <th>End Date</th>
                                <th>Start Hour</th>
                                <th>End Hour</th>
                                <th width="120">Option</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr v-for="(schedule, idx) in form.schedules">
                                <td>{{ idx+1 }}</td>
                                <td>{{ schedule.date}}</td>
                                <td>{{ schedule.end_date}}</td>
                                <td>{{ schedule.start_hour }}</td>
                                <td>{{ schedule.end_hour }}</td>
                                <td>
                                    <span class="btn-active" @click="idxSch=idx" data-bs-toggle="modal" data-bs-target="#schedule"><i class='bx bx-edit'></i>&nbsp;Edit</span>&nbsp;
                                    <span class="btn-active" style="color: #F02929;" @click="form.schedules.splice(idx,1)"><i class='bx bx-trash'></i> &nbsp;Hapus</span>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="card-footer d-flex justify-content-end">
                <nuxt-link to="/events" class="btn btn-danger mx-1"><i class='bx bx-x'></i>Batal</nuxt-link>
                <button class="btn btn-primary mx-1"><i class='bx bx-check' ></i> Simpan</button>
            </div>
            </form>
        </div>
    </div>
</template>

<script>
import Schedule from '~/components/modals/schedule.vue';
    export default {
      head: {
        title: "Create Events",
      },
      layout: "authenticated",
      components: {
        Schedule
      },
      data() {
        return {
            idxSch:null,
            form: {
                type: 'event',
                title: null,
                provider_id: null,
                sustainable: null,
                summary: null,
                location: null,
                level: 'beginner',
                status: 'published',
                published_date: null,
                trainer_id: null,
                type_question_id: null,
                is_ads: '1',
                num_ads: 1,
                banner_slide: null,
                banner_card: null,
                banner: null,
                class:{
                    type_class: '1',
                    last_order_date: null,
                    min_participant: 1,
                    max_participant: 100,
                    max_order: 10,
                    min_order: 1,
                    kurs_dollar: '0',
                    price: null,
                    is_discount: null,
                    price_discount: 0,
                },
                schedules:[],
                processing: false,
                errors: [],
            },
            providers: [],
            categoris: [],
            trainers:[],
        };
      },
      async mounted(){
        await this.$getProviders({params: {all: true}}).then(response=>{
            this.providers = response.data
        })
        await this.$getCategories().then(response=>{
            this.categoris = response.data
        }),
        await this.$getTrainers({param: {status: '1'}}).then(response=>{
            this.trainers = response.data.data
        })
      },
      methods: {
            async submit(){
                this.form.processing = true
                this.form.errors = []
                const form = new FormData()
                const exceptData = ['trainer_id', 'class', 'schedules']
                for (const key in this.form) {
                    let data = this.form[key];
                    if(key=='summary' && data){
                        data = data.replace(`<p data-f-id="pbf" style="text-align: center; font-size: 14px; margin-top: 30px; opacity: 0.65; font-family: sans-serif;">Powered by <a href="https://www.froala.com/wysiwyg-editor?pb=1" title="Froala Editor">Froala Editor</a></p>`, '')
                    }
                    if(data && data!='null' && !exceptData.includes(key)) form.append(key, data);
                }
                for(const [key,trainer] of Object.entries(this.form.trainer_id||[])){
                    form.append(`trainer_id[${key}]`, trainer?.id)
                }
                for(const [key,value] of Object.entries(this.form.class)){
                    if(value && value!='null') form.append(`class[${key}]`, value)
                }
                for(const [key,datas] of Object.entries(this.form.schedules)){
                    for(const [key2,value] of Object.entries(datas||[])){
                        if(value && value!='null') form.append(`schedules[${key}][${key2}]`, value)
                    }
                }
                await this.$postTrainEvents(form).then(response=>{
                    if(response.status==201){
                        this.$toast.success("Data berhasil dibuat!");
                        this.$router.push(`/events`)
                    }
                }).catch(e=>{
                    this.form.errors = e.response.data.errors
                    this.form.processing = false;
                })
            },
            addSchedule(data){
                if(this.idxSch!=null){
                    this.form.schedules[this.idxSch] = data
                    this.idxSch = null
                    return
                }

                this.form.schedules.push(data)
            }
       }
    }
</script>