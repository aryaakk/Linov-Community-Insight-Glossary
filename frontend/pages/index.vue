<template>
  <div>
    <div class="top-wrapper">
      <div class="container">
        <h1>Gali Potensi Karir dan Manajemen SDM</h1>
        <h1>Bersama LinovHR!</h1>
        <div
          class="d-flex justify-content-center pt-4 pb-4"
          style="position: relative"
        >
          <input
            @keyup.enter="searchThread()"
            type="search"
            v-model="params.search"
            class="form-control searchbox mt-4"
            name="query"
            placeholder="Search"
          />
          <span
            @click="searchThread()"
            class="search-text mt-4 cursor"
            style="position: absolute; top: 25px; right: 24%"
            ><i class="fa fa-search size-icon"></i
          ></span>
        </div>
      </div>
    </div>

    <div class="main-home">
      <div class="container">
        <div class="row" ref="threads">
          <div class="col-lg-9 col-sm-12">
            <div class="panel-subhead mt-4">
              <div class="icon-subhead">
                <img src="/img/icon/ic_most.png" />
              </div>
              <div class="title-subhead">
                <label>Most Discussed In The Community</label>
              </div>
            </div>
            <div class="scrollable pt-4">
              <div
                class="content px-2 py-2"
                @click="$router.push(`threads/${discus.slug_id}`)"
                v-for="(discus, idx) in mostDiscuses"
                :key="idx"
              >
                <div
                  class="card-most"
                  :class="discus.image ? 'text-white card-img-overlay' : ''"
                  :style="
                    discus.image
                      ? `background-image: url('${discus.image.file_url}');`
                      : ''
                  "
                >
                  <div class="p-4">
                    <div
                      class="card-headtitle"
                      :style="{
                        background: discus.image
                          ? 'rgba(255, 255, 255, 0.4)'
                          : discus.color_bg,
                      }"
                    >
                      <h5
                        class="card-title"
                        :style="{
                          color: discus.image ? '#FFFFFF' : discus.color,
                        }"
                      >
                        {{ discus.name }}
                      </h5>
                    </div>
                    <p class="card-text">
                      {{ discus.title }}
                    </p>
                  </div>
                  <div class="p-4" style="position: absolute; bottom: 15px">
                    <span class="span-comment mr-3"
                      ><i class="fas fa-eye span-comment2"></i
                      >{{ discus.total_view }}</span
                    >
                    <span class="span-comment mr-3"
                      ><i class="fas fa-heart span-comment2"></i
                      >{{ discus.total_love }}</span
                    >
                    <span class="span-comment mr-3"
                      ><i class="fas fa-comment-alt span-comment2"></i
                      >{{ discus.total_comment }}</span
                    >
                  </div>
                  <div
                    class="card-bottom-line"
                    :style="{ background: discus.color }"
                  ></div>
                </div>
              </div>
            </div>
          </div>
          <div class="col-lg-3 col-sm-12">
            <div class="panel-subhead mt-4">
              <div class="icon-subhead">
                <img src="/img/icon/ic_features.png" />
              </div>
              <div class="title-subhead">
                <label>Consultation</label>
              </div>
            </div>
            <div class="col-md-12 pt-4 px-4 pb-4">
              <ads-konsultasi></ads-konsultasi>
            </div>
          </div>

          <div class="col-md-9 col-sm-12 mt-4" ref="content">
            <div class="panel-subhead mt-4 mb-4">
              <div class="icon-subhead">
                <img src="/img/icon/ic_thread.png" />
              </div>
              <div class="title-subhead">
                <label>Thread & Discuss</label>
              </div>
            </div>
            <div class="panel-subhead justify-content-right">
              <div class="dropdown px-1 col-md-2 col-xs-6">
                <button
                  type="button"
                  class="btn btn-secondary btn-corner btn-block dropdown-toggle"
                  data-toggle="dropdown"
                  aria-haspopup="true"
                  aria-expanded="false"
                  style="margin-right: 8px"
                >
                  Filter
                  <i class="fa fa-chevron-down"></i>
                </button>
                <ul
                  class="dropdown-menu checkbox-menu allow-focus postion-sort ul-pad"
                >
                  <li v-for="(category, idx) in categories" :key="idx">
                    <label>
                      <input
                        type="checkbox"
                        @click="changeFilter(category.code)"
                        :selected="params.filter.includes(category.code)"
                        :value="category.code"
                      />
                      {{ category.name }}
                      <span class="checkmark"></span>
                    </label>
                  </li>
                </ul>
              </div>
              <div class="dropdown px-1 col-md-2 col-xs-6">
                <button
                  type="button"
                  class="btn btn-secondary btn-corner btn-block dropdown-toggle"
                  data-toggle="dropdown"
                  aria-haspopup="true"
                  aria-expanded="false"
                >
                  Sort By
                  <i class="fa fa-chevron-down"></i>
                </button>
                <ul class="dropdown-menu postion-filter" id="optiov-thread">
                  <li @click="changeSort('lastest')">
                    <span class="opt-color" v-if="params.sort == 'lastest'"
                      ><i class="fas fa-check"></i></span
                    >Last Update
                  </li>
                  <li @click="changeSort('created')">
                    <span class="opt-color" v-if="params.sort == 'created'"
                      ><i class="fas fa-check"></i></span
                    >Created Date
                  </li>
                  <li @click="changeSort('atoz')">
                    <span class="opt-color" v-if="params.sort == 'atoz'"
                      ><i class="fas fa-check"></i></span
                    >A to Z
                  </li>
                  <li @click="changeSort('ztoa')">
                    <span class="opt-color" v-if="params.sort == 'ztoa'"
                      ><i class="fas fa-check"></i></span
                    >Z to A
                  </li>
                  <li @click="changeSort('mostlike')">
                    <span class="opt-color" v-if="params.sort == 'mostlike'"
                      ><i class="fas fa-check"></i></span
                    >Most Likes
                  </li>
                  <li @click="changeSort('mostview')">
                    <span class="opt-color" v-if="params.sort == 'mostview'"
                      ><i class="fas fa-check"></i></span
                    >Most View
                  </li>
                </ul>
              </div>
            </div>

            <button
              class="btn btn-primary w-100 mt-4"
              @click="$router.push('/threads/create')"
              style="height: 50px"
            >
              <img src="/img/home/icon_create.png" />
              <b>Create A New Thread</b>
            </button>

            <div class="scrolling-pagination">
              <panel-thread
                v-for="(thread, idx) in threads"
                :key="idx"
                :data="thread"
                @onDelete="deleteThreadById"
              ></panel-thread>
              <div
                v-if="$screen.width <= 990 && !stopInfinite && !loading"
                class="text-center py-3"
              >
                <a href="#" @click.prevent="getThreads"
                  >Show More <i class="fas fa-chevron-down"></i
                ></a>
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
                <h5>Loading more awesome threads...</h5>
              </div>
              <div v-show="params.search && threads.length <= 0 && !loading">
                <div class="card mt-4 p-5 text-center">
                  <h5>No thread found!</h5>
                </div>
              </div>
            </div>
          </div>

          <div class="col-md-3 col-sm-12 mt-4">
            <ads-trainevent></ads-trainevent>
            <ads-article></ads-article>
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
      title: "Forum Komunitas HRD Online Indonesia - Linov Community",
      meta: [
        {
          hid: "og-title",
          name: "og:title",
          content: "Forum Komunitas HRD Online Indonesia - Linov Community",
        },
        {
          hid: "description",
          name: "description",
          content:
            "Forum komunitas HR terlengkap di Indonesia. Diskusi & pertanyaan tentang HR Issues dan pengembangan sumber daya manusia dengan praktisi secara langsung di Linov Community",
        },
        {
          hid: "og-url",
          property: "og:url",
          content: "https://community.linovhr.com/",
        },
      ],
    };
  },
  layout: "default-thread",
  middleware: "completed",
  data() {
    return {
      mostDiscuses: [],
      threads: [],
      categories: [],
      loading: false,
      stopInfinite: false,
      params: {
        page: 1,
        sort: null,
        filter: [],
        search: null,
      },
    };
  },
  async fetch() {
    await this.$getCategories().then((data) => {
      this.categories = data.data;
    });
    await this.getThreads();
  },
  async mounted() {
    await this.$getMostDiscuses().then((data) => {
      this.mostDiscuses = data.data;
    });
    if (this.$screen.width > 990) {
      window.addEventListener("scroll", this.handleScroll);
    }
  },
  unmounted() {
    if (this.$screen.width > 990) {
      window.removeEventListener("scroll", this.handleScroll);
    }
  },
  methods: {
    async getThreads() {
      this.loading = true;
      const { data } = await this.$getThreads({ params: this.params });
      this.threads = [...this.threads, ...data.data];
      this.params.page += 1;
      this.loading = false;
      this.stopInfinite = this.threads.length >= data.total;
    },
    changeSort(sort) {
      this.params.sort = sort;
      this.params.page = 1;
      this.threads = [];
      this.getThreads();
    },
    changeFilter(code) {
      const idx = this.params.filter.findIndex((item) => item == code);
      if (idx == -1) {
        this.params.filter.push(code);
      } else {
        this.params.filter.splice(idx, 1);
      }
      this.params.page = 1;
      this.threads = [];
      this.getThreads();
    },
    searchThread() {
      this.params.page = 1;
      this.threads = [];
      this.getThreads();
      this.$refs.content.scrollIntoView({ block: "start", behavior: "smooth" });
    },
    handleScroll() {
      if (
        !this.stopInfinite &&
        !this.loading &&
        this.$refs.threads &&
        Math.floor(this.$refs.threads.getBoundingClientRect().bottom) <=
          window.innerHeight
      ) {
        this.getThreads();
      }
    },
    deleteThreadById(id) {
      const idx = this.threads.findIndex((thread) => thread.id == id);
      this.threads.splice(idx, 1);
    },
  },
};
</script>
