import { isToday } from './Validators/index'
import isBetween from 'dayjs/plugin/isBetween'
import dayjs from 'dayjs'
dayjs.extend(isBetween)

export const avatarText = value => {
    if (!value)
        return ''
    const nameArray = value.split(' ')

    return nameArray.map(word => word.charAt(0).toUpperCase()).join('')
}

// TODO: Try to implement this: https://twitter.com/fireship_dev/status/1565424801216311297
export const kFormatter = num => {
    const regex = /\B(?=(\d{3})+(?!\d))/g

    return Math.abs(num) > 9999 ? `${Math.sign(num) * +((Math.abs(num) / 1000).toFixed(1))}k` : Math.abs(num).toFixed(0).replace(regex, ',')
}

/**
 * Format and return date in Humanize format
 * Intl docs: https://developer.mozilla.org/en-US/docs/Web/JavaScript/Reference/Global_Objects/Intl/DateTimeFormat/format
 * Intl Constructor: https://developer.mozilla.org/en-US/docs/Web/JavaScript/Reference/Global_Objects/Intl/DateTimeFormat/DateTimeFormat
 * @param {String} value date to format
 * @param {Intl.DateTimeFormatOptions} formatting Intl object to format with
 */
export const formatDate = (value, formatting = { month: 'short', day: 'numeric', year: 'numeric' }) => {
    if (!value)
        return value

    return new Intl.DateTimeFormat('en-US', formatting).format(new Date(value))
}

/**
 * Return short human friendly month representation of date
 * Can also convert date to only time if date is of today (Better UX)
 * @param {String} value date to format
 * @param {Boolean} toTimeForCurrentDay Shall convert to time if day is today/current
 */
export const formatDateToMonthShort = (value, toTimeForCurrentDay = true) => {
    const date = new Date(value)
    let formatting = { month: 'short', day: 'numeric' }
    if (toTimeForCurrentDay && isToday(date))
        formatting = { hour: 'numeric', minute: 'numeric' }

    return new Intl.DateTimeFormat('en-US', formatting).format(new Date(value))
}

export const getTicketStatusColor = (statusName) => {
    if (typeof statusName !== 'string') {
        return;
    }
    let value = statusName.toLowerCase();
    return { iniciado: 'success', cerrado: 'error', asignado: 'info', cotización: 'warning', emisión: 'purple' }[value] || 'secondary';
};

export const getAttentionColor = (index) => {
    if (typeof index !== 'number') {
        return 'secondary';
    }
    return { 1: 'info', 2: 'warning', 3: 'error' }[index] || 'secondary';
};

export const getPaymentStatusColor = (paymentStatusName) => {
    if (typeof paymentStatusName !== 'string') {
        return;
    }
    let value = paymentStatusName.toLowerCase();
    return { cancelado: 'error', pendiente: 'warning', pagado: 'success' }[value] || 'secondary';
};

export const getLocalDateByUTC = (utc_date, withHours = true) => {
    if (typeof utc_date !== 'string') {
        return 'Fecha inválida';
    }
    const options = {
        year: 'numeric',
        month: '2-digit',
        day: '2-digit',
    };
    if (withHours) {
        options.hour = '2-digit';
        options.minute = '2-digit';
    }
    return new Date(utc_date).toLocaleDateString('es-mx', options);
}

export const formatUTCDateToLocal = (utcDate) => {
    const date = new Date(utcDate);
    return `${date.getFullYear()}-${date.getMonth().toString().padStart(2, '0')}-${date.getDay().toString().padStart(2, '0')}`;
}

export const numberToCurrency = (number) => {
    if (Number.isNaN(Number.parseFloat(number))) {
        return '$0.00';
    }
    return new Intl.NumberFormat('en-US', {
        style: "currency",
        currency: "USD",
        maximumFractionDigits: 2,
    }).format(number);
}

export const formatNumber = (num, dec = 2, round = false) => {
    const factor = Math.pow(10, dec);

    if (round) {
        return Math.round(num * factor) / factor;
    }

    return Math.floor(num * factor) / factor;
}

export const getAge = (date) => {
    if (date == null) {
        return 0;
    }
    const dob = new Date(date);
    if (dob.toString() === 'Invalid Date') {
        return -1;
    }
    //calculate month difference from current date in time
    dob.setHours(0, 0, 0);
    const month_diff = Date.now() - dob.getTime();

    //convert the calculated difference in date format
    const age_dt = new Date(month_diff);

    //extract year from date
    const year = age_dt.getUTCFullYear();

    //now calculate the age of the user
    const age = Math.abs(year - 1970);

    //display the calculated age
    return age;
};

export const capitalizeText = text => {
    const firstLetter = text.charAt(0).toUpperCase();
    return firstLetter + text.substring(1).toLowerCase();
};

