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
          url: "/",
        },
        {
          name: "HR Administration",
          url: "/category/hr-administration",
        },
        {
          name: "Tax & Legal",
          url: "/category/tax-legal",
        },
        {
          name: "Comben",
          url: "/category/comben",
        },
        {
          name: "Planning & Recruitment",
          url: "/category/planning-recruitment",
        },
        {
          name: "Perfomance & Development",
          url: "/category/perfomance-development",
        },
        {
          name: "Career & Succession",
          url: "/category/career-succession",
        },
        {
          name: "Organization Development",
          url: "/category/organization-development",
        },
        {
          name: "HR Digital",
          url: "/category/hr-digital",
        },
      ],
    };
  },
};
</script>
