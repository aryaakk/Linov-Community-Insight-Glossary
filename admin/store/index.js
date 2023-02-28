export const state = () => ({
    breadcrumbs: null,
 })
     
 export const mutations = {
   SET_BREADCRUMB(state, path) {
     state.breadcrumbs = path;
   }
 }