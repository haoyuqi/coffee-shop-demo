import {COFE_CONFIG} from "../config";

export default {
    // GET api/v1/cafes
    getCafes: function () {
        return axios.get(COFE_CONFIG.API_URL + '/cafes');
    },

    // GET api/v1/cafes/{cafeID}
    getCafe: function (cafeID) {
        return axios.get(COFE_CONFIG.API_URL + '/cafes/' + cafeID)
    },

    // POST api/v1/cafes
    postAddNewCafe: function (name, address, city, state, zip) {
        return axios.post(COFE_CONFIG.API_URL + '/cafes', {
            name: name,
            address: address,
            city: city,
            state: state,
            zip: zip
        });
    }


}
