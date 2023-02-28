<template>
  <div>
    <div class="banner-article">
      <div class="container">
        <h1>Discover New Knowledge Everyday</h1>
        <div
          class="d-flex justify-content-center pt-4 pb-4"
          style="position: relative"
        >
          <input
            @keyup.enter="searchArticle()"
            type="search"
            v-model="params.search"
            class="form-control searchbox mt-4"
            name="query"
            placeholder="Search"
          />
          <span
            @click="searchArticle()"
            class="search-text mt-4 cursor"
            style="position: absolute; top: 25px; right: 24%"
            ><i class="fa fa-search size-icon"></i
          ></span>
        </div>
      </div>
    </div>

    <div class="main-home">
      <div class="container">
        <div class="row" ref="articles">
          <div class="col-md-8 col-sm-12">
            <div class="panel-subhead mt-4">
              <div class="icon-subhead">
                <img src="/img/icon/ic_most.png" />
              </div>
              <div class="title-subhead">
                <label>Premium Insight</label>
              </div>
            </div>
            <div class="row pt-4 px-4">
              <div
                v-for="(banner, idx) in banners"
                :key="idx"
                @click="redirectTo(`/insight/${banner.slug_id}`)"
                :class="
                  idx == 0
                    ? 'main-article-banner col-md-12'
                    : 'second-article-banner col-md-4'
                "
                :style="{
                  'background-image': `linear-gradient(rgba(0, 0, 0, 0.2), rgba(0, 0, 0, 0.5)), url('${banner.banner_url}')`,
                }"
              >
                <h2 v-if="idx == 0">
                  <strong>{{ banner.title }}</strong>
                </h2>
                <h5 v-else>
                  <strong>{{ banner.title }}</strong>
                </h5>
              </div>
            </div>
          </div>
          <div class="col-md-4 col-sm-12">
            <div class="panel-subhead mt-4">
              <div class="icon-subhead">
                <img src="/img/icon/ic_most.png" />
              </div>
              <div class="title-subhead">
                <label>Popular Insight</label>
              </div>
            </div>
            <div class="col-md-12 pt-4 px-1 article-pagination">
              <div class="py-3" v-for="(popular, idx) in populars" :key="idx">
                <NuxtLink :to="`/insight/${popular.slug_id}`">
                  <div class="card">
                    <h5>
                      <strong>{{ popular.title }}</strong>
                    </h5>
                    <p>
                      <small>Posted by {{ popular.author?.name }}</small
                      ><br />
                      <small style="color: #bfbfbf">{{
                        popular.human_date
                      }}</small>
                    </p>
                  </div>
                </NuxtLink>
              </div>
            </div>
          </div>

          <div class="col-md-12">
            <div class="panel-subhead mt-4 pt-4">
              <div class="icon-subhead">
                <img src="/img/icon/ic_most.png" />
              </div>
              <div class="title-subhead">
                <label>New Insight</label>
              </div>
            </div>
            <div class="row pt-4 px-0 article-pagination">
              <div
                class="col-md-3 py-3 px-3"
                v-for="(internal, idx) in articles.slice(0, 4)"
                :key="idx"
              >
                <div class="card h-100">
                  <NuxtLink :to="`/insight/${internal.slug_id}`">
                    <div>
                      <img :src="internal.banner_url" class="article-image" />
                    </div>
                    <div>
                      <h4>
                        <strong>{{ internal.title }}</strong>
                      </h4>
                      <p v-if="internal.category">
                        <span
                          v-for="(category, idx) in internal.category"
                          :key="idx"
                          class="label label-default mr-1"
                          >{{ category }}</span
                        >
                      </p>
                      <p v-else><span>&nbsp;</span></p>
                      <p>
                        <small>Posted by {{ internal.author?.name }}</small
                        ><br />
                        <small style="color: #bfbfbf">{{
                          internal.human_date
                        }}</small>
                      </p>
                    </div>
                  </NuxtLink>
                </div>
              </div>
            </div>
          </div>

          <div class="col-md-9 col-sm-12 mt-4" ref="content">
            <div class="panel-subhead mt-4 mb-4">
              <div class="icon-subhead">
                <img src="/img/icon/ic_most.png" />
              </div>
              <div class="title-subhead">
                <label>Insight</label>
              </div>
            </div>

            <div class="article-pagination">
              <div
                class="py-3"
                v-for="(article, idx) in articles.slice(4)"
                :key="idx"
              >
                <NuxtLink :to="`/insight/${article.slug_id}`">
                  <div class="card row p-0">
                    <div class="col-md-4 p-4">
                      <img :src="article.banner_url" class="article-image" />
                    </div>
                    <div class="col-md-8">
                      <h4>
                        <strong>{{ article.title }}</strong>
                      </h4>
                      <p v-if="article.category">
                        <span
                          v-for="(category, idx) in article.category"
                          :key="idx"
                          class="label label-default mr-1"
                          >{{ category }}</span
                        >
                      </p>
                      <p>{{ article.detail }}</p>
                      <p>
                        <small>Posted by {{ article.author?.name }}</small
                        ><br />
                        <small style="color: #bfbfbf">{{
                          article.human_date
                        }}</small>
                      </p>
                    </div>
                  </div>
                </NuxtLink>
              </div>
              <div
                v-show="loading"
                class="d-flex justify-content-center mt-4 mb-4"
              >
                <img
                  src="/img/loader.gif"
                  style="height: 30px; margin-right: 5px"
                  alt="loading..."
                />
                <h5>Loading more awesome article...</h5>
              </div>
              <div v-show="params.search && articles.length <= 0 && !loading">
                <div class="card mt-4 p-5 text-center">
                  <h5>No article found!</h5>
                </div>
              </div>
            </div>
          </div>

          <div class="col-md-3 col-sm-12 mt-4">
            <div class="panel-subhead pl-3 mt-4">
              <div class="icon-subhead">
                <img src="/img/icon/ic_features.png" />
              </div>
              <div class="title-subhead">
                <label>Features</label>
              </div>
            </div>
            <div class="col-md-12 pt-4 pb-4">
              <ads-konsultasi></ads-konsultasi>
            </div>
            <ads-trainevent></ads-trainevent>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>
