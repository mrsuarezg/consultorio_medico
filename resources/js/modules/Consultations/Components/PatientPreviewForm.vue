<script setup>
import { reactive, ref, onMounted } from 'vue';
import { bloodTypeList, contraceptiveMethodList, maritalStatusList, religionList, yesNoList } from '@data/lists';
import { PatientService } from '@services/PatientService';
import PersonForm from '@components/Form/PersonForm.vue';
import ContactForm from '@components/Form/ContactForm.vue';
import { NATURAL_PERSON_ID } from '@data/constants';

const props = defineProps({
    patientId: {
        type: Number,
        required: true,
    },
});

const emit = defineEmits([
    'disabledButton',
    'isLoading',
    'done',
    'closeDialog',
    'showSnackbar',
]);

const mainForm = ref(null);
const step = ref(1);
const data = reactive({
    person_type_id: null,
    name: null,
    last_name: null,
    surname: null,
    rfc: null,
    birth_date: null,
    gender: null,
    civil_status: null,
    religion: null,
    blood_type_id: null,
    occupation: null,
    address: null,
    phone: null,
    email: null,
    hereditary_family_histories: {
        observations: null
    },
    non_pathological_histories: {
        room: null,
        hygiene_habits: null,
        feeding: null,
    },
    gynecological_obstetric_pregnancies: {
        menarche: null,
        ivsa: null,
        number_of_partners: null,
        pregnancies_G_P_C_A_O: null,
        contraceptive_method_id: null,
    },
    pathological_histories: {
        infectious_diseases: null,
        chronic_degenerative_diseases: null,
        allergies: null,
        surgical_interventions: null,
        traumatism: null,
        transfusions: null,
        seizures: null,
        addictions: null,
        hospitalizations: null,
    },
});

onMounted(async () => {
    await getPatientData(props.patientId);
});

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
    const response = await PatientService.fetch(params);
    Object.assign(data, {
        person_type_id: response.data[0].person_type_id,
        name: response.data[0].person.personable.name,
        last_name: response.data[0].person.personable.last_name,
        surname: response.data[0].person.personable.surname,
        rfc: response.data[0].person.rfc,
        birth_date: response.data[0].person.personable.birth_date,
        gender: response.data[0].person.personable.gender,
        civil_status: response.data[0].person.personable.civil_status ?? null,
        religion: response.data[0].person.personable.religion ?? null,
        blood_type_id: response.data[0].blood_type_id ?? null,
        occupation: response.data[0].occupation ?? null,
        address: response.data[0].person.address ?? null,
        phone: response.data[0].person?.phones?.[0]?.phone ?? null,
        email: response.data[0].person?.emails?.[0]?.email ?? null,
        hereditary_family_histories: {
            observations: response.data[0].hereditary_family_history?.observations ?? null,
        },
        non_pathological_histories: {
            room: response.data[0].non_pathological_history?.room ?? null,
            hygiene_habits: response.data[0].non_pathological_history?.hygiene_habits ?? null,
            feeding: response.data[0].non_pathological_history?.feeding ?? null,
        },
        gynecological_obstetric_pregnancies: {
            menarche: response.data[0].gynecological_obstetric_pregnancies?.menarche ?? null,
            ivsa: response.data[0].gynecological_obstetric_pregnancies?.IVSA ?? null,
            number_of_partners: response.data[0].gynecological_obstetric_pregnancies?.number_of_partners ?? null,
            pregnancies_G_P_C_A_O: response.data[0].gynecological_obstetric_pregnancies?.pregnancies_G_P_C_A_O ?? null,
            contraceptive_method_id: response.data[0].gynecological_obstetric_pregnancies?.contraceptive_method_id ?? null,
        },
        pathological_histories: {
            infectious_diseases: response.data[0].pathological_history?.infectious_diseases ?? null,
            chronic_degenerative_diseases: response.data[0].pathological_history?.chronic_degenerative_diseases ?? null,
            allergies: response.data[0].pathological_history?.allergies ?? null,
            surgical_interventions: response.data[0].pathological_history?.surgical_interventions ?? null,
            traumatism: response.data[0].pathological_history?.traumatism ?? null,
            transfusions: response.data[0].pathological_history?.transfusions ?? null,
            seizures: response.data[0].pathological_history?.seizures ?? null,
            addictions: response.data[0].pathological_history?.addictions ?? null,
            hospitalizations: response.data[0].pathological_history?.hospitalizations ?? null,
        },
    });
}
</script>

