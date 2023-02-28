export default ($axios) => {
    return {
       getIndexBanners: async  (param) => {
          return await $axios.get('api/training/premium', {params: param})
       },
       getCalendar: async  (param) => {
        return await $axios.get(`api/training/calendar?start=${param.start}&end=${param.end}`)
       },
       getAllTraining: async  (param) => {
        return await $axios.get('api/training/training', {params: param})
       },
       getAllEvent: async  (param) => {
        return await $axios.get('api/training/event', {params: param})
       },
       getAllTrainingEvent: async  (param) => {
         return await $axios.get(`api/training/${param.type}`, {params: param})
       },
       getOneTrainingEvent: async  (param) => {
        return await $axios.get(`api/training/${param.type}/${param.id}`)
       },
       getAllTrainer: async  (param) => {
        return await $axios.get('api/trainer', {params: param})
       },
       getOneTrainer: async  (param) => {
        return await $axios.get(`api/trainer/${param.id}`)
       },
       getTrainerCourse: async  (param) => {
         return await $axios.get(`api/trainer/${param.id}/course`)
       },
       getAllProvider: async  (param) => {
        return await $axios.get('api/provider', {params: param})
       },
       getOneProvider: async  (param) => {
        return await $axios.get(`api/provider/${param.id}`)
       },
       getAllSyllabus: async  (param) => {
        return await $axios.get(`api/syllabus/${param.id}`)
       },
       getSchedule: async  (param) => {
        return await $axios.get(`api/schedule/${param.id}`)
       },
       getOneSchedule: async  (param) => {
        return await $axios.get(`api/schedule/${param.id}/show`, {params: param})
       },
       getAllBank: async  (param) => {
        return await $axios.get('api/bank', {params: param})
       },
       postOrder: async  (param) => {
        return await $axios.post('api/order', param)
       },
       postFreeOrder: async  (param) => {
        return await $axios.post('api/order/event/free', param)
       },
       uploadOrder: async  (param, id) => {
        return await $axios.post(`api/order/${id}`, param)
       },
       getOneOrder: async  (param) => {
        return await $axios.get(`api/order/${param.id}`)
       }
    }
}