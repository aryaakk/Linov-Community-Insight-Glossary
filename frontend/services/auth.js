  export default ($axios) => {
    return {
       getCsrfCookie: () => {
         return $axios.get("sanctum/csrf-cookie")
       },
       registerUser: async  (data) => {
          return await $axios.post("register", data)
       },
       verifyEmail: async (data) => {
         return await $axios.post("email/verification-notification", data)
       },
       complateProfile: async  (data) => {
         return await $axios.post("complate-profile", data)
       },
       forgotPassword: async (data) => {
         return await $axios.post("forgot-password", data)
       },
       resetPassword: async (data) => {
         return await $axios.post("reset-password", data)
       },
    }
}