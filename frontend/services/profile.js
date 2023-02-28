export default ($axios) => {
    return {
       getProfile: async  (param) => {
          return await $axios.get('api/profile/user', param)
       },
       getUserProfile: async  (param, id) => {
        return await $axios.get('api/profile/user/'+id, param)
     },
       updateProfile : async (data) => {
        return await $axios.post('api/profile', data)
       },
       updatePassword: async (data) => {
         return await $axios.post('api/profile/password', data)
       },
       updateNotification: async (data) => {
        return await $axios.post('api/profile/notification', data)
       },
       getUserThreads: async  (param) => {
        return await $axios.get('api/profile/threads', param)
       },
       getLikedThreads: async  (param) => {
        return await $axios.get('api/profile/like', param)
       },
       getBookmarkedThreads: async  (param) => {
        return await $axios.get('api/profile/bookmark', param)
       },
       getConsultThreads: async  (param) => {
         return await $axios.get('api/profile/consultation', param)
       },
       getCommentThreads: async  (param) => {
         return await $axios.get('api/profile/comment', param)
       },
       getNotification: async  (param) => {
         return await $axios.get('api/profile/notification', param)
       },
       getUniversity: async  (param) => {
          return await $axios.get('api/university', param)
       },
       getDegrees: async  (param) => {
        return await $axios.get('api/degrees', param)
       },
       getMajors: async  (param) => {
        return await $axios.get('api/majors', param)
       },
       getSkills: async  (param) => {
         return await $axios.get('api/skills', param)
       },
    }
}