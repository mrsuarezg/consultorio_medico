<script setup>
import { reactive, ref, onMounted } from 'vue';
import { bloodTypeList, contraceptiveMethodList, maritalStatusList, religionList, yesNoList } from '@data/lists';
import { ConsultationService } from '@services/ConsultationService';
import { PatientService } from '@services/PatientService';
import PersonForm from '@components/Form/PersonForm.vue';
import NoDataText from '@components/NoDataText.vue';
import { LEGAL_PERSON_ID, NATURAL_PERSON_ID } from '@data/constants';
import { useDebounceFn } from '@vueuse/core';

const props = defineProps({
    consultationId: {
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
const step = ref(1);

const list = reactive({
    patients: [],
});

const isLoading = reactive({
    patient: false,
});

const searchPatients = useDebounceFn(getPatients, 400);
const typed = reactive({
    patient: null,
});

const mainForm = ref(null);
const data = reactive({
    patient: {
        id: null,
        name: null,
    },
    doctor_id: null,
    diagnosis: null,
    observations: null,
    symptoms: null,
    somatometries: {
        weight: null,
        height: null,
        IMC: null,
    },
    vital_signs: {
        blood_pressure: null,
        heart_rate: null,
        respiratory_rate: null,
        body_temperature: null,
    },
    medical_prescriptions: {
        emission_date: null,
        indications: null,
    },
});
let isRegisterMode = true;

onMounted(() => {
    if (Boolean(props.consultationId)) {
        getPatientData(props.consultationId);
        isRegisterMode = false;
    }
});

function executeMainAction() {
    if (isRegisterMode) {
        registerPatient();
    } else {
        updatePatient();
    }
}

async function registerPatient() {
    try {
        emit('isLoading', true);
        const form = await mainForm.value.validate();
        if (!form.valid) {
            emit('showSnackbar', 'Favor de comprobar todos los campos');
            return;
        }
        await ConsultationService.create(createBody());
        closeSmallDialog(true, 'Paciente registrado corretamente.');
    } catch (error) {
        console.error(error);
        emit('showSnackbar', error?.response?.data?.message ?? 'Ha ocurrido un error al registrar el paciente');
    } finally {
        emit('isLoading', false);
    }
}

async function updatePatient() {
    try {
        emit('isLoading', true);
        const form = await mainForm.value.validate();
        if (!form.valid) {
            emit('showSnackbar', 'Favor de comprobar todos los campos');
            return;
        }
        await ConsultationService.update(props.consultationId, createBody());
        closeSmallDialog(true, 'Paciente actualizado correctamente.');
    } catch (error) {
        console.error(error);
        emit('showSnackbar', error?.response?.data?.message ?? 'Ha ocurrido un error al actualizar el paciente');
    } finally {
        emit('isLoading', false);
    }
}

async function getPatientData(id) {
    const params = {
        search: {
            includes: [
                {
                    relation: 'person.personable',
                },
                {
                    relation: 'hereditaryFamilyHistory',
                },
                {
                    relation: 'nonPathologicalHistory',
                },
                {
                    relation: 'gynecologicalObstetricPregnancies',
                },
                {
                    relation: 'pathologicalHistory',
                },
                // 'person.emails',
                // 'person.phones',
            ],
            filters: [
                {
                    field: "id", operator: "=", value: id
                },
            ],
        },
    };
    const response = await ConsultationService.fetch(params);
    Object.assign(data, {
        person_type_id: response.data[0].person_type_id,
    });
}

async function getPatients(hint) {
    try {
        if (hint === data?.patient?.fullname ?? true) {
            return;
        }
        typed.patient = hint;
        list.patients = [];
        if (!Boolean(hint) || (!Boolean(data?.patient?.id ?? true))) {
            return;
        }
        isLoading.patient = true;
        const params = {
            search: {
                includes: [
                    {
                        relation: 'person.personable',
                    },
                ],
                filters: [
                ],
                scopes: [
                    { name: "fullNameLike", "parameters": [hint] }
                ],
            },
        };
        const response = await PatientService.fetch(params);
        console.info(response);
        list.patients = response.data.map(row => {
            return {
                id: row.person.personable.id,
                name: row.person.personable.name,
                last_name: row.person?.personable?.last_name ?? '',
                surname: row.person?.personable?.surname ?? '',
                fullname: row.person.personable.name + ' ' + row.person.personable.last_name + ' ' + row.person.personable.surname,
                rfc: row.person.rfc,
                // phone: row?.person?.phones?.map(p => p?.phone ?? '').join(', ') ?? '',
            };
        });
    } catch (error) {
        console.error(error);
        emit('showSnackbar', error?.response?.data?.message ?? 'Ha ocurrido un error al consultar la información del paciente');
    } finally {
        isLoading.patient = false;
    }
}

function createBody() {
    const keys = Object.keys(data);
    let body = {};
    keys.forEach(key => {
        if (Boolean(data[key])) {
            body[key] = data[key];
        }
    });
    return body;
}
</script>

<template>
    <VForm ref="mainForm">
        <VWindow v-model="step">
            <VWindowItem :value="1">
                <h4 class="mt-4 mb-2">Información general de la consulta</h4>
                <VRow>
                    <VCol cols="12" md="6">
                        <VAutocomplete label="Paciente *" :items="list.patients" item-value="id" item-title="fullname"
                            v-model="data.patient" :rules="[isRequired]" autocomplete="off"
                            @update:search="searchPatients" return-object>
                            <template #no-data>
                                <NoDataText item-name="paciente" :typed="typed.patient" :is-getting-data="isLoading.patient"
                                    makes-requests />
                            </template>
                        </VAutocomplete>
                    </VCol>
                </VRow>
            </VWindowItem>
            <VWindowItem :value="2">
                <h4 class="mt-4 mb-2">Antecedentes heredo-familiares</h4>
                <VRow>
                    <VCol cols="12" md="12">
                        <!-- <VTextarea v-model="data.hereditary_family_histories.observations" bg-color="amber-lighten-4"
                            color="orange orange-darken-4" auto-grow /> -->
                    </VCol>
                </VRow>
            </VWindowItem>
        </VWindow>

        <VDivider class="my-2" />

        <VCardActions>
            <VBtn color="primary" @click="step--" v-if="step > 1">
                Anterior
            </VBtn>
            <VSpacer />
            <VBtn color="primary" @click="step++" v-if="step < 5">
                Siguiente
            </VBtn>
        </VCardActions>
    </VForm>
</template>
