<template>
  <Teleport to="body">
    <div v-if="isShow" class="modal modal--md">
      <div class="modal_head">
        <h3>프로필 캐릭터</h3>
        <button class="btn--close" @click="closeModal">
          <font-awesome-icon icon="fa-regular fa-xmark" />
        </button>
      </div>
      <div class="modal_body profile_wrap">
        <button
          v-for="profileImgType in profileImgTypes"
          :key="profileImgType"
          :class="{
            current: profileImgType === selectedType,
          }"
          @click="selectType(profileImgType)"
          ><i :class="`profile__img profile__img--md profile__img--${getUserProfileType(profileImgType)}`"></i
        ></button>
      </div>
      <div class="btns">
        <button class="btn--brand" @click="updateGradeTerm">확인</button>
      </div>
    </div>
  </Teleport>
</template>

<script setup lang="ts">
  import { ref, PropType } from 'vue';
  import { router } from '@inertiajs/vue3';
  import { getUserData, getUserProfileType, profileImgTypes } from '@/app/core/helpers/userHelper';

  defineProps({
    isShow: {
      type: Boolean as PropType<boolean>,
      default: false,
    },
  });

  const emits = defineEmits(['update:isShow']);

  const selectedType = ref(getUserData().profile_img_type);

  function selectType(type: number) {
    selectedType.value = type;
  }

  function closeModal() {
    emits('update:isShow', false);
  }

  function updateGradeTerm() {
    router.patch(
      route('app.my.profile-image.update'),
      {
        profile_img_type: selectedType.value,
      },
      {
        onFinish() {
          closeModal();
        },
      },
    );
  }
</script>
