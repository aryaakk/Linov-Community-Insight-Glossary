export default ($axios) => {
    return {
       getSkills: async  (param) => {
          return await $axios.get('api/skills', param)
       },
       getOneSkills: async  (param) => {
         return await $axios.get('api/skills/'+param)
       },
       postSkills: async  (param) => {
         return await $axios.post('api/skills', param)
       },
       updateSkills: async  (param, id) => {
         return await $axios.put('api/skills/'+id, param)
       },
       deleteSkills: async  (param) => {
         return await $axios.post('api/skills/deletes',param)
       },
    }
}