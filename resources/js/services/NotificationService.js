import axios from '@axios';

export const NotificationService = {
    // 👉 Fetch all notifications
    fetchAll(params) {
        return axios.get('/notifications', { params });
    },

    // 👉 Create notification
    sendReminder(params) {
        return axios.post('/notifications', params);
    },
};
