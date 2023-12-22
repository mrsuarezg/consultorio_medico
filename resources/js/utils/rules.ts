import { NATURAL_PERSON_ID } from "@data/constants";

export function validateRFC(value: string, personType: number) {
    if (!value) {
        return true;
    }
    if (personType === NATURAL_PERSON_ID) {
        return validateNaturalRFC(value);
    } else {
        return validateLegalRFC(value);
    }
}

export function validateNaturalRFC(value: string) {
    if (!value) {
        return true;
    }
    if (!/^([A-ZÑ][AEIOU][A-ZÑ]{2})([0-9]{6})([A-ZÑ0-9]{3})?$/.test(value)) {
        return 'RFC inválido';
    }
    return true;
}

export function validateLegalRFC(value: string) {
    if (!value) {
        return true;
    }
    if (!/^([A-ZÑ]{3})([0-9]{6})([A-ZÑ0-9]{3})$/.test(value)) {
        return 'RFC inválido';
    }
    return true;
}

export function validateEmail(value: string) {
    if (!value) {
        return true;
    }
    if (!/^(([^<>()[\]\\.,;:\s@"]+(\.[^<>()[\]\\.,;:\s@"]+)*)|(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/.test(value)) {
        return 'El correo electrónico es inválido.';
    }
    return true;
}

export function validatePhone(value: string) {
    if (!value) {
        return true;
    }
    if (!/^[2-9]\d{9}$/.test(value)) {
        return 'El número es inválido.';
    }
    return true;
}

export function validatePostalCode(value: string) {
    if (!value) {
        return true;
    }
    if (!/^[0-9]{5}$/.test(value) || value === '00000') {
        return 'El código postal es inválido.';
    }
    return true;
}

export function onlyNumber(value: string) {
    if (!value) {
        return true;
    }
    if (!/^[0-9]+$/.test(value)) {
        return 'Sólo se admiten números';
    }
    return true;
}

export function isRequired(value: any) {
    if (value === 0 || value === false) {
        return true;
    }
    if (!value) {
        return 'Campo obligatorio';
    }
    if (Array.isArray(value) && value.length <= 0) {
        return 'Campo obligatorio';
    }
    if (typeof value === 'string') {
        if (value.trim() === '') {
            return 'Campo obligatorio';
        }
    }
    return true;
}

export function withoutSpace(value: any) {
    if (typeof value !== 'string') {
        return true;
    }
    if (value !== value.trim()) {
        return 'No se permite espacio en blanco al inicio o al final.';
    }
    return true;
}

export function validateAmount(value: string) {
    if (!value) {
        return true;
    }
    if (!/^[0-9.,-]+$/.test(value)) {
        return 'Importe inválido';
    }
    return true;
}

export function minCharacters(min: number) {
    return (value: string | any[]) => {
        if (!value) {
            return true;
        }
        if (value.toString().length < min) {
            return `Valor mínimo ${min}`;
        }
        return true;
    }
}

export function maxCharacters(max: number) {
    return (text: string | any[]) => {
        if (!text) {
            return true;
        }
        return text.length <= max || `Sólo se admite hasta ${max} caracteres`;
    };
}

export function minMaxCharacters(min: number, max: number) {
    return (text: string | any[]) => {
        if (!text) {
            return true;
        }
        if (text.length < max && min === max) {
            return min !== 1 ? `Debe incluir ${min} caracteres` : 'Debe incluir un caracter';
        }
        if (text.length < min) {
            return min !== 1 ? `Debe incluir mínimo ${min} caracteres` : 'Debe incluir mínimo un caracter';
        }
        if (text.length > max) {
            return `Sólo se admite hasta ${max} caracteres`;
        }
        return true;
    };
}
