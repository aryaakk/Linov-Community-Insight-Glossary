<template>
<div>
    <div v-if="articles.length>0" class="panel-subhead  pl-3 mt-4 mb-4">
        <div class="icon-subhead">
            <img src="/img/icon/ic_features.png">
        </div>
        <div class="title-subhead w-100 px-2 d-flex justify-content-between">
            <label>Insight</label>
            <NuxtLink to="/insight">See All</NuxtLink>
        </div>
    </div>      
    <div class="px-2 " :class="$screen.width<=990 ? 'scrollable': ''">
        <div class="content py-2" v-for="(article, idx) in articles" :key="idx">
            <div class="ads-article card" :class="$screen.width>990? 'w-100': ''">
            <NuxtLink :to="`/insight/${article.slug_id}`">
            <img class="w-100" style="box-shadow: none; height: 139px;object-fit: cover;" :src="article.banner_url">
            
            <div class="detail-train article-post pt-0">
                <label class="red2">{{ article.title }}</label>
            </div>
            
            <div class="detail-train parent-post-article">
                <label class="red user-post-article mb-0">Post By {{ article.author?.name }} </label>
            </div>
            <div class="detail-train parent-post-article">
                <label class="red user-post-article2">{{ article.human_date }}</label>
            </div>
            </NuxtLink>
            </div>
        </div>
    </div>
</div>
</template>

<script>
export default {
  name: "ads-article",
  data() {
    return {
      articles : []
    }
  },
  async mounted(){
    await this.$getArticle({params: {perpage: 3}}).then(response=>{
        this.articles = response.data.data
    })
  },
  methods: {

  },
}
</script>