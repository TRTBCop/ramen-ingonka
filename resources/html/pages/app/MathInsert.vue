<template>
  <AppLearningLayout :btns-show="true">
    <div class="panels train_descriptive">
      <div class="panel">
        <button class="panel__btn">
          <span>
            <font-awesome-icon icon="fa-regular fa-q" />
            문제
          </span>
          <font-awesome-icon icon="fa-regular fa-angles-up-down" />
        </button>

        <div class="question_area">
          <div class="articles">
            <span class="image"
              ><img
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
              /><br />한 원에서 원의 반지름의 길이는 모두 같으므로 삼각형 ㄱㄴㅇ, 삼각형 ㅇㄴㄷ, 삼각형 ㄱㅇㄷ은 모두
              <em class="blank">빈칸입니다</em>입니다</span
            >
          </div>
        </div>
        <div class="dim">
          <div class="insert">
            <img class="action" src="../../../assets/img/math/loading.png" alt="" />
            <span class="is--size4">다음 문제도 풀어볼까요?</span>
          </div>
        </div>
      </div>
      <AppMathButton :is-show="true" />
    </div>
  </AppLearningLayout>
</template>

<script setup lang="ts">
  import AppLearningLayout from '@/html/layouts/AppResult.vue';
</script>
