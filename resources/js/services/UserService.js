import axios from '@axios';

export const UserService = {
    // GET ALL USERS
    fetchAll(params) {
        return axios.post('/user', params);
    },

    create(data) {
        return axios.post('/user', data);
    },

    update(id, data) {
        return axios.put(`/user/${id}`, data);
    },
};
