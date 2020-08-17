import CafesAPI from '../api/cafe.js';

export const cafes = {
    state: {
        cafes: [],
        cafesLoadStatus: 0,

        cafe: {},
        cafeLoadStatus: 0,

        cafeAddStatus: 0,

        cafeLikeActionsStatus: 0,
        cafeUnlikeActionStatus: 0,

        cafeLiked: false,
    },

    actions: {
        loadCafes({commit}) {
            commit('setCafesLoadStatus', 1);

            CafesAPI.getCafes()
                .then(function (response) {
                    commit('setCafes', response.data);
                    commit('setCafesLoadStatus', 2);
                })
                .catch(function (response) {
                    commit('setCafes', []);
                    commit('setCafesLoadStatus', 3);
                });
        },

        loadCafe({commit}, data) {
            commit('setCafeLikedStatus', false);
            commit('setCafeLoadStatus', 1);

            CafesAPI.getCafe(data.id)
                .then(function (response) {
                    commit('setCafe', response.data);
                    if (response.data.user_like.length > 0) {
                        commit('setCafeLikedStatus', true);
                    }
                    commit('setCafeLoadStatus', 2);
                })
                .catch(function () {
                    commit('setCafe', {});
                    commit('setCafeLoadStatus', 3);
                });
        },

        addCafe({commit, state, dispatch}, data) {
            commit('setCafeAddStatus', 1);

            CafesAPI.postAddNewCafe(data.name, data.locations, data.website, data.description, data.roaster)
                .then(function (response) {
                    commit('setCafeAddStatus', 2);
                    dispatch('loadCafes');
                })
                .catch(function () {
                    commit('setCafeAddStatus', 3);
                });
        },

        likeCafe({commit, state}, data) {
            commit('setCafeLikeActionStatus', 1);

            CafesAPI.postLikeCafe(data.id)
                .then(response => {
                    commit('setCafeLikeActionStatus', 2);
                    commit('setCafeLiked', true);
                })
                .catch(response => {
                    commit('setCafeLikeActionStatus', 3);
                })
        },

        unlikeCafe({commit, state}, data) {
            commit('setCafeUnlikeActionStatus', 1);

            CafesAPI.deleteLikeCafe(data.id)
                .then(response => {
                    commit('setCafeUnlikeActionStatus', 2);
                    commit('setCafeLiked', false);
                })
                .catch(response => {
                    commit('setCafeUnlikeActionStatus', 3);
                })
        }
    },

    mutations: {
        setCafesLoadStatus(state, status) {
            state.cafesLoadStatus = status;
        },

        setCafes(state, cafes) {
            state.cafes = cafes;
        },

        setCafeLoadStatus(state, status) {
            state.cafeLoadStatus = status;
        },

        setCafe(state, cafe) {
            state.cafe = cafe;
        },

        setCafeAddStatus(state, status) {
            state.cafeAddStatus = status;
        },

        setCafeLikedStatus(state, status) {
            state.cafeLiked = status;
        },

        setCafeLikeActionStatus(state, statue) {
            state.cafeLikeActionsStatus = statue;
        },

        setCafeUnlikeActionStatus(state, status) {
            state.cafeUnlikeActionStatus = status;
        }
    },

    getters: {
        getCafesLoadStatus(state) {
            return state.cafesLoadStatus;
        },

        getCafes(state) {
            return state.cafes;
        },

        getCafeLoadStatus(state) {
            return state.cafeLoadStatus;
        },

        getCafe(state) {
            return state.cafe;
        },

        getCafeAddStatus(state) {
            return state.cafeAddStatus;
        },

        getCafeLikedStatus(state) {
            return state.cafeLiked;
        },

        getCafeLikeActionStatus(state) {
            return state.cafeLikeActionsStatus;
        },

        getCafeUnlikeActionStatus(state) {
            return state.cafeUnlikeActionStatus;
        }
    }
}
