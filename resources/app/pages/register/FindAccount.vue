<template>
  <FindLayout page-type="id" :page-step="1">
    <div class="panel_group">
      <AppFormInput
        v-model:value="formData.name"
        v-model:error-messages="errorMessages"
        name="name"
        icon="fa-regular fa-user"
        :placehoder="t('placehoder.name')"
        :rules="rules.name"
        @keydown.enter="handleFindAccount"
      />

      <AppFormInput
        v-model:value="formData.parents_phone"
        v-model:error-messages="errorMessages"
        name="parents_phone"
        icon="fa-regular fa-mobile-notch"
        maxlength="11"
        :placehoder="t('placehoder.parents_phone')"
        :rules="rules.parents_phone"
        @keydown.enter="handleFindAccount"
      />
    </div>

    <ul class="box__error">
      <li v-for="message in errorMessages.name" :key="message">
        <span class="">{{ message }}</span>
      </li>
      <li v-for="message in errorMessages.parents_phone" :key="message">
        <span class="">{{ message }}</span>
      </li>
    </ul>

    <div class="panel__btns">
      <button class="btn--brand" @click="handleFindAccount">다음</button>
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

  const { nameRules, parentsPhoneRules } = useAppFormRules();

  const formData = useForm({
    name: '',
    parents_phone: '',
  });

  const rules = reactive<AppFormRules>({
    name: nameRules(),
    parents_phone: parentsPhoneRules(),
  });

  const errorMessages = ref<{ [key: string]: string[] }>({});

  const handleFindAccount = () => {
    const { success, newErrorMessages } = allValidateFormField(formData, rules);
    errorMessages.value = newErrorMessages;

    if (success) {
      formData.post(route('app.register.find-account.store'));
    }
  };
</script>
