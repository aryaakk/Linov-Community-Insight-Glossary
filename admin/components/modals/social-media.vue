<template>
    <div>
        <modal-basic id="social-media" title="Add Social Media">
            <form @submit.prevent="submit">
                <div class="mb-3">
                    <label class="form-label">Social Media</label>
                    <v-select
                        type="text"
                        v-model="form.social_media_id"
                        placeholder="Pilih Social Media"
                        label="name"
                        :options="socials" 
                        :reduce="social => social.id">
                        <template #search="{attributes, events}">
                            <input
                            class="vs__search"
                            :required="!form.social_media_id"
                            v-bind="attributes"
                            v-on="events"
                            />
                        </template>
                    </v-select>
                </div>
                <div class="mb-3">
                    <label class="form-label">Username / Link</label>
                    <input
                        v-model="form.url"
                        type="text"
                        class="form-control"
                        required
                        placeholder="Input code"
                    />
                </div>
                <div class="py-3 text-end">
                    <button type="button" ref="socclose" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                    <button type="submit" class="btn btn-primary">Simpan</button>
                </div>
            </form>
        </modal-basic>
    </div>
</template>
<script>
    export default {
        name: 'modals-social-media',
        props: ['data', 'idx'],
        data() {
            return {
                form: {
                    social_media_id:null,
                    url: null
                },
                socials: []
            }
        },
        async mounted(){
            await this.$getSocials().then(response=>{
                this.socials = response.data
            })
        },
        watch: {
            'idx': function(value) {
                this.form = Object.assign({},this.data[value])
            },
        },
        methods: {
            submit(){
                this.$refs.socclose.click();
                this.$emit('getSocial', Object.assign({},this.form))
                this.form = {
                    social_media_id:null,
                    url: null
                }
            }
        }
    }
</script>