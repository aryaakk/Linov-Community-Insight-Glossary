export default ($axios) => {
    return {
       getSocials: async  (param) => {
          return await $axios.get('api/socials', param)
       }
    }
}