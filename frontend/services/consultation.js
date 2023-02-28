export default ($axios) => {
    return {
      getConsultations: async  (param) => {
        return await $axios.get('api/consulting', param)
      },
      getOneConsult: async  (param) => {
        return await $axios.get(`api/consulting/${param}/show`)
      },
      postConsultThread : async (data) => {
        return await $axios.post('api/consulting', data)
      },
      deleteConsultThread: async (data, id) => {
        return await $axios.delete(`api/consulting/${id}`, data)
      },
      loveConsultThread: async (data, id) => {
        return await $axios.post(`api/consulting/${id}/love`, data)
      },
      getConsultComments: async  (param) => {
        return await $axios.get(`api/consulting/comments/${param}`)
      },
      postConsultComment: async (data, id) => {
        return await $axios.post(`api/consulting/comments/${id}`, data)
      },
      deleteConsultComment: async (data, id) => {
        return await $axios.delete(`api/consulting/comments/${id}`, data)
      },
    }
}