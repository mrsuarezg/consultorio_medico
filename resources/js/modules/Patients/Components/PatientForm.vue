<script setup>
import { reactive, ref, onMounted } from 'vue';
import { bloodTypeList, maritalStatusList,religionList, yesNoList } from '@data/lists';
import { PatientService } from '@services/PatientService';
import PersonForm from '@components/Form/PersonForm.vue';
import ContactForm from '@components/Form/ContactForm.vue';
import { LEGAL_PERSON_ID, NATURAL_PERSON_ID } from '@data/constants';

const props = defineProps({
    customerId: {
        type: [Number, null],
        required: false,
    },
});

const emit = defineEmits([
    'disabledButton',
    'isLoading',
    'done',
    'closeDialog',
    'showSnackbar',
]);

defineExpose({
    executeMainAction,
});

const { closeSmallDialog } = inject('customerTable');

const mainForm = ref(null);
const data = reactive({
    person_type_id: 1,
    name: null,
    last_name: null,
    surname: null,
    rfc: null,
    birth_date: null,
    gender: null,
    civil_status: null,
    religion: null,
    blood_type: null,
    occupation: null,
    address: null,
    phone: null,
    email: null,
});
let isRegisterMode = true;

onMounted(() => {
    if (Boolean(props.customerId)) {
        getCustomerData(props.customerId);
        isRegisterMode = false;
    }
});

function executeMainAction() {
    if (isRegisterMode) {
        registerCustomer();
    } else {
        updateCustomer();
    }
}

async function registerCustomer() {
    try {
        emit('isLoading', true);
        const form = await mainForm.value.validate();
        if (!form.valid) {
            emit('showSnackbar', 'Favor de comprobar todos los campos');
            return;
        }
        await PatientService.create(createBody());
        closeSmallDialog(true, 'Cliente registrado');
    } catch (error) {
        console.error(error);
        emit('showSnackbar', error?.response?.data?.message ?? 'Ha ocurrido un error al registrar el cliente');
    } finally {
        emit('isLoading', false);
    }
}

async function updateCustomer() {
    try {
        emit('isLoading', true);
        const form = await mainForm.value.validate();
        if (!form.valid) {
            emit('showSnackbar', 'Favor de comprobar todos los campos');
            return;
        }
        await PatientService.update(props.customerId, createBody());
        closeSmallDialog(true, 'Cliente actualizado');
    } catch (error) {
        console.error(error);
        emit('showSnackbar', error?.response?.data?.message ?? 'Ha ocurrido un error al actualizar el cliente');
    } finally {
        emit('isLoading', false);
    }
}

async function getCustomerData(id) {
    const axiosConfig = {
        params: {
            $sort: 'created_at:desc',
            all: true,
            $include: [
                'person',
                'person.emails',
                'person.phones',
            ],
            id: id,
        },
    };
    const response = await PatientService.fetchAll(axiosConfig);
    Object.assign(data, {
        person_type_id: response.data[0].person_type_id,
        name: response.data[0].person.name,
        last_name: response.data[0].person.last_name,
        surname: response.data[0].person.surname,
        rfc: response.data[0].person.rfc,
        birth_date: response.data[0].person.birth_date,
        gender: response.data[0].person.gender,
        civil_status: response.data[0].person.civil_status ?? null,
        religion: response.data[0].person.religion ?? null,
        blood_type: response.data[0].person.blood_type ?? null,
        occupation: response.data[0].person.occupation ?? null,
        address: response.data[0].person.address ?? null,
        phone: response.data[0].person?.phones?.[0]?.phone ?? null,
        email: response.data[0].person?.emails?.[0]?.email ?? null,
    });
}

function createBody() {
    const keys = Object.keys(data);
    let body = {};
    keys.forEach(key => {
        if (Boolean(data[key])) {
            body[key] = data[key];
        }
    });
    if (data.person_type_id === LEGAL_PERSON_ID) {
        delete body.name;
        delete body.last_name;
        delete body.surname;
        delete body.gender;
        delete body.birth_date;
    }
    return body;
}
</script>

<template>
    <VForm ref="mainForm">
        <PersonForm v-model:personType="data.person_type_id" v-model:name="data.name" v-model:lastName="data.last_name"
            v-model:surname="data.surname" v-model:businessName="data.name" v-model:rfc="data.rfc"
            v-model:birthdate="data.birth_date" v-model:gender="data.gender" :readonly="['personType', 'rfc']" get-rfc md="6">
            <template #endNaturalPersonFields>
                <VCol cols="12" md="6">
                    <VAutocomplete label="Estado civil *" v-model="data.civil_status" :rules="[isRequired]"
                        :items="maritalStatusList">
                        <template #no-data>
                            <span class="px-3">Estado civil no encontrado</span>
                        </template>
                    </VAutocomplete>
                </VCol>
                <VCol cols="12" md="6">
                    <VAutocomplete label="Religión" v-model="data.religion" :rules="[isRequired]"
                        :items="religionList">
                        <template #no-data>
                            <span class="px-3">Religión no encontrada</span>
                        </template>
                    </VAutocomplete>
                </VCol>
                <VCol cols="12" md="6">
                    <VAutocomplete label="Tipo de sangre" v-model="data.blood_type" :rules="[isRequired]"
                        :items="bloodTypeList" item-title="name" item-value="id">
                        <template #no-data>
                            <span class="px-3">Tipo de sangre no encontrado</span>
                        </template>
                    </VAutocomplete>
                </VCol>
                <VCol cols="12" md="6">
                    <VTextField label="Ocupación" v-model="data.occupation" :rules="[isRequired]" />
                </VCol>
            </template>
        </PersonForm>

        <!-- <ContactForm v-model:phone="data.phone" v-model:email="data.email" :writable="['all']" :not-required="['all']"
            md="6" />

        <VTextarea label="Dirección" v-model="data.address" class="mt-5" rows="2" auto-grow /> -->
    </VForm>
</template>
