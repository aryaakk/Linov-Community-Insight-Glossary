<template>
    <div class="row px-0 py-2">
        <div class="col-md-3 col-xs-12 ajdk" v-if="datafile">
            <div class="viewFile">
                <div>
                    <img src="/img/thread/file.svg" alt="">
                </div>
                <label class="lblNameFile">File {{ label }}</label>
                <div>
                    <label class="sizeFile">{{ fileSize }}</label>
                </div>
            </div>
        </div>
        <div :class="datafile ? 'col-md-9' : 'col-md-12'" class="col-xs-12" @dragover.prevent @drop.prevent>
            <div class="d-flex justify-content-center" @drop="prepareFile" style="background: #EDF7FF;border: 2px dashed #C2E3FE;border-radius: 4px;min-height: 84px;padding-top: 35px;">
            <input type="file" ref="file"  style="display: none" @change="prepareFile">
            Drag & drop or &nbsp;<span @click="$refs.file.click()" style="color:#76BFF0;cursor: pointer;"><u>browse</u></span>
            </div>
        </div>
    </div>
</template>

<script>
    export default {
        name: 'modal-delete',
        props: ['file', 'label'],
        computed: {
            fileSize() {
                const size = this.datafile.size;
                if(size < 1000000){
                   return Math.floor(size/1000) + 'KB';
                }else{
                  return Math.floor(size/1000000) + 'MB';  
                }
            }
        },
        data() {
            return {
                datafile: null
            }
        },
        methods: {
            prepareFile(e) {
                this.datafile = e.target.files ? e.target.files[0] : e.dataTransfer.files[0]
                this.$emit('getFile', this.datafile)
            }
        }
    }
</script>