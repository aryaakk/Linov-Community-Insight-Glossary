<template>
  <div>
    <div class="banner-glossary">
      <div class="container">
        <div class="header-banner">
          <h1>HR Glossary</h1>
        </div>
        <div class="desc-banner">
          <span
            >We’re building the best HR terms and definitions library for HR
            professionals!</span
          >
          <button @click="scrollTo('main-home', 'search')">Browse Terms</button>
        </div>
      </div>
    </div>

    <!-- screen alphabet list appears when scrolling -->
    <div class="layer-alph">
      <div class="alphabet">
        <div class="alph">
          <template>
            <button
              id="btnl-#"
              :class="!checkLetter('#') ? 'disabled' : ''"
              v-if="checkLetter('#') != true"
              disabled
              @click="scrollTo('#')"
            >
              #
            </button>
            <button
              id="btnl-#"
              :class="!checkLetter('#') ? 'disabled' : ''"
              v-if="checkLetter('#') == true"
              @click="scrollTo('#')"
            >
              #
            </button>
          </template>
          <template v-for="(alph, idx) in alphabet">
            <button
              :id="'btnl-' + alph"
              v-if="checkLetter(alph) != true"
              disabled
              :class="!checkLetter(alph) ? 'disabled' : ''"
              class="button-alph"
              @click="scrollTo(alph)"
            >
              {{ alph }}
            </button>
            <button
              :id="'btnl-' + alph"
              v-else-if="checkLetter(alph) == true"
              :class="!checkLetter(alph) ? 'disabled' : ''"
              class="button-alph"
              @click="scrollTo(alph)"
            >
              {{ alph }}
            </button>
          </template>
        </div>
        <div class="type-list">
          <ul class="checkbox-menu allow-focus postion-sort ul-pad">
            <li v-for="(insightType, idx) in type" :key="idx">
              <label class="list-label">
                <input
                  type="checkbox"
                  :id="idx"
                  @click="changeFilter(insightType.name, idx)"
                  v-model="params.filter[insightType.id]"
                  :value="insightType.name"
                />
                {{ insightType.name }}
                <span class="checkmark"></span>
              </label>
            </li>
          </ul>
        </div>
      </div>
    </div>
    <!-- end screen -->

    <div class="main-home gloss" id="main-home">
      <div
        class="d-flex justify-content-center py-5"
        style="position: relative"
      >
        <input
          ref="search"
          type="search"
          @keyup.enter="searchGlossary()"
          v-model="params.search"
          class="form-control searchbox mt-4"
          name="query"
          placeholder="Search"
        />
        <span @click="searchGlossary()" class="search-text mt-4 cursor"
          ><i class="fa fa-search size-icon"></i
        ></span>
      </div>
      <div ref="content" class="alphabet-list">
        <div class="alphabet">
          <div class="alph">
            <template>
              <button
                id="btn-#"
                :class="!checkLetter('#') ? 'disabled' : ''"
                v-if="checkLetter('#') != true"
                disabled
                @click="scrollTo('#')"
              >
                #
              </button>
              <button
                id="btn-#"
                :class="!checkLetter('#') ? 'disabled' : ''"
                v-if="checkLetter('#') == true"
                @click="scrollTo('#')"
              >
                #
              </button>
            </template>
            <template v-for="(alph, idx) in alphabet">
              <button
                :id="'btn-' + alph"
                v-if="checkLetter(alph) != true"
                disabled
                :class="!checkLetter(alph) ? 'disabled' : ''"
                class="button-alph"
                @click="scrollTo(alph)"
              >
                {{ alph }}
              </button>
              <button
                :id="'btn-' + alph"
                v-else-if="checkLetter(alph) == true"
                :class="!checkLetter(alph) ? 'disabled' : ''"
                class="button-alph"
                @click="scrollTo(alph)"
              >
                {{ alph }}
              </button>
            </template>
          </div>
          <div class="type-list">
            <ul class="checkbox-menu allow-focus postion-sort ul-pad">
              <li v-for="(insightType, idx) in type" :key="idx">
                <label class="list-label">
                  <input
                    type="checkbox"
                    :id="idx"
                    @click="changeFilter(insightType.name, idx)"
                    v-model="params.filter[insightType.id]"
                    :value="insightType.name"
                  />
                  {{ insightType.name }}
                  <span class="checkmark"></span>
                </label>
              </li>
            </ul>
          </div>
        </div>
      </div>
      <div
        v-show="loading"
        class="d-flex justify-content-center align-items-center mb-4"
      >
        <img
          src="/img/loader.gif"
          style="height: 30px; margin-right: 5px"
          alt="loading..."
        />
        <h5>Loading more awesome glossary...</h5>
      </div>
      <div
        v-show="params.search && glossary.length <= 0 && !loading"
        class="d-flex mb-4"
      >
        <div style="width: 100%" class="card p-5 text-center">
          <h3>No Glossary Found!</h3>
        </div>
      </div>
      <div class="content-main">
        <div
          class="wrap-list"
          id="#"
          :class="!checkLetter('#') ? 'hidden' : ''"
        >
          <h1>#</h1>
          <div
            class="list-glossary"
            v-for="(listglos, idx) in glossary"
            :key="idx"
          >
            <nuxt-link
              v-for="(gloss, key) in listglos"
              :key="key"
              :to="`/insight/${gloss.slug_id}`"
              v-if="gloss.letter == '#'"
            >
              {{ gloss.title }}
            </nuxt-link>
          </div>
        </div>
        <div
          class="wrap-list"
          v-for="(alph, idx) in alphabet"
          :key="idx"
          :class="!checkLetter(alph) ? 'hidden' : ''"
          :id="alph"
        >
          <h1>{{ alph }}</h1>
          <div
            class="list-glossary"
            v-for="(listglos, idx) in glossary"
            :key="idx"
          >
            <nuxt-link
              v-for="(gloss, key) in listglos"
              :key="key"
              :to="`/insight/${gloss.slug_id}`"
              v-if="gloss.letter == alph"
            >
              {{ gloss.title }}
            </nuxt-link>
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
      title: "HR Glossary Insight- Linov Community",
      meta: [
        {
          hid: "og-title",
          name: "og:title",
          content: "HR Glossary Insight- Linov Community",
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
      activeAlph: null,
      activeButton: null,
      activeButtonLayer: null,
      glossary: [],
      type: [],
      loading: false,
      stopInfinite: false,
      isUserScroll: false,
      params: {
        page: 1,
        filter: [],
        search: null,
      },
    };
  },
  computed: {
    alphabet() {
      const alphabet = [];
      for (var i = 0; i < 26; i++) {
        var alph = String.fromCharCode(i + 65);
        alphabet.push(alph);
      }
      return alphabet;
    },
  },
  async mounted() {
    window.addEventListener("scroll", this.handleScroll);
  },
  unmounted() {
    window.removeEventListener("scroll", this.handleScroll);
  },
  async fetch() {
    await this.$getTypeInsight().then((data) => {
      this.type = data.data;
    });
    await this.getGlossary();
  },
  methods: {
    async getGlossary() {
      this.loading = true;
      const { data } = await this.$getGlossary({ params: this.params });
      this.loading = false;
      this.glossary = data;
      this.params.page += 1;
    },
    searchGlossary() {
      this.params.page = 1;
      this.glossary = [];
      this.getGlossary();
      this.$refs.content.scrollIntoView({ block: "start", behavior: "smooth" });
    },
    async changeFilter(name, id) {
      console.log(this.params.filter);
      const idx = this.params.filter.findIndex((item) => item == name);
      if (idx == -1) {
        this.params.filter.push(name);
      } else {
        this.params.filter.splice(idx, 1);
      }
      this.params.page = 1;
      this.glossary = [];
      this.getGlossary();
    },
    redirectTo(url) {
      this.$router.push(url);
    },
    scrollTo(params, x = 0) {
      // when button alhabet clicked and direct to glossary alphabet content
      const element = document.getElementById(params);
      const alphLayer = document.querySelector(".layer-alph");
      const alphFixed = document.querySelector(".alphabet-list");
      if (document.querySelector(".layer-alph").style.display == "none") {
        const offset = 90;
        const bodyRect = alphFixed.getBoundingClientRect().height;
        const elementRect = element.getBoundingClientRect().top;
        const elementPosition = elementRect - bodyRect;
        const offsetPosition = elementPosition - offset;
        window.scrollBy({
          top: offsetPosition,
          behavior: "smooth",
        });
      } else {
        const offset = 110;
        const bodyRect = alphLayer.getBoundingClientRect().height;
        const elementRect = element.getBoundingClientRect().top;
        const elementPosition = elementRect - bodyRect;
        const offsetPosition = elementPosition - offset;
        window.scrollBy({
          top: offsetPosition,
          behavior: "smooth",
        });
      }

      // when buttton 'browse terms' clicked
      if (x == "search") {
        this.$refs.search.focus();
        window.scrollTo({
          top: 300,
          behavior: "smooth",
        });
        return;
      }

      // acitved button when clicked
      var btnActive = document.getElementById("btn-" + params);
      var btnActiveLayer = document.getElementById("btnl-" + params);
      if (this.activeAlph == null) {
        this.activeAlph = params;
        this.activeButton = "btn-" + params;
        this.activeButtonLayer = "btnl-" + params;
        element.classList.add("active-alph");
        btnActive.classList.add("active-btn");
        btnActiveLayer.classList.add("active-btn");
      } else if (this.activeAlph != null) {
        var elementOff = document.getElementById(this.activeAlph);
        var btnOff = document.getElementById(this.activeButton);
        var btnOffLayer = document.getElementById(this.activeButtonLayer);
        elementOff.classList.remove("active-alph");
        btnOff.classList.remove("active-btn");
        btnOffLayer.classList.remove("active-btn");
        element.classList.add("active-alph");
        btnActive.classList.add("active-btn");
        btnActiveLayer.classList.add("active-btn");
        this.activeAlph = params;
        this.activeButton = "btn-" + params;
        this.activeButtonLayer = "btnl-" + params;
      }
    },
    handleScroll() {
      // screen layer to responsive web design
      if (this.$screen.width <= 576) {
        if (window.scrollY > 850) {
          document.querySelector(".layer-alph").style.display = "block";
        } else {
          document.querySelector(".layer-alph").style.display = "none";
        }
      } else if (this.$screen.width > 576 && this.$screen.width <= 992) {
        if (window.scrollY > 590) {
          document.querySelector(".layer-alph").style.display = "block";
        } else {
          document.querySelector(".layer-alph").style.display = "none";
        }
      } else if (this.$screen.width > 992) {
        if (window.scrollY > 595) {
          document.querySelector(".layer-alph").style.display = "block";
        } else if (window.scrollY < 460) {
          document.querySelector(".layer-alph").style.display = "none";
        }
      }
    },
    checkLetter(letter) {
      if (!this.glossary[letter]) {
        return false;
      } else {
        return Object.keys(this.glossary[letter]).length > 0;
      }
    },
  },
};
</script>
