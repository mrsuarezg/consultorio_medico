import axios from '@axios';

export const PatientService = {
    /**
    * @param {*} params
    * @example
    *  {
    *
    *  }
    **/
    create(data) {
        return axios.post('/patient/store', data);
    },

    fetch(data) {
        return axios.post('/patient', data);
    },

    update(id, data) {
        return axios.put(`/patient/${id}/update`, data);
    },
};
