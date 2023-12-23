<script setup>
import { computed, defineProps, ref, onMounted } from 'vue';
import { NATURAL_PERSON_ID } from '@data/constants';
import { genderList } from '@data/lists';
import { isRequired, validateRFC } from '@utils/rules';
import { GeneralService } from '@services/GeneralService.js';
import { getAge } from '@utils/formatters';
import dayjs from 'dayjs';
import { useDebounceFn } from '@vueuse/core';

const props = defineProps({
    personType: {
        type: [Number, null],
        required: false,
    },
    fullName: {
        type: [String, null],
        required: false,
    },
    name: {
        type: [String, null],
        required: false,
    },
    lastName: {
        type: [String, null],
        required: false,
    },
    surname: {
        type: [String, null],
        required: false,
    },
    businessName: {
        type: [String, null],
        required: false,
    },
    rfc: {
        type: [String, null],
        required: false,
    },
    gender: {
        type: [String, null],
        required: false,
    },
    birthdate: {
        type: [String, null],
        required: false,
    },
    staticPersonType: {
        type: Number,
        required: false,
        default: NATURAL_PERSON_ID,
    },
    notRequired: {
        type: Array,
        required: false,
        default: [],
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
            personType: {},
            name: {},
            lastName: {},
            surname: {},
            businessName: {},
            rfc: {},
            gender: {},
            birthdate: {},
        },
    },
    customRules: {
        type: Object,
        required: false,
        default: {
            personType: [],
            name: [],
            lastName: [],
            surname: [],
            businessName: [],
            rfc: [],
            gender: [],
            birthdate: [],
        },
    },
    lists: {
        type: Object,
        required: false,
        default: {
            // personType: [],
            // gender: [],
        },
    },
    md: {
        type: [String, Number, Boolean],
        required: false,
        default: 3,
    },
    addTitle: {
        type: Boolean,
        required: false,
        default: false,
    },
    getRfc: {
        type: Boolean,
        required: false,
        default: false,
    },
});

const emit = defineEmits([
    'update:personType',
    'update:fullName',
    'update:name',
    'update:lastName',
    'update:surname',
    'update:businessName',
    'update:rfc',
    'update:gender',
    'update:birthdate',
    'error',
    'ready',
    'loading',
]);

const personTypeList = ref([]);
const localGenderList = ref([]);
const getRfc = useDebounceFn(generateRfc, 400);

onMounted(() => {
    if (!isSetedList('gender')) {
        localGenderList.value = genderList;
    } else {
        localGenderList.value = props?.lists?.gender ?? [];
    }

    if (renderInput('personType') && !isSetedList('personType')) {
        getPersonTypes();
    } else {
        personTypeList.value = props?.lists?.personType ?? [];
    }
});

const isNaturalPerson = computed(() => {
    if (!renderInput('personType')) {
        return props.staticPersonType === NATURAL_PERSON_ID;
    }
    return props.personType === NATURAL_PERSON_ID;
});

const modelPersonType = computed({
    get: () => props.personType,
    set: (value) => {
        emit('update:personType', value);
    }
});

const modelFullName = computed({
    get: () => props.fullName,
    set: (value) => {
        emit('update:fullName', value);
    }
});

const modelName = computed({
    get: () => props.name,
    set: (value) => {
        emit('update:name', value);
        getRfc();
    }
});

const modelLastName = computed({
    get: () => props.lastName,
    set: (value) => {
        emit('update:lastName', value);
        getRfc();
    }
});

const modelSurname = computed({
    get: () => props.surname,
    set: (value) => {
        emit('update:surname', value);
        getRfc();
    }
});

const modelBusinessName = computed({
    get: () => props.businessName,
    set: (value) => {
        emit('update:businessName', value);
    }
});

const modelRfc = computed({
    get: () => props.rfc,
    set: (value) => {
        emit('update:rfc', value);
    }
});

const modelGender = computed({
    get: () => props.gender,
    set: (value) => {
        emit('update:gender', value);
    }
});

const modelBirthdate = computed({
    get: () => props.birthdate,
    set: (value) => {
        emit('update:birthdate', value);
        getRfc();
    }
});

const personTypeSetting = computed(() => {
    return setInputProps('personType');
});

const fullNameSetting = computed(() => {
    return setInputProps('fullName');
});

const nameSetting = computed(() => {
    return setInputProps('name');
});

const lastNameSetting = computed(() => {
    return setInputProps('lastName');
});

const surnameSetting = computed(() => {
    return setInputProps('surname');
});

const businessNameSetting = computed(() => {
    return setInputProps('businessName');
});

const rfcRules = computed(() => {
    return (v) => {
        return validateRFC(v, props?.personType ?? props.staticPersonType);
    };
});

const rfcSetting = computed(() => {
    const inputProps = setInputProps('rfc');
    inputProps.rules.push(rfcRules.value);
    return inputProps;
});

