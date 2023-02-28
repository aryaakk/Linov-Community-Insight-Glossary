<template>
  <nav class="navbar navbar-default navbar-fixed-top">
    <div class="container cont-nav">
      <div class="navbar-header">
        <NuxtLink class="navbar-brand" to="/"
          ><img src="/img/linov-nav.png" class="img-responsive logo"
        /></NuxtLink>
        <button
          type="button"
          @click="toggleSidebar"
          class="navbar-toggle collapsed btn-navigation"
        >
          <i class="fas fa-bars"></i>
        </button>
        <client-only>
          <panel-notif
            style="position: relative; float: right; margin-top: 13px"
            v-show="$screen.width <= 766"
          ></panel-notif>
        </client-only>
      </div>
      <div class="sidebar" ref="sidebar" style="display: none">
        <div class="text-center" v-if="$auth.loggedIn">
          <img
            :src="$auth.user.avatar ? $auth.user.avatar : '/img/home/user.png'"
            class="img-circle"
            alt="Avatar"
            style="
              width: 100px;
              object-fit: cover;
              margin-left: auto;
              margin-right: auto;
            "
          />
          <h4>{{ $auth.user.name }}</h4>
          <p>HR Lawencon Internasional</p>
          <hr class="py-0" />
        </div>
        <ul class="nav navbar-nav">
          <li>
            <NuxtLink to="/" class="py-3" ref="homelink">Forum</NuxtLink>
          </li>
          <li>
            <NuxtLink to="/consultation" class="py-3">Consultation</NuxtLink>
          </li>
          <li>
            <NuxtLink to="/training" class="py-3">Event & Training</NuxtLink>
          </li>
          <li>
            <NuxtLink to="/insight" class="py-3">Resource</NuxtLink>
          </li>
          <hr class="py-0" />
          <template v-if="$auth.loggedIn">
            <li>
              <NuxtLink class="py-3" :to="`/profile/${$auth.user.id}`">
                <i class="fas fa-user icon-space opt-color"></i>My Profile
              </NuxtLink>
            </li>

            <li>
              <NuxtLink class="py-3" to="/profile/edit">
                <i class="fas fa-user-edit icon-space opt-color"></i>Edit
                Profile
              </NuxtLink>
            </li>
            <li>
              <NuxtLink class="py-3" to="/profile/edit-password">
                <i class="fas fa-cog icon-space opt-color"></i>Setting
              </NuxtLink>
            </li>
            <li class="cursor" @click="logout()">
              <a class="py-3" href="#">
                <i class="fas fa-sign-out-alt icon-space opt-color"></i>Logout
              </a>
            </li>
          </template>
          <template v-else>
            <li>
              <NuxtLink to="/auth/login"
                ><span class="span-menu1">Sign In</span></NuxtLink
              >
            </li>
            <li>
              <NuxtLink to="/auth/signup"
                ><span class="span-menu1">Sign Up</span></NuxtLink
              >
            </li>
          </template>
        </ul>
      </div>
      <div class="navbar-collapse collapse py-3" aria-expanded="false">
        <div class="d-flex justify-content-between">
          <div></div>
          <ul class="nav navbar-nav">
            <li>
              <NuxtLink to="/" ref="homelink"
                ><span class="span-menu1">Forum</span></NuxtLink
              >
            </li>
            <li>
              <NuxtLink to="/consultation"
                ><span class="span-menu1">Consultation</span></NuxtLink
              >
            </li>
            <li>
              <NuxtLink to="/training"
                ><span class="span-menu1">Event & Training</span></NuxtLink
              >
            </li>
            <li>
              <NuxtLink to="/insight"
                ><span class="span-menu1">Resource</span></NuxtLink
              >
            </li>
          </ul>

          <ul class="nav navbar-nav navbar-right pt-3" v-if="$auth.loggedIn">
            <li>
              <client-only>
                <panel-notif v-show="$screen.width > 766"></panel-notif>
              </client-only>
            </li>
            <li>
              <div class="dropdown">
                <a
                  class="dropdown-toggle"
                  data-toggle="dropdown"
                  aria-haspopup="true"
                  aria-expanded="false"
                  style="color: #4a4a4a"
                >
                  <img
                    :src="
                      $auth.user.avatar
                        ? $auth.user.avatar
                        : '/img/home/user.png'
                    "
                    class="img-circle avatar-nav"
                    alt="Avatar"
                  />
                  <i class="fas fa-chevron-down"></i>
                </a>
                <ul
                  class="dropdown-menu"
                  v-if="$auth.loggedIn && $auth.user?.hasProfile"
                >
                  <li>
                    <NuxtLink class="py-3" :to="`/profile`">
                      <i class="fas fa-user icon-space opt-color"></i>My Profile
                    </NuxtLink>
                  </li>

                  <li>
                    <NuxtLink class="py-4" to="/profile/edit">
                      <i class="fas fa-user-edit icon-space opt-color"></i>Edit
                      Profile
                    </NuxtLink>
                  </li>
                  <li>
                    <NuxtLink class="py-4" to="/profile/edit-password">
                      <i class="fas fa-cog icon-space opt-color"></i>Setting
                    </NuxtLink>
                  </li>
                  <li class="cursor" @click="logout()">
                    <a class="py-4" href="#">
                      <i class="fas fa-sign-out-alt icon-space opt-color"></i
                      >Logout
                    </a>
                  </li>
                </ul>
                <ul class="dropdown-menu" v-else>
                  <li class="cursor" @click="logout()">
                    <a class="py-4" href="#">
                      <i class="fas fa-sign-out-alt icon-space opt-color"></i
                      >Logout
                    </a>
                  </li>
                </ul>
              </div>
            </li>
          </ul>
          <ul class="nav navbar-nav" v-else>
            <li>
              <NuxtLink to="/auth/signup"
                ><span class="span-menu1">Sign Up</span></NuxtLink
              >
            </li>
            <li>
              <NuxtLink to="/auth/login"
                ><span class="span-menu1">Login</span></NuxtLink
              >
            </li>
          </ul>
        </div>
      </div>
    </div>
    <div
      style="border-top: 1px solid #f2f2f2; padding-top: 6px"
      v-if="submenu && submenu.length > 0"
    >
      <div class="container d-flex justify-content-center">
        <ul class="nav navbar-nav menu">
          <li
            :class="$route.path == menu.url ? 'nuxt-link-exact-active' : ''"
            v-for="(menu, idx) in submenu"
            :key="idx"
          >
            <NuxtLink :to="menu.url" class="dropdown-toggle nav-menu2"
              ><span class="span-menu1">{{ menu.name }}</span></NuxtLink
            >
          </li>
        </ul>
      </div>
    </div>
  </nav>
