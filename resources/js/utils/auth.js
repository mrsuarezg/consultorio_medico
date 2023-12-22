function checkValues(userValues, value) {
    if (!Array.isArray(userValues)) return false;

    if (value.includes('|')) {
        return value.split('|').some(item => userValues.includes(item));
    } else if (value.includes('&')) {
        return value.split('&').every(item => userValues.includes(item));
    } else {
        return userValues.includes(value);
    }
}

export function can(user, value) {
    if (user?.data?.roles?.includes('super_admin')) return true;
    return checkValues(user?.data?.permissions, value);
}

export function is(user, value) {
    if (user?.data?.roles?.includes('super_admin')) return true;
    return checkValues(user?.data?.roles, value);
}
