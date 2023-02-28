<template>
    <div>
        <modal-basic id="educations" title="Add Educations">
            <form @submit.prevent="submit">
                <div class="mb-3">
                    <label class="form-label">Universitas</label>
                    <v-select
                        type="text"
                        v-model="form.university_id"
                        placeholder="Pilih Universitas"
                        label="name"
                        taggable
                        :options="universities"
                    >
                    <template #search="{attributes, events}">
                        <input
                        class="vs__search"
                        :required="!form.university_id"
                        v-bind="attributes"
                        v-on="events"
                        />
                    </template>
                    </v-select>
                </div>
                <div class="mb-3">
                    <label class="form-label">Jurusan</label>
                    <v-select
                        type="text"
                        v-model="form.major_id"
                        placeholder="Pilih Jurusan"
                        label="name"
                        taggable
                        :options="majors" 
                    >
                    <template #search="{attributes, events}">
                        <input
                        class="vs__search"
                        :required="!form.major_id"
                        v-bind="attributes"
                        v-on="events"
                        />
                    </template>
                    </v-select>
                </div>
                <div class="mb-3">
                    <label class="form-label">Jenjang Pendidikan</label>
                    <v-select
                        type="text"
                        v-model="form.title_degree_id"
                        placeholder="Pilih Jenjang"
                        label="degree"
                        taggable
                        :options="degrees" 
                    >
                    <template #search="{attributes, events}">
                        <input
                        class="vs__search"
                        :required="!form.title_degree_id"
                        v-bind="attributes"
                        v-on="events"
                        />
                    </template>
                    </v-select>
                </div>
                <div class="mb-3 row">
                    <div class="col-6">
                        <label class="form-label">Tanggal Mulai</label>
                        <input
                            v-model="form.start_date"
                            type="date"
                            class="form-control"
                            placeholder="Input tgl mulai"
                            required
                        />
                    </div>
                    <div class="col-6">
                        <label class="form-label">Tanggal Selesai</label>
                        <input
                            v-model="form.end_date"
                            type="date"
                            class="form-control"
                            placeholder="Input tgl mulai"
                            :disabled="form.is_current"
                        />
                    </div>
                </div>
                <div class="mb-3">
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" v-model="form.is_current" id="is_current2" @change="form.is_current?form.end_date=null:''">
                        <label class="form-check-label" for="is_current2">
                            Is Current
                        </label>
                    </div>
                </div>
                <div class="py-3 text-end">
                    <button ref="educlose" type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                    <button type="submit" class="btn btn-primary">Simpan</button>
                </div>
            </form>
        </modal-basic>
    </div>
</template>
<script>
    export default {
        name: 'modals-eductaions',
        props: ['idx','data'],
        data() {
            return {
                form: {
                    university_id: null,
                    major_id: null,
                    title_degree_id: null,
                    start_date: null,
                    end_date: null,
                    is_current: false,
                    other_university: null,
                    other_major: null,
                    other_title: null,
                },
                universities: [],
                majors: [],
                degrees: [],
            }
        },
        async mounted(){
            await this.$getUniversity().then(response=>{
                this.universities = response.data
            })
            await this.$getMajors().then(response=>{
                this.majors = response.data
            })
            await this.$getDegrees().then(response=>{
                this.degrees = response.data
            })
        },
        watch: {
            'idx': function(value) {
                const data = this.data[value]
                this.form = Object.assign({},data)
                this.form.university_id = this.universities.find(univ=>univ.id==data?.title_degree_id)
                this.form.major_id = this.majors.find(major=>major.id==data?.major_id)
                this.form.title_degree_id = this.degrees.find(degree=>degree.id==data?.university_id)
            },
            'form.university_id': function(value) {
                if(!value?.id){
                    this.form.other_university = value?.name
                }
            },
            'form.major_id': function(value) {
                if(!value?.id){
                    this.form.other_major = value?.name
                }
            },
            'form.title_degree_id': function(value) {
                if(!value?.id){
                    this.form.other_title = value?.degree
                }
            },
        },
        methods:{
            submit(){
                this.$refs.educlose.click();
                this.$emit('getEducation', this.form)
                this.form = {
                        university_id: null,
                        major_id: null,
                        title_degree_id: null,
                        start_date: null,
                        end_date: null,
                        is_current: false,
                        other_university: null,
                        other_major: null,
                        other_title: null,
                }
            }
        }
    }
</script>