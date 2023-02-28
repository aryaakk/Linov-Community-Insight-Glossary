<template>
    <div>
        <social-media :idx="idxSoc" :data="form.socials" @getSocial="addSocial"></social-media>
        <experiences :idx="idxExp" :data="form.experiences" @getExperience="addExperience"></experiences>
        <educations :idx="idxEdu" :data="form.educations" @getEducation="addEducation"></educations>
        <skills :data="form.skills" @getSkills="(skills)=> { form.skills=skills}"></skills>
        <certification :idx="idxCert" :data="form.certificates" @getCertificate="addCertificate"></certification>

        <div class="card trainer mb-4">
            <form @submit.prevent="submit">
            <h5 class="card-header">Create Master Data</h5>
            <div class="card-body">
                <div class="mb-3">
                    <label class="form-label">Code</label>
                    <input
                        type="text"
                        class="form-control"
                        :class="form.errors?.code ? 'is-invalid' : ''"
                        placeholder="Input code"
                        v-model="form.code"
                    />
                    <div class="invalid-feedback" v-if="form.errors?.code">{{ form.errors?.code[0] }}</div>
                </div>
                <div class="mb-3">
                    <label class="form-label">Nama Lengkap</label>
                    <input
                        type="text"
                        class="form-control"
                        :class="form.errors?.name ? 'is-invalid' : ''"
                        placeholder="Input nama"
                        v-model="form.name"
                    />
                    <div class="invalid-feedback" v-if="form.errors?.name">{{ form.errors?.name[0] }}</div>
                </div>
                <div class="mb-3">
                    <label class="form-label">Moto Professional</label>
                    <textarea 
                        class="form-control"
                        :class="form.errors?.motto ? 'is-invalid' : ''"
                        placeholder="Input Moto"
                        v-model="form.motto" style="min-height: 50px"></textarea>
                    <div class="invalid-feedback" v-if="form.errors?.motto">{{ form.errors?.motto[0] }}</div>
                </div>
                <div class="mb-3">
                    <label class="form-label">About Me</label>
                    <textarea 
                        class="form-control"
                        :class="form.errors?.description ? 'is-invalid' : ''"
                        placeholder="Input Moto"
                        v-model="form.description" style="min-height: 50px"></textarea>
                    <div class="invalid-feedback" v-if="form.errors?.description">{{ form.errors?.description[0] }}</div>
                </div>
                <div class="mb-4">
                    <label class="form-label">Provider</label>
                    <v-select
                        type="text"
                        :multiple="true"
                        :class="form.errors?.provider_id ? 'is-invalid' : ''"
                        placeholder="Pilih provider"
                        label="name"
                        :options="providers" 
                        v-model="form.providers"
                    />
                    <div class="invalid-feedback" v-if="form.errors?.provider_id">{{ form.errors?.provider_id[0] }}</div>
                </div>
                <div class="mb-4">
                    <div class="d-flex pt-2 justify-content-between">
                        <label class="form-label">Social Media</label>
                        <span class="btn-active"  @click="idxSoc=null" data-bs-toggle="modal" data-bs-target="#social-media"><i class='bx bx-plus-circle'></i> Tambahkan</span>
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
                        <span class="btn-active"  @click="idxExp=null" data-bs-toggle="modal" data-bs-target="#experiences"><i class='bx bx-plus-circle'></i> Tambahkan</span>
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
                        <span class="btn-active" @click="idxEdu=null" data-bs-toggle="modal" data-bs-target="#educations"><i class='bx bx-plus-circle'></i> Tambahkan</span>
                    </div>
                    <hr class="my-2">
                    <ul class="list-group">
                        <li class="list-group-item d-flex justify-content-between" v-for="(edu, idx) in form.educations" :key="idx">
                            <div>
                                <h5>{{ (edu.university_id?.name ? edu.university_id?.name : edu.university_name) || edu.other_university }}</h5>
                                <span>
                                    {{  (edu.major_id?.name ? edu.major_id?.name : edu.major_name) || edu.other_major }} - 
                                    {{  (edu.title_degree_id?.degree ? edu.title_degree_id?.degree : edu.degree_name) || edu.other_title }}</span><br>
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
                        <span class="px-1" v-for="(skill, idx) in form.skills">
                            <span class="badge bg-secondary ">
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
                <div class="mb-3">
                    <label class="form-label">Photo</label>
                    <drop-upload @getFiles="(file)=>{ form.photo=file}"></drop-upload>
                    <div class="invalid-feedback d-block" v-if="form.errors?.photo">{{ form.errors?.photo[0] }}</div>
                </div>
                <div class="mb-3">
                    <label class="form-label">Status</label>
                    <div class="form-check form-switch mb-2">
                    <input class="form-check-input" :class="form.errors?.is_active ? 'is-invalid' : ''" v-model="form.is_active" type="checkbox" id="is_active"/>
                    <label class="form-check-label" for="is_active">{{ form.is_active ? 'Active': 'Inactive'}}</label>
                    <div class="invalid-feedback" v-if="form.errors?.is_active">{{ form.errors?.is_active[0] }}</div>
                    </div>
                </div>
            </div>
            <div class="card-footer d-flex justify-content-end">
                <nuxt-link to="/trainers" class="btn btn-danger mx-1"><i class='bx bx-x'></i>Batal</nuxt-link>
                <button class="btn btn-primary mx-1"><i class='bx bx-check' ></i> Update</button>
            </div>
            </form>
        </div>
    </div>
</template>
<style>
    .btn-active{
        font-size: 0.75rem;
        text-transform: uppercase;
        cursor: pointer;
        color: #62B6EE;
    }
</style>
<script>
import Educations from '~/components/modals/educations.vue';
import Experiences from '~/components/modals/experiences.vue';
import SocialMedia from '~/components/modals/social-media.vue';
import Skills from '~/components/modals/skills.vue';
import Certification from '~/components/modals/certification.vue'

export default {
    components: { Experiences, SocialMedia, Educations, Skills, Certification},
      head: {
        title: "Create Master Trainer",
      },
      layout: "authenticated",
      data() {
        return {
            id: this.$router.currentRoute.params.id,
            form: {
                code: null,
                name: null,
                motto: null,
                photo: null,
                description: null,
                is_active: true,
                skills: [],
                educations: [],
                experiences: [],
                certificates: [],
                providers: [],
                socials: [],
                processing: false,
                errors: [],
            },
            providers: [],
            idxSoc: null,
            idxExp: null,
            idxEdu: null,
            idxCert:null,
        };
      },
      async mounted(){
        await this.$getProviders({params: {all: true}}).then(response=>{
            this.providers = response.data
        })
        await this.$getOneTrainers(this.id).then(response=>{
            this.form = {...this.form, ...response.data,...{photo: null, is_active: response.data.is_active=='1'}}
        })
      },
      methods: {
            async submit(){
                this.form.processing = true
                this.form.errors = []
                const form = new FormData()
                form.append('_method', 'PUT')
                for (const key in this.form) {
                    let data = this.form[key];
                    if(typeof this.form[key] == 'object' && key!='photo'){
                        data = JSON.stringify(this.form[key]);
                    }
                    if(data && data!='null') form.append(key, data);
                }

                await this.$updateTrainers(form, this.id).then(response=>{
                    if(response.status==200){
                        this.$toast.success("Trainer berhasil diupdate!");
                        this.$router.push(`/trainers`)
                    }
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