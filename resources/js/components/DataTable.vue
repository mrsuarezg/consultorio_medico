<script setup>
import { NotificationService } from '@services/NotificationService';
import { getLocalDateByUTC, getAttentionColor, getTicketStatusColor } from '@utils/formatters.js';
import { mergeProps } from 'vue';
import { VDataTableServer } from 'vuetify/labs/VDataTable';
import TableFooter from './TableFooter.vue';
// Define props
const props = defineProps({
  headers: {
    type: Array,
    required: true,
  },
  items: {
    type: Array,
    required: true,
  },
  disablePagination: {
    type: Boolean,
    default: false,
  },
  currentPage: {
    type: Number,
    default: 1,
    required: true,
  },
  totalPages: {
    type: Number,
    default: 1,
    required: true,
  },
  isloading: {
    type: Boolean,
    default: false,
    required: true,
  },
  perPage: {
    type: Number,
    default: 15,
  },
  totalTickets: {
    type: Number,
    default: 0,
  },
  levelAttention: {
    type: Array,
    required: true,
    default: [],
  },
  itemsName: {
    type: String,
    required: false,
    default: 'tickets',
  },
  showExpand: {
    type: Boolean,
    required: false,
    default: false,
  },
});
const { headers, items, totalPages, isloading, levelAttention, showExpand } = toRefs(props);

const emit = defineEmits([
  'action-modal',
  'update',
  'update:currentPage',
]);

const localSnackbar = reactive({
  status: false,
  text: '',
  color: 'success',
});

const page = computed({
  get: () => props.currentPage,
  set: (value) => emit('update:currentPage', value)
});

// Items for Level Attention Select
function levelAttentionName(id) {
  return levelAttention.value.find(level => level.id === id) ?? { id: -1, name: 'Prioridad desconocida' };
}

async function sendTicketReminderNotification(ticketCode, executiveId) {
  try {
    await NotificationService.sendTicketReminderNotification(ticketCode, executiveId);
    showSnackbar(`Recordatorio del ticket ${ticketCode} enviado`);
  } catch (error) {
    console.error(error);
    showSnackbar('Ha ocurrido un error al envíar la notificación', 'error');
  }
}

function customerName(item) {
  const person = item.raw.client.person;
  const type = item.raw.client.person_type.name;
  switch (type) {
    case 'Física': return `${person?.name ?? ''} ${person?.last_name ?? ''} ${person?.surname ?? ''}`;
    case 'Moral': return person.business_name;
    default: return person.name != null ? `${person?.name ?? ''} ${person?.last_name ?? ''} ${person?.surname ?? ''}` : person?.business_name ?? '';
  }
}

function showSnackbar(message, color = 'success') {
  Object.assign(localSnackbar, {
    status: true,
    text: message,
    color: color,
  });
}

function closeSnackBar() {
  localSnackbar.status = false;
}
</script>

