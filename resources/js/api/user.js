import {COFE_CONFIG} from '../config.js';

export default {
    // GET /api/v1/user
    getUser() {
        return axios.get(COFE_CONFIG.API_URL + '/user');
    },

    putUpdateUser: function (public_visibility, favorite_coffee, flavor_notes, city, state) {
        return axios.put(COFE_CONFIG.API_URL + '/user',
            {
                public_visibility: public_visibility,
                favorite_coffee: favorite_coffee,
                flavor_notes: flavor_notes,
                city: city,
                state: state
            }
        );
    }
}
