export default function ({ store, redirect, route }) {
    if (!store.state.auth.loggedIn) {
        store.commit('SET_REDIRECT', route.path) 
        return redirect('/auth/login')
    }
}