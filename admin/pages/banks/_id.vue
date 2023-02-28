<template>
    <div>
        <div class="card mb-4">
            <form @submit.prevent="submit">
            <h5 class="card-header">Edit Master Data</h5>
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
                    <label class="form-label">Nama Bank</label>
                    <input
                        type="text"
                        class="form-control"
                        :class="form.errors?.bank_name ? 'is-invalid' : ''"
                        placeholder="Input nama"
                        v-model="form.bank_name"
                    />
                    <div class="invalid-feedback" v-if="form.errors?.bank_name">{{ form.errors?.bank_name[0] }}</div>
                </div>
                <div class="mb-3">
                    <label class="form-label">Icon</label>
                    <input
                        type="file"
                        class="form-control"
                        :class="form.errors?.icon ? 'is-invalid' : ''"
                        placeholder="Input icon"
                        ref="icon"
                    />
                    <div class="invalid-feedback" v-if="form.errors?.icon">{{ form.errors?.icon[0] }}</div>
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
                <nuxt-link to="/banks" class="btn btn-danger mx-1"><i class='bx bx-x'></i>Batal</nuxt-link>
                <button class="btn btn-primary mx-1"><i class='bx bx-check' ></i> Update</button>
            </div>
            </form>
        </div>
    </div>
</template>

<script>
    export default {
      head: {
        title: "Edit Master Banks",
      },
      layout: "authenticated",
      data() {
        return {
            id: this.$router.currentRoute.params.id,
            form: {
                processing: false,
                errors: [],
            },
        };
      },
      async mounted(){
        const {data} = await this.$getOneBanks(this.id)

        this.form = {...this.form, ...data}
      },
      methods: {
            async submit(){
                this.form.processing = true
                this.form.errors = []
                const form = new FormData()
                form.append('_method', 'PUT')
                form.append('code', this.form.code)
                form.append('bank_name', this.form.bank_name) 
                form.append('icon', this.$refs.icon.files[0]) 
                form.append('is_active', this.form.is_active) 
                  
                await this.$updateBanks(form, this.id).then(response=>{
                    if(response.status==200){
                        this.$toast.success("Master data berhasil diupdate!");
                        this.$router.push(`/banks`)
                    }
                }).catch(e=>{
                    this.form.errors = e.response.data.errors
                    this.form.processing = false;
                })
            },
       }
    }
</script>