<script>
export default {
  head() {
    return {
      title: "Insight Artikel Tentang HRD Indonesia - Linov Community",
      meta: [
        {
          hid: "og-title",
          name: "og:title",
          content: "Insight Artikel Tentang HRD Indonesia - Linov Community",
        },
        {
          hid: "description",
          name: "description",
          content:
            "Kumpulan Artikel untuk memperkaya Insight tentang HR Issue dan manajemen sumber daya manusia terlengkap di Indonesia yang dikurasi oleh Linov Community.",
        },
        {
          hid: "og-url",
          property: "og:url",
          content: "https://community.linovhr.com/insight/",
        },
      ],
    };
  },
  layout: "default-insight",
  middleware: "completed",
  data() {
    return {
      banners: [],
      articles: [],
      populars: [],
      loading: false,
      stopInfinite: false,
      params: {
        page: 1,
        sort: null,
        search: null,
      },
    };
  },
  async fetch() {
    this.$getPremiumArticle().then((response) => {
      this.banners = response.data;
    });
    this.$getPopularArticle().then((response) => {
      this.populars = response.data;
    });
    await this.getArticles();
  },
  async mounted() {
    window.addEventListener("scroll", this.handleScroll);
  },
  unmounted() {
    window.removeEventListener("scroll", this.handleScroll);
  },
  methods: {
    async getArticles() {
      this.loading = true;
      const { data } = await this.$getArticle({ params: this.params });
      this.loading = false;
      this.articles = [...this.articles, ...data.data];
      this.params.page += 1;
      this.stopInfinite = this.articles.length >= data.total;
    },
    searchArticle() {
      this.params.page = 1;
      this.articles = [];
      this.getArticles();
      this.$refs.content.scrollIntoView({ block: "start", behavior: "smooth" });
    },
    redirectTo(url) {
      this.$router.push(url);
    },
    handleScroll() {
      if (
        !this.stopInfinite &&
        !this.loading &&
        this.$refs.articles &&
        Math.floor(this.$refs.articles.getBoundingClientRect().bottom) <=
          window.innerHeight
      ) {
        this.getArticles();
      }
    },
  },
};
</script>
