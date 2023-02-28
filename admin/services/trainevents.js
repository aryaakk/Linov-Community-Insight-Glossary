export default ($axios) => {
    return {
       getEvents: async  (param) => {
          return await $axios.get('api/training/event', param)
       },
       getTrainings: async  (param) => {
        return await $axios.get('api/training/training', param)
      },
       getOneEvents: async  (param) => {
         return await $axios.get('api/training/event/'+param)
       },
       getOneTrainings: async  (param) => {
        return await $axios.get('api/training/training/'+param)
      },
       postTrainEvents: async  (param) => {
         return await $axios.post('api/training', param)
       },
       updateTrainEvents: async  (param, id) => {
         return await $axios.post('api/training/'+id, param)
       },
       deleteTrainEvents: async  (param) => {
         return await $axios.post('api/training/deletes',param)
       },
       getSchedule: async  (param) => {
        return await $axios.get(`api/schedule/${param}`)
       },
    }
}