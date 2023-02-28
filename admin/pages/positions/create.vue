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
                <nuxt-link to="/positions" class="btn btn-danger mx-1"><i class='bx bx-x'></i>Batal</nuxt-link>
                <button class="btn btn-primary mx-1"><i class='bx bx-check' ></i> Simpan</button>
            </div>
            </form>
        </div>
    </div>
</template>

<script>
    export default {
      head: {
        title: "Create Master Positions",
      },
      layout: "authenticated",
      data() {
        return {
            form: {
                code: null,
                name: null,
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

                await this.$postPositions(this.form).then(response=>{
                    if(response.status==201){
                        this.$toast.success("Master data berhasil dibuat!");
                        this.$router.push(`/positions`)
                    }
                }).catch(e=>{
                    this.form.errors = e.response.data.errors
                    this.form.processing = false;
                })
            },
       }
    }
</script>