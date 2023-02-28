<template>
  <div style="padding-top: 80px">
    <div class="main-home">
      <div class="container">
        <div class="row" ref="articles" v-if="article.data">
          <div class="col-md-9 col-sm-12">
            <div class="panel-subhead mt-4">
              <div class="icon-subhead">
                <img src="/img/icon/ic_most.png" />
              </div>
              <div class="title-subhead">
                <label>Insight</label>
              </div>
            </div>
            <div class="py-4 mt-4">
              <div
                class="main-article-banner"
                :style="{
                  'background-image': `url(${article.data.banner_url})`,
                }"
              ></div>

              <div
                class="card mt-4 article-body"
                :class="
                  !$auth.loggedIn && article.data.trx_code == 'ART-PRE'
                    ? 'article-premium'
                    : ''
                "
              >
                <h1>{{ article.data.title }}</h1>
                <div class="pt-4" v-html="article.data.detail"></div>
                <div
                  class="hiden-content-thread"
                  v-if="!$auth.loggedIn && article.data.trx_code == 'ART-PRE'"
                >
                  <div class="panel-ads-consultaion">
                    <div class="row">
                      <div class="col-md-9 col-xs-12 d-flex">
                        <img
                          src="/img/thread/consul-msg-icon.svg"
                          class="mr-2"
                        />
                        <span>Baca Insight dari Kami, Gratis! </span>
                      </div>
                      <div class="col-md-2 col-xs-12 text-center">
                        <button
                          @click="readNow"
                          class="btn px-3 btn-ads-consul"
                        >
                          Baca Sekarang!
                        </button>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
              <div class="row mt-4 article-footer">
                <div class="col-md-6">
                  <h4 v-if="article.prev">Previous Post</h4>

                  <div
                    v-if="article.prev"
                    class="card py-2"
                    style="border-left: 10px solid #bfbfbf"
                  >
                    <h4>
                      <strong>{{ article.prev?.title }}</strong>
                    </h4>

                    <NuxtLink :to="`/insight/${article.prev?.slug_id}`"
                      >Lihat Detail...</NuxtLink
                    >
                  </div>
                </div>
                <div class="col-md-6">
                  <h4 v-if="article.next">Next Post</h4>

                  <div
                    v-if="article.next"
                    class="card py-2"
                    style="border-right: 10px solid #0099ff"
                  >
                    <h4>
                      <strong>{{ article.next?.title }}</strong>
                    </h4>

                    <NuxtLink :to="`/insight/${article.next?.slug_id}`"
                      >Lihat Detail...</NuxtLink
                    >
                  </div>
                </div>
              </div>
            </div>
          </div>
          <div class="col-md-3 col-sm-12 mt-4">
            <div class="panel-subhead mt-4">
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
<style>
.article-body span {
  color: #767676 !important;
}
.article-body h1 {
  font-weight: 600;
  font-size: 33px;
  line-height: 32px;
}
.article-body h1,
.article-body h2,
.article-body h3,
.article-body h4,
.article-body h5,
.article-body h6 {
  color: #4e4e4e;
}
.article-premium {
  position: relative;
  padding-bottom: 13vh !important;
}
</style>
<script>
export default {
  head() {
    return {
      title: this.article.data?.title || "Detail Artikel",
      meta: [
        {
          hid: "og-title",
          name: "og:title",
          content: `${
            this.article.data?.meta_title
              ? this.article.data?.meta_title
              : this.article.data?.title
          } - Linov Community`,
        },
        {
          hid: "description",
          name: "description",
          content: `${
            this.article.data?.meta_desc
              ? this.article.data?.meta_desc
              : this.article.data?.title
          } - Linov Community`,
        },
        {
          hid: "og-url",
          property: "og:url",
          content: "https://community.linovhr.com/insight/" + this.slug,
        },
      ],
    };
  },
  layout: "default-insight",
  data() {
    return {
      article: {},
      form: {
        article_id: null,
        comment: "",
        processing: false,
        errors: [],
      },
    };
  },
  async fetch() {
    const slug = this.$router.currentRoute.params.slug;
    const isPreview = this.$route.query.preview;
    if (isPreview) {
      await this.$getPreviewArticle(slug)
        .then((response) => {
          this.article = response.data;
        })
        .catch((error) => {
          return this.$nuxt.error({
            statusCode: error.response.status,
            message: error.response.statusText,
          });
        });
      return;
    }
    await this.$getOneArticle(slug)
      .then((response) => {
        this.article = response.data;
      })
      .catch((error) => {
        return this.$nuxt.error({
          statusCode: error.response.status,
          message: error.response.statusText,
        });
      });
  },
  methods: {
    readNow() {
      this.$store.commit("SET_REDIRECT", this.$route.path);
      this.$router.push("/auth/login");
    },
    async postComment() {
      this.form.processing = true;
      this.form.article_id = this.article.data.id;

      await this.$postCommentArticle(this.form)
        .then((response) => {
          this.form.processing = false;
          this.form.comment = "";
          this.$toast.success(
            "Terimakasih telah berkomentar. Komentar anda akan tampil setelah diproses oleh tim kami"
          );
        })
        .catch((e) => {
          this.form.errors = e.response.data.errors;
          this.form.processing = false;
          this.$toast.error("An error occured!");
        });
    },
    async deleteComment(id) {
      await this.$deleteCommentArticle(id)
        .then((response) => {
          const idx = this.article.data.comments.findIndex(
            (comment) => (comment.id = id)
          );

          this.article.data.comments.splice(idx, 1);
          this.$toast.success("Berhasil menghapus komentar!");
        })
        .catch((e) => {
          this.$toast.error("An error occured!");
        });
    },
  },
};
</script>
