import {COFE_CONFIG} from "../config";

export default {
    getBrewMethods: function () {
        return axios.get(COFE_CONFIG.API_URL+'/brew-methods');
    }
}
