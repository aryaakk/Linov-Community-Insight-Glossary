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
          name: "Insight",
          url: "/insight",
        },
        {
          name: "Deep Insight",
          url: "/insight/insight-book",
        },
        {
          name: "Glossary",
          url: "/insight/glossary",
        },
      ],
    };
  },
};
</script>
