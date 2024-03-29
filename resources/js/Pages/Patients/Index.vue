<script setup>
import { onMounted, shallowRef, ref, reactive } from 'vue';
import { PatientService } from '@services/PatientService.js';
import SmallDialog from '@components/Dialog/SmallDialog.vue';
import MultipleItems from '@components/MultipleItems.vue';
import PatientForm from '@modules/Patients/Components/PatientForm.vue';
import PatientPreviewForm from '@modules/Patients/Components/PatientPreviewForm.vue';
// import CustomerTableFilter from '@modules/Settings/Customers/Components/CustomerTableFilter.vue';
import TableFooter from '@components/TableFooter.vue';

provide('customerTable', {
    closeSmallDialog,
});

// START TABLE
const headers = ref([
    { title: 'Nombre', value: 'name', key: 'name' },
    { title: 'Apellido paterno', value: 'last_name', key: 'last_name' },
    { title: 'Apellido materno', value: 'surname', key: 'surname' },
    { title: 'RFC', value: 'rfc', key: 'rfc' },
    { title: 'Acciones', value: 'actions', key: 'actions' },
]);
const filters = reactive({
    person_type: null,
    name: null,
    rfc: null,
});
const items = ref([]);
const itemsPerPage = ref(15);
const isGettingPatients = ref(false);
const currentPage = ref(1);
const totalPage = ref(1);
const totalItems = ref(0);
// END TABLE
// START SMALL DIALOG
const isShowingSmallDialog = ref(false);
const smallDialogTitle = ref('');
const formProps = ref({});
const formComponent = shallowRef('div');
const includeMainButton = ref(true);
// END SMALL DIALOG
const snackbar = reactive({
    isVisible: false,
    text: '',
    color: 'success'
});

onMounted(async () => {
    await getPatients();
});

async function getPatients() {
    try {
        isGettingPatients.value = true;
        const params = {
            search: {
                filters: [],
                includes: [
                    { relation: 'person.personable' },
                    // { relation: 'person.phones' },
                    // { relation: 'person.emails' },
                ],
                limit: itemsPerPage.value,
                page: currentPage.value,
            }
        };
        if (!!filters.person_type) {
            params.search.filters.push({ field: 'person_type_id', operator: '=', value: filters.person_type });
        }
        if (!!filters.name) {
            const nameFilters = [];
            nameFilters.push({ field: 'person.name', operator: 'like', value: `%${filters.name}%` });
            nameFilters.push({ field: 'person.last_name', operator: 'like', value: `%${filters.name}%`, type: 'or' });
            nameFilters.push({ field: 'person.surname', operator: 'like', value: `%${filters.name}%`, type: 'or' });
            params.search.filters.push({ nested: nameFilters });
        }
        if (!!filters.rfc) {
            params.search.filters.push({ field: 'person.rfc', operator: 'like', value: `%${filters.rfc}%` });
        }
        if (!!filters.phone) {
            params.search.filters.push({ field: 'person.phone', operator: 'like', value: `%${filters.phone}%` });
        }
        if (!!filters.email) {
            params.search.filters.push({ field: 'person.email', operator: 'like', value: `%${filters.email}%` });
        }

        const response = await PatientService.fetch(params);
        items.value = response.data.map(row => {
            return {
                id: row.id,
                name: row.person.personable.name,
                last_name: row.person?.personable?.last_name ?? '',
                surname: row.person?.personable?.surname ?? '',
                rfc: row.person.rfc,
                // phone: row?.person?.phones?.map(p => p?.phone ?? '').join(', ') ?? '',
            };
        });

        totalItems.value = response?.total ?? -1;
        totalPage.value = response?.last_page ?? 1;
        currentPage.value = response?.current_page ?? 1;
    } catch (error) {
        console.error(error);
        showSnackbar('Ha ocurrido un error al obtener los pacientes', 'error', false);
    } finally {
        isGettingPatients.value = false;
    }
}

function showSnackbar(message, color = 'success', reloadTable = true) {
    Object.assign(snackbar, {
        isVisible: true,
        message: message,
        color: color,
    });
    if (reloadTable) {
        getPatients();
    }
}

