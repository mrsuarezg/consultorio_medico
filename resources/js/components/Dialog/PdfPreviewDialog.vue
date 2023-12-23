<script setup>
import PdfEmbed from 'vue-pdf-embed';
const props = defineProps({
  pdfUrl: {
    type: [ArrayBuffer, String],
    required: true,
  },
  dialog: {
    type: Boolean,
    required: true,
  },
  attrs: {
    type: Object,
    default: () => ({}),
    required: false,
  },
});

const { pdfUrl, dialog, attrs } = toRefs(props);

const page = ref(1);
const pageCount = ref(0);
const pdfRef = ref(null);
const statusLoading = ref(1); // 1: Loading, 2: Loaded, 3: Error

const emits = defineEmits(['close-dialog']);

const handleDocumentRender = (event) => {
  statusLoading.value = 2;
  pageCount.value = pdfRef.value.pageCount;
};

function renderFailed() {
  statusLoading.value = 3;
};

function closeDialog() {
  emits('close-dialog');
};

function printPDF() {
  pdfRef.value.print(300, '', true);
};

const base64ToArrayBuffer = (base64) => {
  const binaryString = window.atob(base64);
  const bytes = new Uint8Array(binaryString.length);
  for (let i = 0; i < binaryString.length; i++) {
    bytes[i] = binaryString.charCodeAt(i);
  }
  return bytes.buffer;
};

// Computed for attrs, file name for download, using title if not 'Cotizacion' + Date + '.pdf'
const fileName = computed(() => {
  let fileName = attrs.value.title || 'Cotizacion';
  fileName = fileName.replace(/ /g, '_');
  fileName = fileName.replace(/[^a-zA-Z0-9-_]/g, '');
  fileName = fileName + '_' + new Date().toISOString().split('T')[0] + '.pdf';
  return fileName;
});

// Download PDF, is not native of pdf-embed, so we need to use a trick, its not the best way, but works
function downloadPDF() {
  const pdfData = base64ToArrayBuffer(pdfUrl.value.split(",")[1]); // Convertir el base64 a ArrayBuffer  
  const blob = new Blob([pdfData], { type: "application/pdf" });
  const link = document.createElement("a");
  link.href = window.URL.createObjectURL(blob);
  link.download = fileName.value;
  link.click();
};

function loadedFailed() {
  statusLoading.value = 3;
};

// Computed for attrs, title for Dialog
const titleModal = computed(() => {
  return attrs.value.title || 'PDF';
});

// onMounted valid if pdfUrl is a string base64, url or ArrayBuffer
onMounted(() => {
  if (typeof pdfUrl.value === 'string') {
    if (pdfUrl.value.startsWith('data:application/pdf;base64,')) {
      statusLoading.value = 2;
    } else if(pdfUrl.value.startsWith('http' || 'https')) {
      statusLoading.value = 1;
    } else {
      statusLoading.value = 3;
    }
  } else if (pdfUrl.value instanceof ArrayBuffer) {
    statusLoading.value = 2;
  } else {
    statusLoading.value = 3;
  }
});
</script>
<template>
  <VDialog v-model="dialog" max-width="800">
    <VCard>
      <!-- Toolbar -->
      <div>
        <VToolbar color="primary">
          <template v-if="statusLoading === 1">
            <VToolbarTitle>Cargando...</VToolbarTitle>
          </template>
          <template v-else-if="statusLoading === 2">
            <VToolbarTitle>{{ titleModal }}</VToolbarTitle>
            <VBtn icon variant="plain" @click="page--" :disabled="page <= 1">
              <VIcon color="white" icon="mdi-chevron-left" />
            </VBtn>
            {{ page }} / {{ pageCount }}
            <VBtn icon variant="plain" @click="page++" :disabled="page >= pageCount">
              <VIcon color="white" icon="mdi-chevron-right" />
            </VBtn>
            <!-- Print icon -->
            <VBtn icon variant="plain" @click="printPDF()">
              <VIcon color="white" icon="mdi-printer" />
            </VBtn>
            <!-- Download icon -->
            <VBtn icon variant="plain" @click="downloadPDF()">
              <VIcon color="white" icon="mdi-download" />
            </VBtn>
          </template>
          <template v-else>
            <VToolbarTitle>Error Archivo Invalido</VToolbarTitle>
          </template>
          <VSpacer />
          <VToolbarItems>
            <VBtn icon variant="plain" @click="closeDialog">
              <VIcon color="white" icon="mdi-close" />
            </VBtn>
          </VToolbarItems>
        </VToolbar>
      </div>
      <!-- End Toolbar -->
      <template v-if="statusLoading === 1">
        <VProgressLinear indeterminate color="primary" />
      </template>
      <pdf-embed ref="pdfRef" v-if="pdfUrl" :page="page" :source="pdfUrl" @rendered="handleDocumentRender"
        @rendering-failed="renderFailed" @loading-failed="loadedFailed" />
      <template v-if="statusLoading === 3">
        <VCardText>
          <VAlert type="error" border="start" prominent>
            Error al cargar el archivo
          </VAlert>
        </VCardText>
      </template>
    </VCard>
  </VDialog>
</template>
