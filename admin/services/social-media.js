export default ($axios) => {
    return {
       getSocials: async  (param) => {
          return await $axios.get('api/social-media', param)
       },
       getOneSocials: async  (param) => {
         return await $axios.get('api/social-media/'+param)
       },
       postSocials: async  (param) => {
         return await $axios.post('api/social-media', param)
       },
       updateSocials: async  (param, id) => {
         return await $axios.put('api/social-media/'+id, param)
       },
       deleteSocials: async  (param) => {
         return await $axios.post('api/social-media/deletes',param)
       },
    }
}