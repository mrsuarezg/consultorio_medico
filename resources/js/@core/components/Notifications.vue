<script setup>
import { PerfectScrollbar } from 'vue3-perfect-scrollbar';
import { ref } from 'vue';
import { usePage } from '@inertiajs/vue3';
import { useWebNotification } from '@vueuse/core';

let option = {
  title: null,
  dir: 'auto',
  lang: 'en',
  renotify: true,
  tag: 'test',
};

const {
  isSupported,
  show,
} = useWebNotification(option);

const page = usePage();
const notifications = ref([]);

const props = defineProps({
  badgeProps: {
    type: null,
    required: false,
    default: undefined,
  },
  location: {
    type: null,
    required: false,
    default: 'bottom end',
  },
});

onMounted(() => {
//   window.Echo.private(`App.Models.User.${page.props.auth.user.data.id}`)
//     .notification(notification => {
//       const audio = new Audio('https://cidar-dev.s3.us-east-2.amazonaws.com/sounds/twitt.mp3');
//       audio.play();

//       option.title = notification.web.title;
//       page.props.unreadNotificationsCount++;

//       if (isSupported) {
//         show();
//       } else {
//         alert(option.title);
//       }
//     });
});

const reloadNotifications = () => {
  router.reload({
    only: ['notifications'],
    preserveState: true,
  });
  router.on('success', () => {
    notifications.value = page.props?.notifications ?? [];
  });
};

function markNotificationAsRead() {
  page.props.unreadNotificationsCount = 0;
}
</script>

<template>
  <VBadge :model-value="!!props.badgeProps" v-bind="props.badgeProps">
    <VBtn icon variant="text" color="default" size="small" @click="reloadNotifications">
      <VBadge v-if="$page.props.unreadNotificationsCount > 0" color="error" bordered offset-x="1" offset-y="1"
        :content="$page.props.unreadNotificationsCount" max="9">
        <VIcon icon="mdi-bell-outline" size="24" />
      </VBadge>
      <VIcon v-else icon="mdi-bell-outline" size="24" />
      <VMenu activator="parent" width="380px" :location="props.location" offset="14px">
        <VCard class="d-flex flex-column">
          <!-- 👉 Header -->
          <VCardItem class="notification-section">
            <VCardTitle class="text-base">
              Notificaciones
            </VCardTitle>

            <template #append>
              <VTooltip>
                <template #activator="{ props }">
                  <VBtn icon="mdi-bell-check-outline" size="24" variant="text" v-bind="props"
                    @click="markNotificationAsRead" />
                </template>
                <span>Marcar como leídas</span>
              </VTooltip>
            </template>
          </VCardItem>
          <VDivider />
          <!-- 👉 Notifications list -->
          <PerfectScrollbar :options="{ wheelPropagation: false }">
            <VList class="py-0">
              <VListItem v-if="notifications?.length > 0" v-for="notification in notifications"
                :title="notification.data.custom.title" :key="notification.title" link lines="one" min-height="66px">
                <template #subtitle>
                  {{ notification.data.custom.text }}
                </template>
                <template #prepend>
                  <VListItemAction start>
                    <VAvatar :color="notification.color || 'primary'" :image="notification.data.custom.icon || undefined"
                      :icon="notification.data.custom.icon || undefined" size="40" variant="tonal">
                    </VAvatar>
                  </VListItemAction>
                </template>
              </VListItem>
              <p v-else class="text-center px-2 py-4 mb-0">Sin notificaciones</p>
            </VList>
          </PerfectScrollbar>

          <!-- 👉 Footer -->
          <VCardText class="notification-section" v-if="false">
            <VBtn block @click="$emit('click:readAllNotifications')">
              Ver todas mis notificaciones
            </VBtn>
          </VCardText>
        </VCard>
      </VMenu>
    </VBtn>
  </VBadge>
</template>

<style lang="scss">
.notification-section {
  padding: 14px !important;
}
</style>
