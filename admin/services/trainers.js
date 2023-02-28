export default ($axios) => {
    return {
       getTrainers: async  (param) => {
          return await $axios.get('api/trainer', param)
       },
       getOneTrainers: async  (param) => {
         return await $axios.get('api/trainer/'+param)
       },
       postTrainers: async  (param) => {
         return await $axios.post('api/trainer', param)
       },
       updateTrainers: async  (param, id) => {
         return await $axios.post('api/trainer/'+id, param)
       },
       deleteTrainers: async  (param) => {
         return await $axios.post('api/trainer/deletes',param)
       },
    }
}