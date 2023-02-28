<template>
    <div>
        <social-media :idx="idxSoc" :data="form.socials" @getSocial="addSocial"></social-media>
        <experiences :idx="idxExp" :data="form.experiences" @getExperience="addExperience"></experiences>
        <educations :idx="idxEdu" :data="form.educations" @getEducation="addEducation"></educations>
        <skills :data="form.skills" @getSkills="(skills)=> { form.skills=skills}"></skills>
        <certification :idx="idxCert" :data="form.certificates" @getCertificate="addCertificate"></certification>
        <div class="card mb-4">
            <form @submit.prevent="submit">
            <h5 class="card-header">Add Consultant</h5>
            <div class="card-body row">
                <div class="py-2 col-md-6">
                    <label>Full Name</label>
                    <input v-model="form.name" type="text" class="form-control" :class="form.errors?.name ? 'is-invalid' : ''">
                    <span class="invalid-feedback" v-if="form.errors?.name">{{ form.errors?.name[0] }}</span>
                </div>
                <div class="py-2 col-md-6">
                    <label>Status</label>
                    <div class="form-check form-switch mb-2">
                        <input class="form-check-input" v-model="form.is_active" type="checkbox" id="is_active" checked>
                        <label class="form-check-label" for="is_active">Active</label>
                    </div>
                </div>
                <div class="py-2 col-md-6" >
                    <label>Place of Birth</label>
                    <input v-model="form.birthplace" type="text" class="form-control" :class="form.errors?.birthplace ? 'is-invalid' : ''">
                    <span class="invalid-feedback" v-if="form.errors?.birthplace">{{ form.errors?.birthplace[0] }}</span>
                </div>
                <div class="py-2 col-md-6" >
                    <label>Date of Birth</label>
                    <input v-model="form.birthdate"  type="date" class="form-control" :class="form.errors?.birthdate ? 'is-invalid' : ''">
                    <span class="invalid-feedback" v-if="form.errors?.birthdate">{{ form.errors?.birthdate[0] }}</span>
                </div>
                <div class="py-2 col-md-6">
                    <label>Email</label>
                    <input v-model="form.email" type="email" class="form-control">
                </div>
                <div class="py-2 col-md-6" >
                    <label>Phone Number</label>
                    <input type="text" class="form-control" v-model="form.phone" :class="form.errors?.phone ? 'is-invalid' : ''">
                    <span class="invalid-feedback" v-if="form.errors?.phone">{{ form.errors?.phone[0] }}</span>
                </div>
                <div class="py-2 col-md-6" >
                    <label>Country</label>
                    <v-select
                        type="text"
                        :class="form.errors?.country_id ? 'is-invalid' : ''"
                        placeholder="Pilih negara"
                        label="name"
                        :options="countries" 
                        :reduce="country => country.id"
                        v-model="form.country_id"
                    />
                    <span class="invalid-feedback" v-if="form.errors?.country_id">{{ form.errors?.country_id[0] }}</span>
                </div>
                <div class="py-2 col-md-6">
                    <label>Province</label>
                    <v-select
                        type="text"
                        :class="form.errors?.state_id ? 'is-invalid' : ''"
                        placeholder="Pilih provinsi"
                        label="name"
                        :options="states" 
                        :reduce="state => state.id"
                        v-model="form.state_id"
                    />
                    <span class="invalid-feedback" v-if="form.errors?.state_id">{{ form.errors?.state_id }}</span>
                </div>
                <div class="py-2 col-md-6">
                    <label>City</label>
                    <v-select
                        type="text"
                        :class="form.errors?.city_id ? 'is-invalid' : ''"
                        placeholder="Pilih Kota"
                        label="name"
                        :options="cities" 
                        :reduce="city => city.id"
                        v-model="form.city_id"
                    />
                    <span class="invalid-feedback" v-if="form.errors?.city_id">{{ form.errors?.city_id[0] }}</span>
                </div>
                <div class="py-2 col-md-6">
                    <label>Postal Code</label>
                    <input v-model="form.postal_code" type="text" class="form-control" :class="form.errors?.postal_code ? 'is-invalid' : ''">
                    <span class="invalid-feedback" v-if="form.errors?.postal_code">{{ form.errors?.postal_code[0]}}</span>
                </div>
                <div class="py-2 col-md-12">
                    <label>Spesialisasi / bidang yang diajukan</label>
                    <v-select
                        type="text"
                        :class="form.errors?.category ? 'is-invalid' : ''"
                        placeholder="Pilih Bidang"
                        label="name"
                        :options="categories" 
                        :reduce="cat => cat.id"
                        v-model="form.category"
                    />
                    <span class="invalid-feedback" v-if="form.errors?.category">{{ form.errors?.category[0] }}</span>
                </div>
                <div class="py-2 col-md-12" :class="form.errors?.about_me ? 'is-invalid' : ''">
                    <label>About Me</label>
                    <textarea v-model="form.about_me" class="form-control" rows="3"></textarea>
                    <span class="invalid-feedback" v-if="form.errors?.about_me">{{ form.errors?.about_me[0] }}</span>
                </div>
                <div class="mb-4">
                    <div class="d-flex pt-2 justify-content-between">
                        <label class="form-label">Social Media</label>
                        <span class="btn-active" data-bs-toggle="modal" data-bs-target="#social-media"><i class='bx bx-plus-circle'></i> Tambahkan</span>
                    </div>
                    <hr class="my-2">
                    <ul class="list-group">
                        <li class="list-group-item d-flex justify-content-between" v-for="(socmed, idx) in form.socials" :key="idx">
                            <span>{{ socmed.url }}</span>
                            <div>
                                <span class="btn-active" @click="idxSoc=idx" data-bs-toggle="modal" data-bs-target="#social-media"><i class='bx bxs-edit'></i></span>
                                <span class="btn-active" @click="form.socials.splice(idx,1)" style="color: #E74357;"><i class='bx bx-trash'></i></span>
                            </div>
                        </li>
                    </ul>
                    <div class="invalid-feedback" v-if="form.errors?.socials">{{ form.errors?.socials[0] }}</div>
                </div>
                <div class="mb-4">
                    <div class="d-flex pt-2 justify-content-between">
                        <label class="form-label">Experiences</label>
                        <span class="btn-active" data-bs-toggle="modal" data-bs-target="#experiences"><i class='bx bx-plus-circle'></i> Tambahkan</span>
                    </div>
                    <hr class="my-2">
                    <ul class="list-group">
                        <li class="list-group-item d-flex justify-content-between" v-for="(exp, idx) in form.experiences" :key="idx">
                            <div>
                                <h5>{{ exp.company}}</h5>
                                <span>{{ exp.position}}</span><br>
                                <small>{{ $dayjs(exp.start_date).format('MMM-YYYY') }}&nbsp;-&nbsp;{{ $dayjs(exp.end_date).format('MMM-YYYY') }}</small>
                            </div>
                            <div>
                                <span class="btn-active" @click="idxExp=idx" data-bs-toggle="modal" data-bs-target="#experiences"><i class='bx bxs-edit'></i></span>
                                <span @click="form.experiences.splice(idx, 1)" class="btn-active" style="color: #E74357;"><i class='bx bx-trash'></i></span>
                            </div>
                        </li>
                    </ul>
                    <div class="invalid-feedback" v-if="form.errors?.experiences">{{ form.errors?.experiences[0] }}</div>
                </div>
                <div class="mb-4">
                    <div class="d-flex pt-2 justify-content-between">
                        <label class="form-label">Educations</label>
                        <span class="btn-active" data-bs-toggle="modal" data-bs-target="#educations"><i class='bx bx-plus-circle'></i> Tambahkan</span>
                    </div>
                    <hr class="my-2">
                    <ul class="list-group">
                        <li class="list-group-item d-flex justify-content-between" v-for="(edu, idx) in form.educations" :key="idx">
                            <div>
                                <h5>{{ edu.university_id?.name || edu.other_university }}</h5>
                                <span>{{  edu.major_id?.name || edu.other_major }} - {{  edu.title_degree_id?.degree || edu.other_title }}</span><br>
                                <small>{{ $dayjs(edu.start_date).format('MMM-YYYY') }}&nbsp;-&nbsp;{{ $dayjs(edu.end_date).format('MMM-YYYY') }}</small>
                            </div>
                            <div>
                                <span class="btn-active" @click="idxEdu=idx" data-bs-toggle="modal" data-bs-target="#educations"><i class='bx bxs-edit'></i></span>
                                <span class="btn-active" style="color: #E74357;" @click="form.educations.splice(idx,1)"><i class='bx bx-trash'></i></span>
                            </div>
                        </li>
                    </ul>
                    <div class="invalid-feedback" v-if="form.errors?.educations">{{ form.errors?.educations[0] }}</div>
                </div>
                <div class="mb-4">
                    <div class="d-flex pt-2 justify-content-between">
                        <label class="form-label">Skills</label>
                        <div>
                            <span class="btn-active" data-bs-toggle="modal" data-bs-target="#skills"><i class='bx bx-plus-circle'></i> Tambahkan</span>
                            <span class="btn-active" data-bs-toggle="modal" data-bs-target="#skills"><i class='bx bx-edit'></i> Edit</span>
                        </div>
                    </div>
                    <hr>
                    <div>
                        <span class="badge bg-secondary px-1" v-for="(skill, idx) in form.skills">
                            <span class="badge bg-secondary">
                            {{ skill }} <span class="px-1"></span>
                            </span>
                        </span>
                    </div>
                </div>
                <div class="mb-4">
                    <div class="d-flex pt-2 justify-content-between">
                        <label class="form-label">Certifications & Licenses</label>
                        <span class="btn-active" data-bs-toggle="modal" data-bs-target="#certificate"><i class='bx bx-plus-circle'></i> Tambahkan</span>
                    </div>
                    <hr class="my-2">
                    <ul class="list-group">
                        <li class="list-group-item d-flex justify-content-between" v-for="(cert, idx) in form.certificates" :key="idx">
                            <div>
                                <h5>{{ cert.title }} - {{ cert.organization }}</h5>
                                <span>{{  cert.link  }}</span><br>
                                <small>{{ $dayjs(cert.start_date).format('MMM-YYYY') }}&nbsp;-&nbsp;{{ $dayjs(cert.end_date).format('MMM-YYYY') }}</small>
                            </div>
                            <div>
                                <span class="btn-active" @click="idxCert=idx" data-bs-toggle="modal" data-bs-target="#certificate"><i class='bx bxs-edit'></i></span>
                                <span class="btn-active" style="color: #E74357;" @click="form.certificates.splice(idx,1)"><i class='bx bx-trash'></i></span>
                            </div>
                        </li>
                    </ul>
                </div>
            </div>
            <div class="card-footer d-flex justify-content-end">
                <nuxt-link to="/consultant" class="btn btn-danger mx-1"><i class='bx bx-x'></i>Batal</nuxt-link>
                <button :disabled="form.processing" class="btn btn-primary mx-1"><i class='bx bx-check' ></i> Simpan</button>
            </div>
            </form>
        </div>
    </div>
