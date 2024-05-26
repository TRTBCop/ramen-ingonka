<template>
  <AppLearningLayout>
    <div class="progress">progress영역</div>

    <div class="quiz_area">
      <span> 기본 문제 텍스트가 들어가는 곳입니다. </span>
      <span class="image">
        <img
          src="https://dailykor.com/data/math/text_img/58_4_137_1611035156.png"
          style="
            width: 175px;
            display: block;
            vertical-align: top;
            margin: 5px auto;
            text-align: center;
            cursor: pointer;
            padding: 0px;
            position: relative;
            max-width: 100%;
            border: 0px;
            font: inherit;
          "
          fr-original-style="width: 175px; display: block; vertical-align: top; margin: 5px auto; text-align: center;"
          fr-original-class="fr-draggable"
        /><br />
      </span>

      <div class="answers mb-1">
        <button class="">
          <span><img src="../../../assets/img/math/brand_logo.svg" alt="" /></span>
        </button>
        <button class="">
          <span><img src="../../../assets/img/math/brand_logo.svg" alt="" /></span
        ></button>
        <!--      <button class="btn--check"><span>다중선택</span></button>-->
        <button class=""
          ><span><img src="../../../assets/img/math/brand_logo.svg" alt="" /></span
        ></button>
        <button
          ><span><img src="../../../assets/img/math/brand_logo.svg" alt="" /></span
        ></button>
      </div>

      <div class="answers col mb-1">
        <button class="">
          <span><img src="../../../assets/img/math/brand_logo.svg" alt="" /></span>
        </button>
        <button class="">
          <span><img src="../../../assets/img/math/brand_logo.svg" alt="" /></span
        ></button>
        <!--      <button class="btn--check"><span>다중선택</span></button>-->
        <button class=""
          ><span><img src="../../../assets/img/math/brand_logo.svg" alt="" /></span
        ></button>
        <button
          ><span><img src="../../../assets/img/math/brand_logo.svg" alt="" /></span
        ></button>
      </div>

      <div class="answers mb-1">
        <button class="btn--correct">
          <span>정답</span>
        </button>
        <button class="btn--wrong"><span>오답</span></button>
        <!--      <button class="btn--check"><span>다중선택</span></button>-->
        <button class="btn--check"><span>다중선택</span></button>
        <button><span>작아지므로</span></button>
      </div>

      <div class="answers col mb-1">
        <button class="btn--correct">
          <span>정답</span>
        </button>
        <button class="btn--wrong"><span>오답</span></button>
        <!--      <button class="btn--check"><span>다중선택</span></button>-->
        <button class="btn--check"><span>다중선택</span></button>
        <button><span>작아지므로</span></button>
      </div>

      <div class="answers toasts on">
        <button class="btn--correct">
          <span>정답</span>
        </button>
        <button class="btn--wrong"><span>오답</span></button>
        <!--      <button class="btn--check"><span>다중선택</span></button>-->
        <button class="btn--check"><span>다중선택</span></button>
        <button><span>작아지므로</span></button>
      </div>
      <div class=""> </div>
    </div>
  </AppLearningLayout>
</template>

<script setup lang="ts">
  import AppLearningLayout from '@/html/layouts/AppResult.vue';
  import { FontAwesomeIcon } from '@fortawesome/vue-fontawesome';
</script>