</template>
<style>
.menu {
  display: flex;
  flex-wrap: nowrap;
  overflow-x: auto;
  -webkit-overflow-scrolling: touch;
}
.menu::-webkit-scrollbar {
  display: none;
}
.menu li {
  flex: 0 0 auto;
  padding: 2px;
}
.sidebar li a {
  font-size: 16px !important;
  font-weight: normal !important;
  color: #4a4a4a !important;
}
</style>
<script>
export default {
  name: "navbar-default",
  props: ["submenu"],
  data() {
    return {
      //
    };
  },
  mounted() {
    if (!this.$route.path.includes("/category") || !this.$route.path == "/") {
      this.$refs.homelink.$el.classList.remove("nuxt-link-active");
    }

    window.addEventListener("click", (e) => {
      if (!this.$el.contains(e.target) && this.$refs.sidebar?.style) {
        this.$refs.sidebar.style.display = "none";
      }
    });
  },
  methods: {
    async logout() {
      await this.$axios.get("/sanctum/csrf-cookie");

      await this.$auth.logout();
      localStorage.removeItem("redirectUrl");
      window.location.reload();
    },
    toggleSidebar() {
      let display = this.$refs.sidebar?.style?.display;
      this.$refs.sidebar.style.display =
        display == "none" || display == "" ? "block" : "none";
    },
  },
};
</script>
