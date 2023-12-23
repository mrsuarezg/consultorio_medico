<script setup>
import { TicketService } from '@services/TicketService';
import { watch } from 'vue';

const props = defineProps({
  params: {
    type: Object,
    required: false,
    default: {}
  },
  cardProps: {
    type: Object,
    required: false,
    default: {}
  }
});

const { params } = props;

const emits = defineEmits([
  'found',
  'error',
  'changedCode'
]);

const data = reactive({
  ticketCode: null,
});

const emittedChange = ref(false);
const ticketForm = ref(null);
const rules = {
  required: (v) => checkValue(v) || 'Campo obligatorio'
};
const isLoading = reactive({
  ticketCode: false
});

watch(() => data.ticketCode, () => {
  if (!emittedChange.value) {
    emitTicketCodeHasChanged();
  }
});

async function searchTicket() {
  const form = await ticketForm.value.validate();
  if (!form.valid) {
    return;
  }
  try {
    isLoading.ticketCode = true;
    emittedChange.value = false;

    const axiosConfig = {
      params: {}
    };

    if (Object.entries(params).length > 0) {
      axiosConfig.params = { ...params };
    } else {
        axiosConfig.params = {
          $include: [
            'ticketStatus',
            'businessField',
            'subBusinessField',
            'client',
            'client.clientAddress',
            'client.clientAddress.state',
            'client.clientAddress.municipality',
          ]
        };
    }
    axiosConfig.params['ticket_code'] = data.ticketCode.trim();

    const tickets = await TicketService.fetchAll2(axiosConfig);
    const ticketCount = Object.keys(tickets.data).length;

    if (ticketCount <= 0) {
      emitError(`No se encontró algún ticket con el código "${data.ticketCode}"`);
      return;
    }

    if (ticketCount > 1) {
      emitError(`Se encontraron varios tickets con el código "${data.ticketCode}"`);
      return;
    }
    const ticket = tickets.data[0];

    isLoading.ticketCode = false;
    emits('found', ticket);

  } catch (error) {
    console.error(error);
    emitError('Ha ocurrido un error al obtener la información del ticket.');
  }
};

function emitError(message = '') {
  isLoading.ticketCode = false;
  emits('error', message);
}

function clearTicketCode() {
  emitTicketCodeHasChanged();
}

function emitTicketCodeHasChanged() {
  emits('changedCode');
  emittedChange.value = true;
}

function checkValue(value) {
  if (typeof value === 'string') {
    return !!value.trim();
  }
  return !!value;
}
</script>

<template>
  <VForm @submit.prevent="searchTicket()" ref="ticketForm">
    <VCard title="Búsqueda del ticket" v-bind="cardProps">
      <VCardText>
        <VRow>
          <VCol cols="8" md="10">
            <VTextField v-model="data.ticketCode" label="Número de ticket *" placeholder="C-0000-00"
              :rules="[rules.required]" :disabled="isLoading.ticketCode" :loading="isLoading.ticketCode" clearable
              @click:clear="clearTicketCode()" />
          </VCol>

          <VCol cols="4" md="2">
            <VBtn prepend-icon="mdi-magnify" type="submit" class="h-100 w-100" :loading="isLoading.ticketCode">
              Buscar
            </VBtn>
          </VCol>
        </VRow>
      </VCardText>
    </VCard>
  </VForm>
</template>
