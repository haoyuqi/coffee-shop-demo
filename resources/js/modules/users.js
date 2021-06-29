import UserAPI from '../api/user.js';

export const users = {
    state: {
        user: {},
        userLoadStatus: 0
    },
    actions: {
        loadUser({commit}) {
            commit('setUserLoadStatus', 1);

            UserAPI.getUser()
                .then(function (response) {
                    commit('setUser', response.data);
                    commit('setUserLoadStatus', 2);
                })
                .catch(function (response) {
                    commit('setUser', {});
                    commit('setUserLoadStatus', 3);
                })
        },

        logoutUser({commit}) {
            commit('setUser', {});
            commit('setUserLoadStatus', 0);
        }
    },
    mutations: {
        setUserLoadStatus(state, status) {
            state.userLoadStatus = status;
        },
        setUser(state, user) {
            state.user = user;
        }
    },
    getters: {
        getUserLoadStatus(state) {
            return function () {
                return state.userLoadStatus;
            }
        },
        getUser(state) {
            return state.user;
        }
    }
}