<template>
    <VForm ref="mainForm">
        <VWindow v-model="step">
            <VWindowItem :value="1">
                <PersonForm v-model:personType="data.person_type_id" v-model:name="data.name"
                    v-model:lastName="data.last_name" v-model:surname="data.surname" v-model:businessName="data.name"
                    v-model:rfc="data.rfc" v-model:birthdate="data.birth_date" v-model:gender="data.gender"
                    :readonly="['all']" get-rfc md="6">
                    <template #endNaturalPersonFields>
                        <VCol cols="12" md="6">
                            <VAutocomplete label="Estado civil" v-model="data.civil_status" readonly variant="plain"
                                menu-icon="''" :items="maritalStatusList">
                                <template #no-data>
                                    <span class="px-3">Estado civil no encontrado</span>
                                </template>
                            </VAutocomplete>
                        </VCol>
                        <VCol cols="12" md="6">
                            <VAutocomplete label="Religión" v-model="data.religion" readonly variant="plain" menu-icon="''"
                                :items="religionList">
                                <template #no-data>
                                    <span class="px-3">Religión no encontrada</span>
                                </template>
                            </VAutocomplete>
                        </VCol>
                        <VCol cols="12" md="6">
                            <VAutocomplete label="Tipo de sangre" v-model="data.blood_type_id" readonly variant="plain"
                                menu-icon="''" :items="bloodTypeList" item-title="name" item-value="id">
                                <template #no-data>
                                    <span class="px-3">Tipo de sangre no encontrado</span>
                                </template>
                            </VAutocomplete>
                        </VCol>
                        <VCol cols="12" md="6">
                            <VTextField label="Ocupación" v-model="data.occupation" readonly variant="plain"
                                menu-icon="''" />
                        </VCol>
                    </template>
                </PersonForm>
            </VWindowItem>
            <VWindowItem :value="2">
                <h4 class="mt-4 mb-2">Antecedentes heredo-familiares</h4>
                <VRow>
                    <VCol cols="12" md="12">
                        <VTextarea v-model="data.hereditary_family_histories.observations" bg-color="amber-lighten-4"
                            color="orange orange-darken-4" auto-grow readonly />
                    </VCol>
                </VRow>
            </VWindowItem>
            <VWindowItem :value="3">
                <h4 class="mt-4 mb-2">Antecedentes personales no patológicos</h4>
                <VRow>
                    <VCol cols="12" md="12">
                        <VTextarea label="Habitación" v-model="data.non_pathological_histories.room"
                            bg-color="amber-lighten-4" color="orange orange-darken-4" auto-grow readonly />
                    </VCol>
                    <VCol cols="12" md="12">
                        <VTextarea label="Hábitos higiénicos" v-model="data.non_pathological_histories.hygiene_habits"
                            bg-color="amber-lighten-4" color="orange orange-darken-4" auto-grow readonly />
                    </VCol>
                    <VCol cols="12" md="12">
                        <VTextarea label="Alimentación" v-model="data.non_pathological_histories.feeding"
                            bg-color="amber-lighten-4" color="orange orange-darken-4" auto-grow readonly />
                    </VCol>
                </VRow>
            </VWindowItem>
            <VWindowItem :value="4">
                <h4 class="mt-4 mb-2">Antecedentes ginéco-obstetricos</h4>
                <VRow>
                    <VCol cols="12" md="6">
                        <VTextField label="Menarca" v-model="data.gynecological_obstetric_pregnancies.menarche" readonly />
                    </VCol>
                    <VCol cols="12" md="6">
                        <VTextField label="IVSA" v-model="data.gynecological_obstetric_pregnancies.ivsa" readonly />
                    </VCol>
                    <VCol cols="12" md="6">
                        <VTextField label="Número de parejas"
                            v-model="data.gynecological_obstetric_pregnancies.number_of_partners" readonly />
                    </VCol>
                    <VCol cols="12" md="6">
                        <VTextField label="Embarazos G_P_C_A_O"
                            v-model="data.gynecological_obstetric_pregnancies.pregnancies_G_P_C_A_O" readonly />
                    </VCol>
                    <VCol cols="12" md="6">
                        <VAutocomplete label="Método anticonceptivo"
                            v-model="data.gynecological_obstetric_pregnancies.contraceptive_method_id"
                            :items="contraceptiveMethodList" item-title="name" item-value="id" readonly>
                            <template #no-data>
                                <span class="px-3">
                                    Método anticonceptivo no encontrado
                                </span>
                            </template>
                        </VAutocomplete>
                    </VCol>
                </VRow>
            </VWindowItem>
            <VWindowItem :value="5">
                <h4 class="mt-4 mb-2">Antecedentes patológicos</h4>
                <VRow>
                    <VCol cols="12" md="6">
                        <VTextarea label="Enfermedades infecto-contagiosas"
                            v-model="data.pathological_histories.infectious_diseases" bg-color="amber-lighten-4"
                            color="orange orange-darken-4" auto-grow readonly />
                    </VCol>
                    <VCol cols="12" md="6">
                        <VTextarea label="Enfermedades crónico-degenerativas"
                            v-model="data.pathological_histories.chronic_degenerative_diseases" bg-color="amber-lighten-4"
                            color="orange orange-darken-4" auto-grow readonly />
                    </VCol>
                    <VCol cols="12" md="6">
                        <VTextarea label="Alergias" v-model="data.pathological_histories.allergies"
                            bg-color="amber-lighten-4" color="orange orange-darken-4" auto-grow readonly />
                    </VCol>
                    <VCol cols="12" md="6">
                        <VTextarea label="Intervenciones quirúrgicas"
                            v-model="data.pathological_histories.surgical_interventions" bg-color="amber-lighten-4"
                            color="orange orange-darken-4" auto-grow readonly />
                    </VCol>
                    <VCol cols="12" md="6">
                        <VTextarea label="Traumatismos" v-model="data.pathological_histories.traumatism"
                            bg-color="amber-lighten-4" color="orange orange-darken-4" auto-grow readonly />
                    </VCol>
                    <VCol cols="12" md="6">
                        <VTextField label="Transfusiones" v-model="data.pathological_histories.transfusions" readonly />
                    </VCol>
                    <VCol cols="12" md="6">
                        <VTextField label="Convulsiones" v-model="data.pathological_histories.seizures" readonly />
                    </VCol>
                    <VCol cols="12" md="6">
                        <VTextField label="Adicciones" v-model="data.pathological_histories.addictions" readonly />
                    </VCol>
                    <VCol cols="12" md="6">
                        <VTextField label="Hospitalizaciones" v-model="data.pathological_histories.hospitalizations"
                            readonly />
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
