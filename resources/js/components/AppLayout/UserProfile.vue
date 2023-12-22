<script setup>
import { computed } from 'vue';
import { router, usePage } from '@inertiajs/vue3'
// import { initialAbility } from '@plugins/casl/ability';
// import { useAppAbility } from '@plugins/casl/useAppAbility';
// import avatar1 from '@images/avatars/avatar-3.png';

const userProfile = computed(() => {
  return {
    name: usePage().props.auth.user?.data?.name ?? 'No identificado',
    email: usePage().props.auth.user?.data?.email ?? '',
    // avatar: avatar1,
    job_function: usePage().props.auth.user?.data?.job_function ?? 'No identificado',
  };
});

const logout = () => {
  router.post('/logout');
};
</script>

<template>
  <VAvatar class="cursor-pointer" color="primary" variant="tonal">
    <!-- <VImg :src="avatar1" /> -->
    <VIcon icon="mdi-account" />

    <!-- SECTION Menu -->
    <VMenu activator="parent" width="230" location="bottom end" offset="14px">
      <VList>
        <!-- 👉 User Avatar & Name -->
        <VListItem>
          <template #prepend>
            <VListItemAction start>
              <VAvatar color="primary" variant="tonal">
                <!-- <VImg :src="avatar1" /> -->
                <VIcon icon="mdi-account" />
              </VAvatar>
            </VListItemAction>
          </template>

          <VListItemTitle>
            <span>{{ userProfile.name }}</span>
          </VListItemTitle>
          <VListItemSubtitle>{{ userProfile.job_function }}</VListItemSubtitle>
        </VListItem>
        <!-- <VDivider class="my-2" /> -->

        <!-- 👉 Profile -->
        <!-- <VListItem link>
          <template #prepend>
            <VIcon class="me-2" icon="mdi-account-outline" size="22" />
          </template>

          <VListItemTitle>Perfil</VListItemTitle>
        </VListItem> -->

        <!-- 👉 Settings -->
        <!-- <VListItem link>
          <template #prepend>
            <VIcon class="me-2" icon="mdi-cog-outline" size="22" />
          </template>

          <VListItemTitle>Ajustes</VListItemTitle>
        </VListItem> -->

        <!-- Divider -->
        <VDivider class="my-2" />

        <VListItem link @click="logout">
          <template #prepend>
            <VIcon class="me-2" icon="mdi-logout-variant" size="22" />
          </template>

          <VListItemTitle>Cerrar sesión</VListItemTitle>
        </VListItem>
      </VList>
    </VMenu>
    <!-- !SECTION -->
  </VAvatar>
</template>
