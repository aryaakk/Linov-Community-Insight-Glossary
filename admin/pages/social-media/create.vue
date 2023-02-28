<template>
    <div>
        <div class="card mb-4">
            <form @submit.prevent="submit">
            <h5 class="card-header">Create Master Data</h5>
            <div class="card-body">
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
                    <label class="form-label">Icon</label>
                    <input
                        type="text"
                        class="form-control"
                        :class="form.errors?.icon ? 'is-invalid' : ''"
                        placeholder="Input icon"
                        v-model="form.icon"
                    />
                    <div class="invalid-feedback" v-if="form.errors?.icon">{{ form.errors?.icon[0] }}</div>
                </div>
                <div class="mb-3">
                    <label class="form-label">URL</label>
                    <input
                        type="text"
                        class="form-control"
                        :class="form.errors?.url ? 'is-invalid' : ''"
                        placeholder="Input url"
                        v-model="form.url"
                    />
                    <div class="invalid-feedback" v-if="form.errors?.url">{{ form.errors?.url[0] }}</div>
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
                <nuxt-link to="/social-media" class="btn btn-danger mx-1"><i class='bx bx-x'></i>Batal</nuxt-link>
                <button class="btn btn-primary mx-1"><i class='bx bx-check' ></i> Simpan</button>
            </div>
            </form>
        </div>
    </div>
</template>

<script>
    export default {
      head: {
        title: "Create Master Social Media",
      },
      layout: "authenticated",
      data() {
        return {
            form: {
                name: null,
                icon: null,
                url: null,
                is_active: true,
                processing: false,
                errors: [],
            },
        };
      },
      methods: {
            async submit(){
                this.form.processing = true
                this.form.errors = []

                await this.$postSocials(this.form).then(response=>{
                    if(response.status==201){
                        this.$toast.success("Master data berhasil dibuat!");
                        this.$router.push(`/social-media`)
                    }
                }).catch(e=>{
                    this.form.errors = e.response.data.errors
                    this.form.processing = false;
                })
            },
       }
    }
</script>