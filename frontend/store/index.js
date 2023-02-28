export const state = () => ({
    redirectUrl: '/',
 })
     
export const mutations = {
  SET_REDIRECT(state, path) {
    state.redirectUrl = path;
    if(process.client){
    localStorage.setItem('redirectUrl', path)
    }
  }
}