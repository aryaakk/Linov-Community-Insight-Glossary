export default ($axios) => {
    return {
       getAllSyllabus: async  (param) => {
        return await $axios.get(`api/syllabus/${param}`)
       },
       getOneSyllabus: async  (param) => {
         return await $axios.get('api/syllabus/'+param)
       },
       postSyllabus: async  (param) => {
         return await $axios.post('api/syllabus', param)
       },
       updateSyllabus: async  (param, id) => {
         return await $axios.put('api/syllabus/'+id, param)
       },
       deleteSyllabus: async  (param) => {
         return await $axios.delete('api/syllabus/'+param)
       },
    }
}