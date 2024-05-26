<template>
  <div id="kt_app_sidebar_footer" class="app-sidebar-footer flex-column-auto pt-2 pb-6 px-6">
    <div class="text-dark order-2 order-md-1 text-center">
      <Link title="리딩수학" :href="route('brand.index')" class="text-gray-800 text-hover-primary">리딩수학</Link>
    </div>
  </div>
</template>

<script lang="ts">
  import { getAssetPath } from '@/admin/core/helpers/assets';
  import { Link } from '@inertiajs/vue3';
  import { defineComponent } from 'vue';

  export default defineComponent({
    name: 'SidebarFooter',
    components: { Link },
    setup() {
      return {
        getAssetPath,
      };
    },
  });
</script>
