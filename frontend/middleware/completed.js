export default function ({ store, redirect }) {
    if (store.state.auth.loggedIn && !store.state.auth.user?.hasProfile){
        return redirect('/auth/fill-profile')
    }
    if (store.state.auth.loggedIn && !store.state.auth.user?.hasVerified){
        return redirect('/auth/verify-email')
    }
}