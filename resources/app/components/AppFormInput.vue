<template>
  <div
    class="input"
    :class="{
      error: isError,
    }"
  >
    <font-awesome-icon :icon="icon" />
    <input
      :value="value"
      :name="name"
      :type="type"
      class="input__text"
      :placeholder="placehoder"
      :maxlength="maxlength"
      :disabled="disabled"
      :readonly="readonly"
      @input="updateValue"
      @blur="handleValidateFormField"
    />
    <slot></slot>
  </div>
</template>

<script setup lang="ts">
  import { computed, PropType } from 'vue';
  import { AppFormRule, validateFormField } from '../core/helpers/validator';
  import { cloneDeep, set } from 'lodash';

  const props = defineProps({
    icon: {
      type: String as PropType<string>,
      default: '',
    },
    name: {
      type: String as PropType<string>,
      default: '',
      required: true,
    },
    placehoder: {
      type: String as PropType<string>,
      default: '',
    },
    type: {
      type: String as PropType<string>,
      default: 'text',
    },
    maxlength: {
      type: [String, Number] as PropType<string | number>,
      default: '',
    },
    disabled: {
      type: Boolean as PropType<boolean>,
      default: false,
    },
    readonly: {
      type: Boolean as PropType<boolean>,
      default: false,
    },
    rules: {
      type: [Array, undefined] as PropType<AppFormRule[] | null | undefined>,
      default: undefined,
    },
    value: {
      type: [String, Number, null] as PropType<string | number | null>,
      default: '',
      required: true,
    },
    errorMessages: {
      type: Object as PropType<{ [key: string]: string[] }>,
      default: () => ({}),
    },
  });
  const emit = defineEmits(['update:value', 'update:errorMessages']);

  const isError = computed(() => {
    return Boolean(props.errorMessages[props.name] && props.errorMessages[props.name].length > 0);
  });

  const updateValue = (event: Event) => {
    emit('update:value', (event.target as HTMLInputElement).value);
  };

  const handleValidateFormField = () => {
    if (!props.rules) return;
    updateErrorMessages(validateFormField(props.value, props.rules));
  };

  const updateErrorMessages = (value: string[]) => {
    const newValue = cloneDeep(props.errorMessages);
    set(newValue, props.name, value);

    emit('update:errorMessages', newValue);
  };
</script>
