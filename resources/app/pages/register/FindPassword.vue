<template>
  <FindLayout page-type="password" :page-step="1">
    <div class="panel_group">
      <AppFormInput
        v-model:value="formData.access_id"
        v-model:error-messages="errorMessages"
        name="access_id"
        icon="fa-regular fa-user"
        :placehoder="t('placehoder.access_id')"
        :rules="rules.access_id"
        @keydown.enter="handleFindPassword"
      />

      <AppFormInput
        v-model:value="formData.name"
        v-model:error-messages="errorMessages"
        name="name"
        icon="fa-regular fa-user"
        :placehoder="t('placehoder.name')"
        :rules="rules.name"
        @keydown.enter="handleFindPassword"
      />

      <AppFormInput
        v-model:value="formData.parents_phone"
        v-model:error-messages="errorMessages"
        name="parents_phone"
        icon="fa-regular fa-mobile-notch"
        :placehoder="t('placehoder.parents_phone')"
        :rules="rules.parents_phone"
        @keydown.enter="handleFindPassword"
      />
    </div>

    <ul class="box__error">
      <li v-for="message in errorMessages.access_id" :key="message">
        <span class="">{{ message }}</span>
      </li>
      <li v-for="message in errorMessages.name" :key="message">
        <span class="">{{ message }}</span>
      </li>
      <li v-for="message in errorMessages.parents_phone" :key="message">
        <span class="">{{ message }}</span>
      </li>
    </ul>

    <div class="panel__btns">
      <button class="btn--brand" @click="handleFindPassword">다음</button>
    </div>
  </FindLayout>
</template>

<script setup lang="ts">
  import { ref, reactive } from 'vue';
  import FindLayout from '@/app/layouts/FindLayout.vue';
  import { useForm } from '@inertiajs/vue3';
  import { AppFormRules, allValidateFormField, useAppFormRules } from '@/app/core/helpers/validator';
  import AppFormInput from '@/app/components/AppFormInput.vue';
  import { useI18n } from 'vue-i18n';

  const { t } = useI18n();

  const { accessIdRules, nameRules, parentsPhoneRules } = useAppFormRules();

  const formData = useForm({
    access_id: '',
    name: '',
    parents_phone: '',
  });

  const rules = reactive<AppFormRules>({
    access_id: accessIdRules(),
    name: nameRules(),
    parents_phone: parentsPhoneRules(),
  });

  const errorMessages = ref<{ [key: string]: string[] }>({});

  const handleFindPassword = () => {
    const { success, newErrorMessages } = allValidateFormField(formData, rules);
    errorMessages.value = newErrorMessages;

    if (success) {
      formData.post(route('app.register.find-password.store'));
    }
  };
</script>
