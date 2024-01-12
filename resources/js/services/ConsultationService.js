import axios from '@axios';

export const ConsultationService = {
    /**
    * @param {*} params
    * @example
    *  {
    *
    *  }
    **/
    create(data) {
        return axios.post('/consultation/store', data);
    },

    fetch(data) {
        return axios.post('/consultation', data);
    },

    update(id, data) {
        return axios.put(`/consultation/${id}/update`, data);
    },
};
