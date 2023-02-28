<template>
    <div>
        <div class="card mb-4">
            <form @submit.prevent="submit">
            <h5 class="card-header">Edit Master Data</h5>
            <div class="card-body">
                <div class="mb-3">
                    <label class="form-label">Pilih Negara</label>
                    <v-select
                        type="text"
                        :class="form.errors?.phone_code_id ? 'is-invalid' : ''"
                        placeholder="Pilih negara"
                        label="name"
                        :options="country" 
                        :reduce="country => country.id"
                        v-model="form.phone_code_id"
                    />
                    <div class="invalid-feedback" v-if="form.errors?.code">{{ form.errors?.code[0] }}</div>
                </div>
                <div class="mb-3">
                    <label class="form-label">Pilih Provinsi</label>
                    <v-select
                        type="text"
                        :class="form.errors?.state_id ? 'is-invalid' : ''"
                        placeholder="Pilih provinsi"
                        label="name"
                        :options="state" 
                        :reduce="state => state.id"
                        v-model="form.state_id"
                    />
                    <div class="invalid-feedback" v-if="form.errors?.code">{{ form.errors?.code[0] }}</div>
                </div>
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
                    <label class="form-label">Nama</label>
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
                    <label class="form-label">Status</label>
                    <div class="form-check form-switch mb-2">
                    <input class="form-check-input" :class="form.errors?.is_active ? 'is-invalid' : ''" v-model="form.is_active" type="checkbox" id="is_active"/>
                    <label class="form-check-label" for="is_active">{{ form.is_active ? 'Active': 'Inactive'}}</label>
                    <div class="invalid-feedback" v-if="form.errors?.is_active">{{ form.errors?.is_active[0] }}</div>
                    </div>
                </div>
            </div>
            <div class="card-footer d-flex justify-content-end">
                <nuxt-link to="/city" class="btn btn-danger mx-1"><i class='bx bx-x'></i>Batal</nuxt-link>
                <button class="btn btn-primary mx-1"><i class='bx bx-check' ></i> Update</button>
            </div>
            </form>
        </div>
    </div>
</template>

<script>
    export default {
      head: {
        title: "Edit Master City",
      },
      layout: "authenticated",
      data() {
        return {
            id: this.$router.currentRoute.params.id,
            form: {
                phone_code_id: null,
                state_id: null,
                processing: false,
                errors: [],
            },
            country: [],
            state: []
        };
      },
      async mounted(){
        await this.$getOneCity(this.id).then(response=>{
            this.form = {...this.form, ...response.data}
            console.log(this.form)
        })
        await this.$getRegCountry().then(response=>{
            this.country = response.data
        }),
        await this.$getRegState(this.form.phone_code_id).then(response=>{
            this.state = response.data
        })
      },
      watch : {
        'form.phone_code_id':function(val) {
            this.$getRegState(val).then((response)=>{
                this.state = response.data;
            });
        },
      },
      methods: {
            async submit(){
                this.form.processing = true
                this.form.errors = []

                await this.$updateCity(this.form, this.id).then(response=>{
                    if(response.status==200){
                        this.$toast.success("Master data berhasil diupdate!");
                        this.$router.push(`/city`)
                    }
                }).catch(e=>{
                    this.form.errors = e.response.data.errors
                    this.form.processing = false;
                })
            },
       }
    }
</script>