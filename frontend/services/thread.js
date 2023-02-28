export default ($axios) => {
    return {
       getMostDiscuses: async  (param) => {
          return await $axios.get('api/threads/most-discuses', param)
       },
       getThreads: async  (param) => {
        return await $axios.get('api/threads', param)
       },
       getThreadsCategory: async  (param, category) => {
         return await $axios.get(`api/threads/${category}`, param)
       },
       getOneThread: async  (param) => {
        return await $axios.get(`api/threads/${param}/show`)
       },
       getRelateThreads: async  (param) => {
         return await $axios.get(`api/threads/${param}/related`)
       },
       postThread : async (data) => {
        return await $axios.post('api/threads', data)
       },
       updateThread : async (data, id) => {
        return await $axios.post(`api/threads/${id}/edit`, data)
       },
       reportThread : async (data, id) => {
         return await $axios.post(`api/threads/${id}/report`, data)
       },
       deleteThread: async (data, id) => {
        return await $axios.delete(`api/threads/${id}`, data)
       },
       loveThread: async (data, id) => {
        return await $axios.post(`api/threads/${id}/love`, data)
       },
       bookMarkThread: async (data, id) => {
        return await $axios.post(`api/threads/${id}/bookmark`, data)
       },
       dismissThread: async (data, id) => {
        return await $axios.post(`api/threads/${id}/dismiss`, data)
       },
       pollingThread: async (data, id) => {
        return await $axios.post(`api/threads/${id}/polling`, data)
       },
       getComments: async  (param) => {
        return await $axios.get(`api/comments/${param}`)
       },
       postComment: async (data, id) => {
        return await $axios.post(`api/comments/${id}`, data)
       },
       deleteComment: async (data, id) => {
        return await $axios.delete(`api/comments/${id}`, data)
       },
       loveComment: async (data, id) => {
        return await $axios.post(`api/comments/${id}/love`, data)
       },
       getCategories: async  (param) => {
        return await $axios.get('api/types', param)
       },
       getPollDuration: async  (param) => {
        return await $axios.get('api/poll-duration', param)
       },
    }
}