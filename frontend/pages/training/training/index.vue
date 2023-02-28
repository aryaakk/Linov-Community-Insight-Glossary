<template>
  <div class="pt-100">
    <div class="container" ref="trainings">
      <div
        class="row py-4 mr-1 ml-1 heading-training"
        style="background-image: url('/img/training/bg-training.png')"
      >
        <div class="col-md-4 text-center py-4">
          <img src="/img/training/icon_training.svg" />
        </div>
        <div class="col-md-8">
          <h3>Training</h3>
          <p>
            Ikuti berbagai training bersama LinovCommunity dan tingkatkan
            kemampuan serta skill di bidang human resources. Didalam
            LinovCommnuity terdapat banyak pilihan training untuk diikuti.
          </p>
        </div>
      </div>
      <div class="row mt-5 p-0">
        <div class="col-md-3">
          <client-only>
            <nav-training
              :sorts="sorts"
              :classes="classes"
              :categories="categories"
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

          <div class="row p-0">
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

<script>
export default {
  head: {
    title: "Training",
  },
  layout: "default-training",
  data() {
    return {
      categories: [],
      trainings: [],
      trainers: [],
      providers: [],
      sorts: ["Popular", "Newest", "Highest Price", "Lowest Price"],
      classes: ["All", "Public", "In House"],
      loading: false,
      stopInfinite: false,
      params: {
        page: 1,
        perpage: 9,
      },
    };
  },
  async fetch() {
    await this.$getCategories().then((data) => {
      this.categories = data.data;
    });
    await this.$getAllTraining(this.params).then((response) => {
      this.trainings = response.data.data;
    });
    await this.$getAllTrainer({ perpage: 4 }).then((response) => {
      this.trainers = response.data.data;
    });
    await this.$getAllProvider({ perpage: 4 }).then((response) => {
      this.providers = response.data.data;
    });
    this.params.page += 1;
  },
  mounted() {
    window.addEventListener("scroll", this.handleScroll);
  },
  unmounted() {
    window.removeEventListener("scroll", this.handleScroll);
  },
  methods: {
    async filterData(params) {
      this.params.page = 1;
      this.params = { ...this.params, ...params };
      await this.$getAllTraining(this.params).then((response) => {
        this.trainings = response.data.data;
      });
    },
    async handleScroll() {
      if (
        !this.stopInfinite &&
        !this.loading &&
        this.$refs.trainings &&
        Math.floor(this.$refs.trainings.getBoundingClientRect().bottom) <=
          window.innerHeight
      ) {
        this.loading = true;
        const { data } = await this.$getAllTraining(this.params);
        this.trainings = [...this.trainings, ...data.data];
        this.params.page += 1;
        this.loading = false;
        this.stopInfinite = this.trainings.length >= data.total;
      }
    },
  },
};
</script>
