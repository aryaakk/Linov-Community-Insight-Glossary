<template>
  <div id="wrapper">
    <div class="header-nav">
      <navbar :submenu="menus"></navbar>
    </div>
    <div>
      <Nuxt />
    </div>
    <section-footer></section-footer>
  </div>
</template>

<script>
export default {
  data() {
    return {
      menus: [
        {
          name: "All",
          url: "/training",
        },
        {
          name: "Training",
          url: "/training/training",
        },
        {
          name: "Event",
          url: "/training/event",
        },
        {
          name: "Trainer",
          url: "/training/trainer",
        },
        {
          name: "Provider",
          url: "/training/provider",
        },
      ],
    };
  },
};
</script>
