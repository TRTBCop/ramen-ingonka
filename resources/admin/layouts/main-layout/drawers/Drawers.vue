<template>
  <KTMessengerDrawer />
  <KTActivityDrawer />
  <KTToolButtons />
  <KTHelpDrawer />
</template>

<script lang="ts">
  import { defineComponent } from 'vue';
  import KTMessengerDrawer from '@/admin/layouts/main-layout/extras/MessengerDrawer.vue';
  import KTActivityDrawer from '@/admin/layouts/main-layout/drawers/ActivityDrawer.vue';
  import KTHelpDrawer from '@/admin/layouts/main-layout/extras/HelpDrawer.vue';
  import KTToolButtons from '@/admin/layouts/main-layout/extras/ToolButtons.vue';

  export default defineComponent({
    name: 'GlobalDrawers',
    components: {
      KTMessengerDrawer,
      KTActivityDrawer,
      KTHelpDrawer,
      KTToolButtons,
    },
  });
</script>
