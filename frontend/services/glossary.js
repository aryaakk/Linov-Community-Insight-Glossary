export default ($axios) => {
  return {
    getGlossary: async (param) => {
      return await $axios.get("api/glossary", param);
    },
    getTypeInsight: async(param) => {
      return await $axios.get("api/glossary/types-insight", param);
    }
  };
};