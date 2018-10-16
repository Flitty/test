export default {
    setLastRoute({ commit }, route) {
        commit('setLastRoute', route || localStorage.getItem('setLastRoute'));
    },
    setToken({ commit }, api_token) {
        let token = api_token || localStorage.getItem('api_token');

        if (token && token !== "null") {
            commit('initialize', token);
        }
    },
    setUserData({ commit }, data) {
        let avatar = data && data.avatar || localStorage.getItem('user_avatar');
        let name = data && data.name || localStorage.getItem('user_name');

        commit('setUserAvatar', avatar);
        commit('setUserName', name);
    }
}