function toggleSmallDialog() {
    isShowingSmallDialog.value = !isShowingSmallDialog.value;
}

function registerPatient() {
    showSmallDialog('Registrar paciente', PatientForm, {}, true);
}

function editCustomer(id) {
    showSmallDialog('Editar paciente', PatientForm, { patientId: id }, true);
}

function previewPatient(id) {
    showSmallDialog('Información del paciente', PatientPreviewForm, { patientId: id }, false);
}

function showSmallDialog(title, component, componentProps, showMainButton) {
    smallDialogTitle.value = title;
    formComponent.value = component;
    formProps.value = componentProps;
    includeMainButton.value = showMainButton;
    toggleSmallDialog();
}

function closeSmallDialog(showFatherSnackbar, snackbarMessage = '', snackbarColor = 'success') {
    toggleSmallDialog();
    getPatients();
    if (showFatherSnackbar) {
        Object.assign(snackbar, {
            isVisible: true,
            message: snackbarMessage,
            color: snackbarColor,
        });
    }
}

function updateItemsPerPage(value) {
    itemsPerPage.value = value;
    getPatients();
}

function searchWithFilter() {
    currentPage.value = 1;
    getPatients();
}
</script>

<template>
    <main>
        <MultipleItems remove-margin-top @add-item="registerPatient">
            <template #title>
                <span class="mr-4">Pacientes</span>
                <VMenu :close-on-content-click="false">
                    <template v-slot:activator="{ props }">
                        <VBtn color="primary" prepend-icon="mdi-filter-menu" size="small" v-bind="props">
                            Filtrar
                        </VBtn>
                    </template>
                    <!-- <CustomerTableFilter v-model:personType="filters.person_type" v-model:name="filters.name"
                        v-model:rfc="filters.rfc" v-model:phone="filters.phone" v-model:email="filters.email"
                        @search="searchWithFilter()" /> -->
                </VMenu>
            </template>
            <VDataTableServer :headers="headers" :items="items" :items-length="items.length" :items-per-page="itemsPerPage"
                :loading="isGettingPatients">

                <template #item.actions="{ item }">
                    <VMenu>
                        <template #activator="{ props }">
                            <VBtn icon="mdi-dots-vertical" size="small" v-bind="props" variant="plain" />
                        </template>
                        <VList>
                            <VListItem @click="previewPatient(item.id)">
                                <template v-slot:prepend>
                                    <VIcon icon="mdi-eye" size="small" class="mr-2" />
                                </template>
                                <VListItemTitle>Ver más información</VListItemTitle>
                            </VListItem>
                            <VListItem @click="editCustomer(item.id)">
                                <template v-slot:prepend>
                                    <VIcon icon="mdi-pencil" size="small" class="mr-2" />
                                </template>
                                <VListItemTitle>Editar</VListItemTitle>
                            </VListItem>
                            <!-- <VListItem>
                                <template v-slot:prepend>
                                    <VIcon icon="mdi-delete" size="small" class="mr-2" />
                                </template>
                                <VListItemTitle>Eliminar</VListItemTitle>
                            </VListItem> -->
                        </VList>
                    </VMenu>
                </template>

                <template #no-data>
                    <p class="pa-4">No hay pacientes para mostrar</p>
                </template>

                <template #bottom>
                    <TableFooter v-model="currentPage" :total-items="totalItems" :total-pages="totalPage"
                        :disabled="isGettingPatients" :current-items="items.length" @changed-page="getPatients()"
                        @changed-per-page-option="updateItemsPerPage" />
                </template>
            </VDataTableServer>
        </MultipleItems>

        <SmallDialog :title="smallDialogTitle" v-model="isShowingSmallDialog" :form-props="formProps"
            :form-component="formComponent" close-button-text="Cancelar" main-button-text="Guardar" max-width="800"
            :include-main-button="includeMainButton" />

        <VSnackbar v-model="snackbar.isVisible" location="bottom" :color="snackbar.color" z-index="3000">
            {{ snackbar.message }}
            <template v-slot:actions>
                <VBtn color="surface" variant="text" size="small" icon="mdi-close-circle" @click="closeSnackBar()" />
            </template>
        </VSnackbar>
    </main>
</template>
