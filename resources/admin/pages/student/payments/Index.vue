<template>
  <Head
    ><title>{{ $page.props.page.title }}</title></Head
  >
  <AdminLayout>
    <Navbar />
    <PaymentList />
  </AdminLayout>
</template>

<script setup lang="ts">
  import AdminLayout from '@/admin/layouts/AdminLayout.vue';
  import Navbar from '@/admin/pages/student/components/Navbar.vue';
  import { Head } from '@inertiajs/vue3';
  import PaymentList from '@/admin/pages/payments/components/PaymentList.vue';
</script>