const genderSetting = computed(() => {
    return setInputProps('gender');
});

const birthdateSetting = computed(() => {
    return setInputProps('birthdate');
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
    return props?.[inputName] !== undefined ?? false;
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
    return props.disabled.includes(inputName);
}

function addAsterisk(inputName) {
    return isInputRequiredAndEnabled(inputName) ? '*' : '';
}

function isSetedList(listName) {
    if (!Object.keys(props.lists).includes(listName)) {
        return false;
    }
    return props?.lists?.[listName];
}

async function getPersonTypes() {
    try {
        emit('loading', true);
        // const response = await GeneralService.fetchPersonTypes();
        // personTypeList.value = response.data.map(personType => {
        //     return {
        //         id: personType.id,
        //         name: personType.name
        //     };
        // });
        personTypeList.value = [
            { id: 1, name: 'Física' },
            { id: 2, name: 'Moral' },
        ];
        emit('ready', true);
    } catch (error) {
        console.error(error);
        emit('error', 'Ha ocurrido un error al obtener el listado de tipos de persona.');
    }
}

function generateRfc() {
    if (!props.getRfc) {
        return;
    }
    if ((modelPersonType.value === NATURAL_PERSON_ID || props.staticPersonType === NATURAL_PERSON_ID) && (!!modelName.value) && (!!modelLastName.value) && (!!modelSurname.value) && (!!modelBirthdate.value)) {
        const rfc = modelLastName.value.toUpperCase().substring(0, 1) +
            modelLastName.value.toUpperCase().match(/[AEIOU]/)[0] +
            modelSurname.value.toUpperCase().substring(0, 1) +
            modelName.value.toUpperCase().substring(0, 1) +
            dayjs(modelBirthdate.value).format('YYMMDD');
        if (modelRfc.value == null || modelRfc.value.substring(0, 10) !== rfc) {
            emit('update:rfc', rfc);
        }
    }
}

</script>

<template>
    <h4 v-if="addTitle" class="mt-4 mb-2">Información personal</h4>
    <VRow>
        <VCol cols="12" :md="md" v-if="renderInput('personType')">
            <VSelect :label="`Tipo de persona ${addAsterisk('personType')}`" v-model="modelPersonType"
                :items="personTypeList" item-title="name" item-value="id" v-bind="personTypeSetting" />
        </VCol>

        <VCol cols="12" :md="md" v-if="renderInput('fullName')">
            <VTextField :label="`Nombre completo ${addAsterisk('fullName')}`" v-model="modelFullName" v-bind="fullNameSetting" />
        </VCol>

        <VCol cols="12" :md="md" v-if="renderInput('name') && isNaturalPerson">
            <VTextField :label="`Nombre(s) ${addAsterisk('name')}`" v-model="modelName" v-bind="nameSetting" />
        </VCol>

        <VCol cols="12" :md="md" v-if="renderInput('lastName') && isNaturalPerson">
            <VTextField :label="`Apellido paterno ${addAsterisk('lastName')}`" v-model="modelLastName"
                v-bind="lastNameSetting" />
        </VCol>

        <VCol cols="12" :md="md" v-if="renderInput('surname') && isNaturalPerson">
            <VTextField :label="`Apellido materno ${addAsterisk('surname')}`" v-model="modelSurname"
                v-bind="surnameSetting" />
        </VCol>

        <VCol cols="12" :md="6" v-if="renderInput('businessName') && !isNaturalPerson">
            <VTextField :label="`Razón social ${addAsterisk('businessName')}`" v-model="modelBusinessName"
                v-bind="businessNameSetting" />
        </VCol>

        <VCol cols="12" :md="md" v-if="renderInput('gender') && isNaturalPerson">
            <VSelect :label="`Género ${addAsterisk('gender')}`" v-model="modelGender" :items="localGenderList"
                v-bind="genderSetting" />
        </VCol>

        <VCol cols="12" :md="md" v-if="renderInput('birthdate') && isNaturalPerson">
            <VTextField :label="`Fecha de nacimiento ${addAsterisk('birthdate')}`" v-model="modelBirthdate"
                v-bind="birthdateSetting" type="date">
                <template #counter>
                    <span>{{ `${getAge(modelBirthdate)} años` }}</span>
                </template>
            </VTextField>
        </VCol>

        <VCol cols="12" :md="md" v-if="renderInput('rfc')">
            <VTextField :label="`RFC ${addAsterisk('rfc')}`" v-model="modelRfc" v-bind="rfcSetting" />
        </VCol>

        <slot name="endNaturalPersonFields" :md="md" v-if="isNaturalPerson"></slot>

        <slot name="endLegalPersonFields" :md="md" v-if="!isNaturalPerson"></slot>
        <slot :md="md"></slot>

    </VRow>
</template>
