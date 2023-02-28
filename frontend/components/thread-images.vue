<template>
    <div>
        <div class="lb-modal" v-if="showImage">
            <button class="lb-modal-close" style="background-color:transparent" @click="showImage=null">
            <i class="fas far fa-times"></i>
            </button> 
            <button class="lb-modal-prev"  v-show="showIdx>0" @click="goPrev()">
                <i class="fas far fa-angle-left fa-2x"></i>
            </button> 
            <button class="lb-modal-next" v-show="showIdx<(images.length-1)" @click="goNext()">
                <i class="fas far fa-angle-right fa-2x"></i>
            </button> 
            <div class="lb-modal-img">
                <img :src="showImage"> 
            </div>
        </div>
        <div class="row-main-thread mt-4" v-if="images.length>0" style="display: flex;flex-wrap: wrap;">
            <div class="col-md-3 col-xs-6 p-1" v-if="idx<4" v-for="(img, idx) in images" :key="img.id">
                <div  :class="{'last-post-img': idx==3 && images.length>4}">
                    <label v-if="idx==3 && images.length>4">{{ images.length-4 }}+</label>
                    <img :src="img.file_url" @click="showImage=`${img.file_url}`;showIdx=idx" class="post-thread" loading="lazy" format="webp" />
                </div>
            </div>
        </div>
    </div>
</template>

<script>
export default {
  name: "thread-images",
  props: ["images"],
  data() {
    return {
        showImage: null,
        showIdx: 0,
    }
  },
  methods: {
    goNext(){
        this.showImage=`${this.images[this.showIdx+1].file_url}`
        this.showIdx++
    },
    goPrev(){
        this.showImage=`${this.images[this.showIdx-1].file_url}`
        this.showIdx--
    }
  }
}
</script>