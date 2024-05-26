<template>
  <Head
    ><title>{{ $page.props.page.title }}</title></Head
  >

  <AdminLayout>
    <StudentList />
  </AdminLayout>
</template>

<script setup lang="ts">
  import AdminLayout from '@/admin/layouts/AdminLayout.vue';
  import { Head } from '@inertiajs/vue3';
  import StudentList from '@/admin/pages/student/components/StudentList.vue';
</script>
