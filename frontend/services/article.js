export default ($axios) => {
  return {
    getArticle: async (param) => {
      return await $axios.get("api/article", param);
    },
    getPremiumArticle: async (param) => {
      return await $axios.get("api/article/premium", param);
    },
    getPopularArticle: async (param) => {
      return await $axios.get("api/article/popular", param);
    },
    getOneArticle: async (param) => {
      return await $axios.get(`api/article/${param}/show`);
    },
    getPreviewArticle: async (param) => {
      return await $axios.get(`api/article/${param}/show?preview=true`);
    },
    postCommentArticle: async (data) => {
      return await $axios.post(`/api/article/comment`, data);
    },
    deleteCommentArticle: async (id) => {
      return await $axios.delete(`api/article/comment/${id}`);
    },
  };
};
