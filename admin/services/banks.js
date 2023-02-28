export default ($axios) => {
    return {
       getBanks: async  (param) => {
          return await $axios.get('api/bank', param)
       },
       getOneBanks: async  (param) => {
         return await $axios.get('api/bank/'+param)
       },
       postBanks: async  (param) => {
         return await $axios.post('api/bank', param)
       },
       updateBanks: async  (param, id) => {
         return await $axios.post('api/bank/'+id, param)
       },
       deleteBanks: async  (param) => {
         return await $axios.post('api/bank/deletes',param)
       },
    }
}