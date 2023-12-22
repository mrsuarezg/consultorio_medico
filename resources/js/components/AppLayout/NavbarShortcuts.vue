<script setup>
const shortcuts = [
  {
    icon: 'mdi-ticket-confirmation',
    title: 'Ticket',
    subtitle: 'Gestionar Tickets',
    to: { name: 'issuance-policy-register-ticket' },
  },
  {
    icon: 'mdi-file-document-edit',
    title: 'Emisión',
    subtitle: 'Gestiona Emisiones',
    to: { name: 'issuance-policy-emission' },
  },
]
</script>

<template>
  <Shortcuts :shortcuts="shortcuts" />
</template>
