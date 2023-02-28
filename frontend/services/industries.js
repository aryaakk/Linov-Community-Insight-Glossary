export default ($axios) => {
    return {
       getIndustries: async  (param) => {
          return await $axios.get('api/industries', param)
       }
    }
}