<template>
  <VDataTableServer :show-expand="showExpand" :headers="headers" :items="items" :loading="isloading"
    :items-length="items.length">
    <template #item.fullName="{ item }">
      <div class="d-flex flex-column items-center">
        <span class="d-flex">{{ customerName(item) }}</span>
      </div>
    </template>
    <template #item.created_at="{ item }">
      <span>{{ getLocalDateByUTC(item.raw.created_at) }}</span>
    </template>
    <template #item.enterprise="{ item: { props: { title: { company: { name } } } } }">
      <div class="d-flex flex-column items-center">
        <span class="d-flex">{{ name }}</span>
      </div>
    </template>
    <template #item.cost_center="{ item }">
      <div class="d-flex flex-column items-center">
        <span class="d-flex">{{ item.raw.cost_center.name }}</span>
      </div>
    </template>
    <template #item.attention="{ item }">
      <div class="d-flex flex-column items-center">
        <VChip class="d-flex justify-center"
          :color="getAttentionColor(levelAttentionName(item.raw.attention_level_id).name)" size="small">
          <span class="ma-auto">{{ levelAttentionName(item.raw.attention_level_id).name }}</span>
        </VChip>
      </div>
    </template>
    <template #item.state="{ item }">
      <div class="d-flex flex-column items-center">
        <VChip class="d-flex justify-center" :color="getTicketStatusColor(item.raw.ticket_status.name)" size="small">
          <span class="text-sm ma-auto">{{ item.raw.ticket_status.name }}</span>
        </VChip>
      </div>
    </template>
    <!-- 👉 Register Ticket - Actions (Edit, Notified, UpdateStatus) -->
    <template #item.actions="{ item }">
      <div class="d-flex justify-center">
        <VMenu>
          <template v-slot:activator="{ props: menu }">
            <VTooltip location="top">
              <template v-slot:activator="{ props: tooltip }">
                <VBtn variant="text" size="small" icon="mdi-dots-vertical" v-bind="mergeProps(menu, tooltip)" />
              </template>
              <span>Mostrar opciones</span>
            </VTooltip>
          </template>
          <VList class="pa-0">
            <VListItem @click.prevent="emit('action-modal', { ticket: item.raw, action: 'edit' })">
              <template v-slot:prepend>
                <VIcon icon="mdi-pencil" size="small" class="mr-2" />
              </template>
              <VListItemTitle v-text="'Editar'" />
            </VListItem>

            <VTooltip>
              <template #activator="{ props }">
                <VListItem @click.prevent="sendTicketReminderNotification(item.raw.ticket_code, item.raw.executive_id)"
                  v-bind="props">
                  <template v-slot:prepend>
                    <VIcon icon="mdi-bell-ring" size="small" class="mr-2" />
                  </template>
                  <VListItemTitle v-text="'Enviar notificación'" />
                </VListItem>
              </template>
              <span>Enviar notificación a {{ item.raw.executive.name }}</span>
            </VTooltip>

            <VListItem @click.prevent="emit('action-modal', { ticket: item.raw, action: 'update' })">
              <template v-slot:prepend>
                <VIcon icon="mdi-list-status" size="small" class="mr-2" />
              </template>
              <VListItemTitle v-text="'Actualizar estado'" />
            </VListItem>
          </VList>
        </VMenu>
      </div>
    </template>

    <!-- 👉 Quote [Vehicle, Life, Bail, Damage] - Actions (Edit, Print, Emmit) -->
    <template #item.actionsQuotes="{ item }">
      <slot name="actionsQuotes" :item="item">
        <div class="flex items-center">
          <VTooltip bottom>
            <template #activator="{ props }">
              <VBtn icon variant="text" size="x-small"
                @click.prevent="emit('action-modal', { ticket: item, action: 'edit' })" v-bind="props">
                <VIcon icon="mdi-pencil" :size="24" />
              </VBtn>
            </template>
            Editar
          </VTooltip>

          <VTooltip bottom>
            <template #activator="{ props }">
              <VBtn icon variant="text" size="x-small"
                @click.prevent="emit('action-modal', { ticket: item, action: 'print' })" v-bind="props">
                <VIcon :size="24" icon="mdi-file-pdf-box" />
              </VBtn>
            </template>
            Ver PDF
          </VTooltip>

          <VTooltip bottom>
            <template #activator="{ props }">
              <VBtn icon variant="text" size="x-small"
                @click.prevent="emit('action-modal', { ticket: item, action: 'emission' })" v-bind="props">
                <VIcon :size="24" icon="mdi-gavel" />
              </VBtn>
            </template>
            Registrar emisión
          </VTooltip>
        </div>
      </slot>
    </template>

    <!-- 👉 Emission - Actions (Edit) -->
    <template #item.actionsEmission="{ item }">
      <div class="flex items-center">
        <VTooltip bottom>
          <template #activator="{ props }">
            <VBtn icon variant="text" size="x-small" @click.prevent="emit('action-modal', { item: item, action: 'edit' })"
              v-bind="props">
              <VIcon icon="mdi-pencil" :size="24" />
            </VBtn>
          </template>
          <span>Editar</span>
        </VTooltip>

        <VMenu>
          <template v-slot:activator="{ props: menu }">
            <VTooltip location="top">
              <template v-slot:activator="{ props: tooltip }">
                <VBtn variant="text" size="small" icon="mdi-dots-vertical" v-bind="mergeProps(menu, tooltip)" />
              </template>
              <span>M&aacute;s opciones</span>
            </VTooltip>
          </template>
          <VList class="pa-0">
            <VListItem class="pa-0">
              <VListItemTitle>
                <VBtn variant="text" prepend-icon="mdi-file-cabinet"
                  @click.prevent="emit('action-modal', { item: item, action: 'sustenance' })">Ver sustentos</VBtn>
              </VListItemTitle>
            </VListItem>
            <VListItem class="pa-0">
              <VBtn variant="text" prepend-icon="mdi-currency-usd"
                @click.prevent="emit('action-modal', { item: item, action: 'collection' })">Ver cobranza</VBtn>
            </VListItem>
            <VListItem class="pa-0">
              <VBtn variant="text" @click.prevent="emit('action-modal', { item: item, action: 'test' })"></VBtn>
            </VListItem>
          </VList>
        </VMenu>
      </div>
    </template>

    <!-- Slot can used showExpand its true -->
    <template #expanded-row="{ columns, item }">
      <slot name="expanded-row" :columns="columns" :item="item" />
    </template>

    <template #bottom>
      <TableFooter v-model="page" :per-page="perPage" :current-items="items.length" :total-items="totalTickets"
        :total-pages="totalPages" :disabled="isloading" />
    </template>

    <template #no-data>
      <p class="pa-4">No hay {{ itemsName }} para mostrar.</p>
    </template>
  </VDataTableServer>

  <VSnackbar v-model="localSnackbar.status" location="bottom" :color="localSnackbar.color">
    {{ localSnackbar.text }}
    <template v-slot:actions>
      <VBtn color="surface" variant="text" size="small" icon="mdi-close-circle" @click="closeSnackBar()" />
    </template>
  </VSnackbar>
</template>
