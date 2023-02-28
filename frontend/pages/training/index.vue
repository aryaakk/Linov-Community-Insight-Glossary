<template>
  <div style="padding-top: 100px">
    <!-- banner here -->
    <div
      id="banner-training"
      class="main-training-banner carousel slide"
      data-ride="carousel"
      v-if="banners.length > 0"
    >
      <div class="carousel-inner">
        <div
          class="item"
          :class="idx == 0 ? 'active' : ''"
          v-for="(banner, idx) in banners"
          :key="idx"
        >
          <NuxtLink :to="`/training/${banner.type}/${banner.slug_id}`">
            <img
              :src="banner.banner_slide_url"
              :alt="banner.title"
              class="banner-training"
            />
          </NuxtLink>
        </div>
      </div>
      <a
        class="left carousel-control"
        href="#banner-training"
        data-slide="prev"
      >
        <span class="fas fa-chevron-left"></span>
        <span class="sr-only">Previous</span>
      </a>
      <a
        class="right carousel-control"
        href="#banner-training"
        data-slide="next"
      >
        <span class="fas fa-chevron-right"></span>
        <span class="sr-only">Next</span>
      </a>
    </div>

    <div class="container">
      <div
        class="mt-5"
        style="
          background-image: url('/img/wording_training.png');
          background-size: cover;
          background-repeat: no-repeat;
          border-radius: 10px;
        "
      >
        <h4
          class="text-white text-center"
          style="padding-top: 2rem; padding-bottom: 2rem"
        >
          Choose the right class for your improvement
        </h4>
      </div>
      <div class="row mt-5 p-0">
        <div class="col-md-3">
          <client-only>
            <nav-training
              :sorts="sorts"
              :categories="categories"
              :classes="classes"
              @onFilter="filterData"
            ></nav-training>
          </client-only>
        </div>
        <div class="col-md-9">
          <div class="panel-subhead py-4 d-flex justify-content-between">
            <div class="d-flex">
              <div class="icon-subhead">
                <img src="/img/icon/ic_training.png" />
              </div>
              <div class="title-subhead">
                <label>Training</label>
              </div>
            </div>
            <p>
              <u><NuxtLink to="/training/training">View All</NuxtLink></u>
            </p>
          </div>
          <div class="row d-flex-wrap p-0">
            <panel-training
              v-for="(training, idx) in trainings"
              :key="idx"
              :data="training"
            ></panel-training>
          </div>

          <div class="panel-subhead py-4 d-flex justify-content-between">
            <div class="d-flex">
              <div class="icon-subhead">
                <img src="/img/icon/ic_event.png" />
              </div>
              <div class="title-subhead">
                <label>Event</label>
              </div>
            </div>
            <p>
              <u><NuxtLink to="/training/event">View All</NuxtLink></u>
            </p>
          </div>

          <div class="row d-flex-wrap p-0">
            <panel-training
              v-for="(event, idx) in events"
              :key="idx"
              :data="event"
            ></panel-training>
          </div>

          <div class="panel-subhead py-4 d-flex justify-content-between">
            <div class="d-flex">
              <div class="icon-subhead">
                <img src="/img/icon/ic_star.png" />
              </div>
              <div class="title-subhead">
                <label>Trainer</label>
              </div>
            </div>
            <p>
              <u><NuxtLink to="/training/trainer">View All</NuxtLink></u>
            </p>
          </div>

          <div class="row d-flex-wrap p-0">
            <panel-trainer
              :data="trainer"
              v-for="(trainer, idx) in trainers"
              :key="idx"
            ></panel-trainer>
          </div>

          <div class="panel-subhead py-4 d-flex justify-content-between">
            <div class="d-flex">
              <div class="icon-subhead">
                <img src="/img/icon/ic_provider.png" />
              </div>
              <div class="title-subhead">
                <label>Provider</label>
              </div>
            </div>
            <p>
              <u><NuxtLink to="/training/provider">View All</NuxtLink></u>
            </p>
          </div>

          <div class="row p-0">
            <panel-provider
              :data="provider"
              v-for="(provider, idx) in providers"
              :key="idx"
            ></panel-provider>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>
<style>
.carousel-control span {
  margin-top: 180px;
}
</style>
<script>
export default {
  head() {
    return {
      title:
        "Webinar HRD Online Gratis Indonesia - Event & Training - Linov Community",
      meta: [
        {
          hid: "og-title",
          name: "og:title",
          content:
            "Forum Diskusi dan Konsultasi HRD Online Indonesia - Linov Community",
        },
        {
          hid: "description",
          name: "description",
          content:
            "Upgrade dan update skill melalui event & training online. Ikuti webinar dan pelatihan bersama para ahli dan praktisi HR profesional.",
        },
        {
          hid: "og-url",
          property: "og:url",
          content: "https://community.linovhr.com/consultation/",
        },
      ],
    };
  },
  layout: "default-training",
  data() {
    return {
      categories: [],
      trainings: [],
      events: [],
      trainers: [],
      providers: [],
      banners: [],
      sorts: ["Popular", "Newest", "Highest Price", "Lowest Price"],
      classes: ["All", "Public", "In House"],
      params: {
        perpage: 3,
      },
    };
  },
  async fetch() {
    await this.$getCategories().then((data) => {
      this.categories = data.data;
    });
    await this.$getIndexBanners().then((data) => {
      this.banners = data.data;
    });
    await this.$getAllTraining(this.params).then((response) => {
      this.trainings = response.data.data;
    });
    await this.$getAllEvent(this.params).then((response) => {
      this.events = response.data.data;
    });
    await this.$getAllTrainer({ perpage: 4 }).then((response) => {
      this.trainers = response.data.data;
    });
    await this.$getAllProvider({ perpage: 4 }).then((response) => {
      this.providers = response.data.data;
    });
  },
  methods: {
    async filterData(params) {
      this.params = { ...this.params, ...params };
      await this.$getAllTraining(this.params).then((response) => {
        this.trainings = response.data.data;
      });
      await this.$getAllEvent(this.params).then((response) => {
        this.events = response.data.data;
      });
    },
  },
};
</script>