export const calculateNights = (userCheckInDate, userCheckOutDate, hotelCheckInTime, hotelCheckOutTime) => {
    if (!userCheckInDate || !userCheckOutDate || !hotelCheckInTime || !hotelCheckOutTime) {
        console.error('Missing parameters in calculateNights function');
        return 0;
    }
    if (typeof userCheckInDate !== 'string' || typeof userCheckOutDate !== 'string' || typeof hotelCheckInTime !== 'string' || typeof hotelCheckOutTime !== 'string') {
        console.error('Invalid parameters in calculateNights function string');
        return 0;
    }

    let checkIn = dayjs(userCheckInDate, "DD-MM-YYYY HH:mm");
    let checkOut = dayjs(userCheckOutDate, "DD-MM-YYYY HH:mm");

    const [hotelCheckInHour, hotelCheckInMinute] = hotelCheckInTime.split(':').map(Number);
    const [hotelCheckOutHour, hotelCheckOutMinute] = hotelCheckOutTime.split(':').map(Number);

    // Adjust check-in and check-out times based on hotel timings
    if (checkIn.isAfter(checkIn.set({ hour: hotelCheckInHour, minute: hotelCheckInMinute }))) {
        checkIn = checkIn.add(1, 'day');
    }
    checkIn = checkIn.set({ hour: hotelCheckInHour, minute: hotelCheckInMinute });

    if (checkOut.isAfter(checkOut.set({ hour: hotelCheckOutHour, minute: hotelCheckOutMinute }))) {
        checkOut = checkOut.add(1, 'day');
    }
    checkOut = checkOut.set({ hour: hotelCheckOutHour, minute: hotelCheckOutMinute });

    // Calculate nights based on the adjusted times
    const nights = checkOut.diff(checkIn, 'day');

    return nights;
};

/**
 * @description Function for processing a schedule string and returning an object with the start and end times
 * @param {string} scheduleString - String with the schedule in format 'HH:mm-HH:mm' or 'HH:mm-24:00' or 'HH:mm/HH:mm' or 'HH:mm/24:00' or 'HH:mm-24:00/HH:mm' or 'HH:mm-24:00/24:00'
 * @returns {object} Object with the start and end times
 * @throws {Error} If the schedule string is not in a valid format
 * @example
 * processSchedule('08:00-16:00'); // { startTime: '08:00', endTime: '16:00' }
 * processSchedule('08:00-24:00'); // { startTime: '08:00', endTime: '00:00' }
 * processSchedule('08:00/16:00'); // { startTime: '08:00', endTime: '16:00' }
 * processSchedule('08:00/24:00'); // { startTime: '08:00', endTime: '00:00' }
*/

export const processSchedule = (scheduleString) => {
    // Usamos una regex para extraer las horas en formato HH:mm
    const matches = scheduleString.match(/(\d{2}:\d{2})/g);

    if (matches && matches.length >= 2) {
        return {
            startTime: matches[0],
            endTime: matches[1] === '24:00' ? '00:00' : matches[1]
        };
    } else {
        throw new Error('Formato de horario no válido');
    }
}

// validate is date time in format YYYY-MM-DD HH:mm in 24 hours format, when 24 its 00:00
export const validateDateTime = (dateTime) => {
    // Intentar parsear la fecha con dayjs
    const parsedDate = dayjs(dateTime);

    // Si dayjs pudo parsear la fecha y coincide con el formato 'YYYY-MM-DD HH:mm', es válida
    return parsedDate.isValid() && parsedDate.format('YYYY-MM-DD HH:mm') === dateTime;
}

export const formatDateTime = (dateTime) => {
    if (!dateTime) {
        return dateTime;
    }

    const parsedDate = dayjs(dateTime);
    return parsedDate.format('YYYY-MM-DD HH:mm');
}

export const calculateMeals = (start, end, mealSchedules) => {
    if (!start || !end || !mealSchedules) {
        return {};
    }

    if (typeof start !== 'string' || typeof end !== 'string' || !Array.isArray(mealSchedules)) {
        return {};
    }

    const startDate = dayjs(start, "DD-MM-YYYY HH:mm");
    const endDate = dayjs(end, "DD-MM-YYYY HH:mm");
    const mealsResult = {};

    // Iterar sobre cada comida y su horario
    mealSchedules.forEach(meal => {
        const { name, startTime, endTime, amount, finance_item_id } = meal;
        let mealStart = startDate.clone().set('hour', startTime.split(':')[0]).set('minute', startTime.split(':')[1]);
        let mealEnd = startDate.clone().set('hour', endTime.split(':')[0]).set('minute', endTime.split(':')[1]);

        if (mealEnd.isBefore(mealStart)) {
            mealEnd = mealEnd.add(1, 'day');
        }

        let count = 0;
        while (mealStart.isBefore(endDate)) {
            if (
                (mealStart.isBetween(startDate, endDate, null, '[)') || mealEnd.isBetween(startDate, endDate, null, '(]'))
            ) {
                count++;
            }

            mealStart = mealStart.add(1, 'day');
            mealEnd = mealEnd.add(1, 'day');
        }

        mealsResult[name] = { count, amount, finance_item_id };
    });
    return mealsResult;
}
