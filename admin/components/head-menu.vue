<template>
    <nav class="layout-navbar container-xxl navbar navbar-expand-xl navbar-detached align-items-center bg-navbar-theme"
            id="layout-navbar"
          >
            <div class="layout-menu-toggle navbar-nav align-items-xl-center me-3 me-xl-0 d-xl-none">
              <a class="nav-item nav-link px-0 me-xl-4" href="javascript:void(0)">
                <i class="bx bx-menu bx-sm"></i>
              </a>
            </div>

            <div class="navbar-nav-right d-flex align-items-center" id="navbar-collapse">
              <!-- Search -->
              <div class="navbar-nav align-items-center">
                <div class="nav-item pt-3">
                  <nav aria-label="breadcrumb">
                    <ol class="breadcrumb">
                      <li class="breadcrumb-item"><nuxt-link to="/">Home</nuxt-link></li>
                      <li class="breadcrumb-item text-capitalize" :class="$route.path==`/${path}`? 'active' : ''" v-for="(path, idx) in breadcrumbs">
                       <span v-if="idx==(breadcrumbs.length-1)"> {{ path }}</span>
                       <nuxt-link :to="`/${path}`" v-else>{{ path }}</nuxt-link>
                      </li>
                    </ol>
                  </nav>
                </div>
              </div>
              <!-- /Search -->

              <ul class="navbar-nav flex-row align-items-center ms-auto">
                <!-- Place this tag where you want the button to render. -->
                <li class="nav-item lh-1 me-3">
                </li>

                <!-- User -->
                <li class="nav-item navbar-dropdown dropdown-user dropdown">
                  <a class="nav-link dropdown-toggle hide-arrow" href="javascript:void(0);" data-bs-toggle="dropdown">
                    <div class="avatar avatar-online">
                      <img :src="$auth.user.avatar ? $auth.user.avatar : '/img/home/avatar_medium.png'" alt class="w-px-40 h-auto rounded-circle" />
                    </div>
                  </a>
                  <ul class="dropdown-menu dropdown-menu-end">
                    <li>
                      <a class="dropdown-item" href="#">
                        <div class="d-flex">
                          <div class="flex-shrink-0 me-3">
                            <div class="avatar avatar-online">
                              <img :src="$auth.user.avatar ? $auth.user.avatar : '/img/home/avatar_medium.png'" alt class="w-px-40 h-auto rounded-circle" />
                            </div>
                          </div>
                          <div class="flex-grow-1">
                            <span class="fw-semibold d-block">{{ $auth.user.name }}</span>
                            <small class="text-muted">Admin</small>
                          </div>
                        </div>
                      </a>
                    </li>
                    <li>
                      <div class="dropdown-divider"></div>
                    </li>
                    <li>
                      <a class="dropdown-item" href="#">
                        <i class="bx bx-user me-2"></i>
                        <span class="align-middle">My Profile</span>
                      </a>
                    </li>
                    <li>
                      <a class="dropdown-item" href="#">
                        <i class="bx bx-cog me-2"></i>
                        <span class="align-middle">Settings</span>
                      </a>
                    </li>
                    <li>
                      <div class="dropdown-divider"></div>
                    </li>
                    <li>
                      <div class="dropdown-item" style="cursor:pointer" @click="logout()">
                        <i class="bx bx-power-off me-2"></i>
                        <span class="align-middle">Log Out</span>
                      </div>
                    </li>
                  </ul>
                </li>
                <!--/ User -->
              </ul>
            </div>
          </nav>
</template>

<script>
export default {
  name: "nav-menu",
  computed: {
    breadcrumbs: function () {
      const breadcrumb = this.$store.state.breadcrumbs ? this.$store.state.breadcrumbs : this.$route.path.substring(1)
      return breadcrumb.split("/")
    },
  },
  methods: {
      async logout() {
          await this.$axios.get("/sanctum/csrf-cookie");

          await this.$auth.logout()

          window.location.reload()
      },
  }
}
</script>