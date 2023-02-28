<template>
    <div class="row px-0 py-2">
        <div class="col-md-4 mb-1 col-xs-12" v-if="datafiles">
            <ul class="list-group">
                <li class="list-group-item d-flex justify-content-between" v-for="(file, idx) in datafiles" :key="idx">
                    <img v-if="file.type.includes('image')" :src="$imageUrl(file)" class="file-drop-image">
                    <span v-else>{{ file.name }}</span>
                    <span>{{ $sizeOf(file) }}</span>
                </li>
            </ul>
        </div>
        <div :class="datafiles ? 'col-md-8' : 'col-md-12'" class="mb-1 col-xs-12" @dragover.prevent @drop.prevent>
            <div class="text-center file-drop-zone" @drop="prepareFile">
            <input type="file" :multiple="mutiple" ref="file"  style="display: none" @change="prepareFile">
            <label for="" class="py-4">{{ title ? title : 'Drag & drop'}} or&nbsp;<span @click="$refs.file.click()" class="file-drop-browse"><u>browse</u></span></label>
            </div>
        </div>
    </div>
</template>
<style>
    .file-drop-zone{
        background: #EDF7FF;
        border: 2px dashed #C2E3FE;
        border-radius: 4px;
    }
    .file-drop-browse{
        color:#76BFF0;
        cursor: pointer;
    }
    .file-drop-image{
        height: 60px;
        width: 70%;
        object-fit: cover;
    }
</style>
<script>
    export default {
        name: 'drop-upload',
        props: ['mutiple','title'],
        data() {
            return {
                datafiles: null
            }
        },
        methods: {
            prepareFile(e) {
                this.datafiles = e.target.files ? e.target.files : e.dataTransfer.files
                this.$emit('getFiles', this.mutiple ? this.datafiles : this.datafiles[0])
            }
        }
    }
</script>