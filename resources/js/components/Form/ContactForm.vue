<script setup>
import { computed } from 'vue';
import { validatePhone, validateEmail, isRequired } from '@utils/rules';

const props = defineProps({
    phone: {
        type: [Object, String, null],
        required: false,
    },
    cellphone: {
        type: [Object, String, null],
        required: false,
    },
    email: {
        type: [Object, String, null],
        required: false,
    },
    lists: {
        type: Object,
        required: false,
        default: {
            // phone: [],
            // cellphone: [],
            // email: [],
        },
    },
    notRequired: {
        type: Array,
        required: false,
        default: [
            // 'phone',
            // 'cellphone',
            // 'email',
        ],
    },
    readonly: {
        type: Array,
        required: false,
        default: [],
    },
    disabled: {
        type: Array,
        required: false,
        default: [],
    },
    customProps: {
        type: Object,
        required: false,
        default: {
            phone: {},
            cellphone: {},
            email: {},
        },
    },
    customRules: {
        type: Object,
        required: false,
        default: {
            phone: [],
            cellphone: [],
            email: [],
        },
    },
    md: {
        type: [String, Number, Boolean],
        required: false,
        default: 3,
    },
    writable: {
        type: Object,
        required: false,
        default: [
            // 'phone',
            // 'cellphone',
            // 'email',
        ],
    },
    addTitle: {
        type: Boolean,
    },
});

const emit = defineEmits([
    'update:phone',
    'update:cellphone',
    'update:email',
]);

const modelPhone = computed({
    get: () => props.phone,
    set: (value) => {
        emit('update:phone', value);
    }
});

const modelCellphone = computed({
    get: () => props.cellphone,
    set: (value) => {
        emit('update:cellphone', value);
    }
});

const modelEmail = computed({
    get: () => props.email,
    set: (value) => {
        emit('update:email', value);
    }
});

const phoneSetting = computed(() => {
    const inputProps = setInputProps('phone');
    inputProps.rules.push(validatePhone);
    return inputProps;
});

const cellphoneSetting = computed(() => {
    const inputProps = setInputProps('cellphone');
    inputProps.rules.push(validatePhone);
    return inputProps;
});

const emailSetting = computed(() => {
    const inputProps = setInputProps('email');
    inputProps.rules.push(validateEmail);
    return inputProps;
});

const phoneList = computed(() => {
    const isSetted = isSetedList('phone');
    if (!isSetted) {
        console.warn('The phone list is not setted!');
    }
    return isSetted ? props.lists.phone : [];
});

const cellphoneList = computed(() => {
    const isSetted = isSetedList('cellphone');
    if (!isSetted) {
        console.warn('The cellphone list is not setted!');
    }
    return isSetted ? props.lists.cellphone : [];
});

const emailList = computed(() => {
    const isSetted = isSetedList('email');
    if (!isSetted) {
        console.warn('The email list is not setted!');
    }
    return isSetted ? props.lists.email : [];
});

function setInputProps(inputName) {
    const inputProps = {
        rules: [],
    };
    if (props?.customProps?.[inputName]) {
        Object.assign(inputProps, props.customProps[inputName]);
    }

    if (isInputRequiredAndEnabled(inputName)) {
        inputProps.rules.push(isRequired);
    }

    if (props?.customRules?.[inputName]) {
        inputProps.rules.push(...props.customRules[inputName]);
    }

    if (isInputReadonly(inputName)) {
        Object.assign(inputProps, {
            variant: 'plain',
            'menu-icon': '',
            readonly: true,
        });
    }

    if (isInputDisabled(inputName)) {
        Object.assign(inputProps, {
            disabled: true,
        });
    }
    return inputProps;
}

function renderInput(inputName) {
    return props?.[inputName] !== undefined;
}

function isInputRequired(inputName) {
    return !(props.notRequired.includes(inputName) || props.notRequired.includes('all'));
}

function isInputRequiredAndEnabled(inputName) {
    return isInputRequired(inputName) && !(isInputReadonly(inputName) || isInputDisabled(inputName));
}

function isInputReadonly(inputName) {
    return props.readonly.includes(inputName) || props.readonly.includes('all');
}

function isInputDisabled(inputName) {
    return props.disabled.includes(inputName) || props.disabled.includes('all');
}

function addAsterisk(inputName) {
    return isInputRequiredAndEnabled(inputName) ? '*' : '';
}

// LOCAL FUNCTIONS
function isInputWritable(inputName) {
    return props.writable.includes(inputName) || props.writable.includes('all');
}

function isSetedList(listName) {
    if (!Object.keys(props.lists).includes(listName)) {
        return false;
    }
    return props?.lists?.[listName];
}
</script>

<template>
    <h4 v-if="addTitle" class="mt-4 mb-2">Contacto</h4>
    <VRow>
        <VCol cols="12" :md="md" v-if="renderInput('phone')">
            <VTextField v-if="isInputWritable('phone')" :label="`Número telefónico ${addAsterisk('phone')}`"
                v-model="modelPhone" v-bind="phoneSetting" counter="10" type="tel" />

            <VAutocomplete v-else :label="`Número telefónico ${addAsterisk('phone')}`" v-model="modelPhone"
                :items="phoneList" item-title="phone" return-object v-bind="phoneSetting">
                <template #no-data>
                    <span class="px-4">No hay información disponible</span>
                </template>
            </VAutocomplete>
        </VCol>

        <VCol cols="12" :md="md" v-if="renderInput('cellphone')">
            <VTextField v-if="isInputWritable('cellphone')" :label="`Número celular ${addAsterisk('cellphone')}`"
                v-model="modelCellphone" v-bind="cellphoneSetting" counter="10" type="tel" />

            <VAutocomplete v-else :label="`Número celular ${addAsterisk('cellphone')}`" v-model="modelCellphone"
                :items="cellphoneList" item-title="phone" return-object v-bind="cellphoneSetting">
                <template #no-data>
                    <span class="px-4">No hay información disponible</span>
                </template>
            </VAutocomplete>
        </VCol>

        <VCol cols="12" :md="md" v-if="renderInput('email')">
            <VTextField v-if="isInputWritable('email')" :label="`Correo electrónico ${addAsterisk('email')}`"
                v-model="modelEmail" v-bind="emailSetting" type="email" />

            <VAutocomplete v-else :label="`Correo electrónico ${addAsterisk('email')}`" v-model="modelEmail"
                :items="emailList" item-title="email" return-object v-bind="emailSetting">
                <template #no-data>
                    <span class="px-4">No hay información disponible</span>
                </template>
            </VAutocomplete>
        </VCol>
    </VRow>
</template>
