export default ($axios) => {
    return {
       getNotifications: async  (param) => {
          return await $axios.get('api/notifications', param)
       },
       readNotifications: async  (param) => {
        return await $axios.post('api/notifications', param)
       },
    }
}