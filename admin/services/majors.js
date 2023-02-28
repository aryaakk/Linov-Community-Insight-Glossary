export default ($axios) => {
    return {
       getMajors: async  (param) => {
          return await $axios.get('api/majors', param)
       },
       getOneMajors: async  (param) => {
         return await $axios.get('api/majors/'+param)
       },
       postMajors: async  (param) => {
         return await $axios.post('api/majors', param)
       },
       updateMajors: async  (param, id) => {
         return await $axios.put('api/majors/'+id, param)
       },
       deleteMajors: async  (param) => {
         return await $axios.post('api/majors/deletes',param)
       },
    }
}