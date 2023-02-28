<template>
    <div class="post-poll-created row mt-2" v-if="photos.length>0" style="min-height: 150px;margin-right: 0px;margin-left: 0px;">
        <div class="col-md-3 col-sm-3 col-xs-6 ajdk" v-for="(photo, idx) in photos" :key="idx">
        <img class="thumbnail" style="width: 100%;height: 100px;" :src="$getPreview(photo)" loading="lazy"/>
        <span @click="photos.splice(idx, 1)" style="text-shadow: 1px 1px 4px #000000;"><i class="fas fa-times-circle remove-photo cursor"></i></span>
        </div>
    </div>
</template>

<script>
export default {
  name: "list-photos",
  props: ["photos"],
}
</script>