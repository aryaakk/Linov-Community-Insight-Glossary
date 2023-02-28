<template>
    <div class="post-poll-created row mt-2" v-if="files.length>0" style="margin-left: 0px;margin-right: 0px;">
        <div class="col-md-3 col-sm-3 col-xs-6 ajdk text-center" v-for="(file, idx) in files" :key="idx">
        <div style="background: #F0F0F0;padding-top: 28px;border-radius: 4px;min-height: 106px;">
        <svg width="37" height="37" viewBox="0 0 37 37" fill="none" xmlns="http://www.w3.org/2000/svg">
        <path d="M20.041 3.08301H9.24935C8.4316 3.08301 7.64734 3.40786 7.0691 3.9861C6.49087 4.56433 6.16602 5.34859 6.16602 6.16634V30.833C6.16602 31.6508 6.49087 32.435 7.0691 33.0133C7.64734 33.5915 8.4316 33.9163 9.24935 33.9163H27.7494C28.5671 33.9163 29.3514 33.5915 29.9296 33.0133C30.5078 32.435 30.8327 31.6508 30.8327 30.833V13.8747L20.041 3.08301Z" stroke="#626262" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
        <path d="M20.041 3.08301V13.8747H30.8327" stroke="#626262" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
        </svg>
        </div>
        <p><b>{{ file.name }}</b></p>
        <span><i @click="files.splice(idx, 1)" style="text-shadow: 1px 1px 4px #000000;" class="fas fa-times-circle remove-photo cursor"></i></span>
        </div>
    </div>
</template>

<script>
export default {
  name: "list-files",
  props: ["files"],
}
</script>