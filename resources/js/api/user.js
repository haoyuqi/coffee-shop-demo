import {COFE_CONFIG} from '../config.js';

export default {
    // GET /api/v1/user
    getUser() {
        return axios.get(COFE_CONFIG.API_URL + '/user');
    }
}
