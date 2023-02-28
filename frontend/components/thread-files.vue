<template>
    <div class="row-main-thread mt-4 row" v-if="files.length>0" style="margin-right: 0px;margin-left: 0px;">
        <div class="col-md-3 col-xs-6 ajdk" v-if="idx<4" v-for="(file, idx) in files" :key="idx" style="padding-left: 2px;">
            <a target="_blank" :href="file.file_url">
            <div class="viewFile">
                <div>
                    <img src="/img/thread/file.svg" alt="">
                </div>
                <label class="lblNameFile">File {{ idx+1 }}</label>
                <div>
                    <label class="sizeFile">{{ file.size ? file.size+' kb' : 'unknown'}}</label>
                </div>
            </div>
            </a>
        </div>
    </div>
</template>

<script>
export default {
  name: "thread-files",
  props: ["files"],
}
</script>