</template>

<script>
import Educations from '~/components/modals/educations.vue';
import Experiences from '~/components/modals/experiences.vue';
import SocialMedia from '~/components/modals/social-media.vue';
import Skills from '~/components/modals/skills.vue';
import Certification from '~/components/modals/certification.vue'

export default {
      components: { Experiences, SocialMedia, Educations, Skills, Certification },
      head: {
        title: "Add Consultant",
      },
      layout: "authenticated",
      data() {
        return {
            countries : [],
            states : [],
            cities : [],
            categories : [],
            idxSoc: null,
            idxExp: null,
            idxEdu: null,
            idxCert:null,
            form: {
                country_id:null,
                state_id: null,
                educations: [],
                experiences: [],
                socials: [],
                skills: [],
                certificates: [],
                processing: false,
                errors: []
            },
        };
      },
      async mounted(){
        await this.$getRegCountry({}).then((response)=>{
            this.countries = response.data;
        });
        await this.$getCategories().then((response)=>{
                this.categories = response.data;
        });
      },
      watch : {
            'form.country_id':function(val) {
                this.$getRegState(this.form.country_id).then((response)=>{
                    this.states = response.data;
                });
            },
            'form.state_id':function(val) {
                this.$getRegCity({state_id: this.form.state_id, country_id: this.form.country_id})
                .then((response)=>{
                    this.cities = response.data;
                    // this.form.city=null
                });
            },
      },
      methods: {
            async submit(){
                if(!confirm("Are you sure?")){
                    return
                }
                this.form.processing = true
                this.form.errors = []
                
                const except= ['country_id', 'state_id','educations','experiences','socials','skills','certificates', 'is_active'];
                const form = new FormData()
                for (const key in this.form) {
                    if(except.includes(key)) continue;
                    let data = this.form[key];
                    if(data) form.append(key, data);
                }
                form.append('is_active', this.form.is_active?'1':'0')
                for(const key in this.form.socials){
                    form.append(`socials[${key}][social_media_id]`, key);
                    form.append(`socials[${key}][url]`, this.form.socials[key].url);
                }
                for(const key in this.form.skills){
                    let data= this.form.skills[key];
                    form.append(`skills[${key}]`,data);
                }
                for(const key in this.form.experiences){
                    for(const idx in this.form.experiences[key]){
                    let data= this.form.experiences[key][idx];
                    form.append(`experiences[${key}][${idx}]`,data);
                    }
                }
                for(const key in this.form.educations){
                    for(const idx in this.form.educations[key]){
                    let data= this.form.educations[key][idx];
                    if(Object.prototype.toString.call(data) === '[object Object]'){
                        data = data?.id
                    }
                    if(data!=null){
                    form.append(`educations[${key}][${idx}]`,data);
                    }
                    }
                }
                for(const key in this.form.certificates){
                    for(const idx in this.form.certificates[key]){
                    let data= this.form.certificates[key][idx];
                    form.append(`certificates[${key}][${idx}]`,data);
                    }
                }
                
                await this.$postConsultant(form).then(response=>{
                    this.form.processing = false;
                    this.$toast.success('Consultant created successfully');
                    this.$router.push('/consultant');
                }).catch(e=>{
                    this.form.errors = e.response.data.errors
                    this.form.processing = false;
                })
            },
            addEducation(data){
                if(this.idxEdu!=null){
                    this.form.educations[this.idxEdu] = data
                    this.idxEdu = null
                    return
                }

                this.form.educations.push(data)
            },
            addSocial(data){
                if(this.idxSoc!=null){
                    this.form.socials[this.idxSoc] = data
                    this.idxSoc = null
                    return
                }

                this.form.socials.push(data)
            },
            addExperience(data){
                if(this.idxExp!=null){
                    this.form.experiences[this.idxExp] = data
                    this.idxExp = null
                    return
                }

                this.form.experiences.push(data)
            },
            addCertificate(data){
                if(this.idxCert!=null){
                    this.form.certificates[this.idxCert] = data
                    this.idxCert = null
                    return
                }

                this.form.certificates.push(data)
            }
       }
    }
</script>