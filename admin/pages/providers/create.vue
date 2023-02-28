<template>
    <div>
        <div class="card mb-4">
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
                    <label class="form-label">Nama Provider</label>
                    <input
                        type="text"
                        class="form-control"
                        :class="form.errors?.name ? 'is-invalid' : ''"
                        placeholder="Input nama"
                        v-model="form.name"
                    />
                    <div class="invalid-feedback" v-if="form.errors?.name">{{ form.errors?.name[0] }}</div>
                </div>
                <div class="mb-4">
                    <label class="form-label">Location</label>
                    <v-select
                        type="text"
                        :class="form.errors?.state_id ? 'is-invalid' : ''"
                        placeholder="Pilih Lokasi"
                        label="name"
                        :options="locations" 
                        :reduce="state=>state.id"
                        v-model="form.state_id"
                    />
                    <div class="invalid-feedback" v-if="form.errors?.state_id">{{ form.errors?.state_id[0] }}</div>
                </div>
                <div class="mb-3">
                    <label class="form-label">Tagline</label>
                    <textarea 
                        class="form-control"
                        :class="form.errors?.tagline ? 'is-invalid' : ''"
                        placeholder="Input Tagline"
                        v-model="form.tagline" style="min-height: 50px"></textarea>
                    <div class="invalid-feedback" v-if="form.errors?.tagline">{{ form.errors?.tagline[0] }}</div>
                </div>
                <div class="mb-3">
                    <label class="form-label">Summary</label>
                    <textarea 
                        class="form-control"
                        :class="form.errors?.summary ? 'is-invalid' : ''"
                        placeholder="Input Summary"
                        v-model="form.summary" style="min-height: 50px"></textarea>
                    <div class="invalid-feedback" v-if="form.errors?.summary">{{ form.errors?.summary[0] }}</div>
                </div>
                <div class="mb-3">
                    <label class="form-label">Provider Picture</label>
                    <drop-upload title="Drag & drop (100 x 100)" @getFiles="(file)=>{ form.logo=file}"></drop-upload>
                    <div class="invalid-feedback d-block" v-if="form.errors?.logo">{{ form.errors?.logo[0] }}</div>
                </div>
                <div class="mb-3">
                    <label class="form-label">Banner Picture</label>
                    <drop-upload title="Drag & drop (1024 x 100)" @getFiles="(file)=>{ form.logo_2=file}"></drop-upload>
                    <div class="invalid-feedback d-block" v-if="form.errors?.logo_2">{{ form.errors?.logo_2[0] }}</div>
                </div>
                <div class="mb-3">
                    <label class="form-label">Status Providers</label>
                    <div class="form-check form-switch mb-2">
                    <input class="form-check-input" :class="form.errors?.is_active ? 'is-invalid' : ''" v-model="form.is_active" type="checkbox" id="is_active"/>
                    <label class="form-check-label" for="is_active">{{ form.is_active ? 'Active': 'Inactive'}}</label>
                    <div class="invalid-feedback" v-if="form.errors?.is_active">{{ form.errors?.is_active[0] }}</div>
                    </div>
                </div>
            </div>
            <div class="card-footer d-flex justify-content-end">
                <nuxt-link to="/providers" class="btn btn-danger mx-1"><i class='bx bx-x'></i>Batal</nuxt-link>
                <button class="btn btn-primary mx-1"><i class='bx bx-check' ></i> Simpan</button>
            </div>
            </form>
        </div>
    </div>
</template>

<script>
    export default {
      head: {
        title: "Create Master Provider",
      },
      layout: "authenticated",
      data() {
        return {
            form: {
                code: null,
                state_id: null,
                name: null,
                tagline: null,
                summary: null,
                logo: null,
                logo_2:null,
                is_active: true,
                processing: false,
                errors: [],
            },
            locations: []
        };
      },
      async mounted(){
        await this.$getState({params: {all: true}}).then(response=>{
            this.locations = response.data
        })
      },
      methods: {
            async submit(){
                this.form.processing = true
                this.form.errors = []
                const form = new FormData()
                for (const key in this.form) {
                    let data = this.form[key];
                    if(data && data!='null') form.append(key, data);
                }  

                await this.$postProviders(form).then(response=>{
                    if(response.status==201){
                        this.$toast.success("Master data berhasil dibuat!");
                        this.$router.push(`/providers`)
                    }
                }).catch(e=>{
                    this.form.errors = e.response.data.errors
                    this.form.processing = false;
                })
            },
       }
    }
</script>