<template>
  <KTUpgradePlanModal />
  <KTCreateAppModal />
  <KTNewTargetModal />
  <KTViewUsersModal />
  <KTInviteFriendsModal />
</template>

<script lang="ts">
  import { defineComponent } from 'vue';
  import KTUpgradePlanModal from '@/admin/components/modals/general/UpgradePlanModal.vue';
  import KTCreateAppModal from '@/admin/components/modals/wizards/create-app-modal/CreateAppModal.vue';
  import KTNewTargetModal from '@/admin/components/modals/forms/NewTargetModal.vue';
  import KTViewUsersModal from '@/admin/components/modals/general/ViewUsersModal.vue';
  import KTInviteFriendsModal from '@/admin/components/modals/general/InviteFriendsModal.vue';

  export default defineComponent({
    name: 'GlobalModals',
    components: {
      KTUpgradePlanModal,
      KTCreateAppModal,
      KTNewTargetModal,
      KTViewUsersModal,
      KTInviteFriendsModal,
    },
  });
</script>
