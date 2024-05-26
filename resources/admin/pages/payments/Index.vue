<template>
  <Head>
    <title>{{ $page.props.page.title }}</title>
  </Head>

  <AdminLayout>
    <PaymentList />
  </AdminLayout>
</template>

<script setup lang="ts">
  import { Head } from '@inertiajs/vue3';
  import AdminLayout from '@/admin/layouts/AdminLayout.vue';
  import PaymentList from './components/PaymentList.vue';
</script>
