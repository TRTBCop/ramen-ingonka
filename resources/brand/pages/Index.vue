<template>
  <BrandLayout>
    <Head><title>리딩수학</title></Head>
    <div class="hero">
      <h2>
        <span>수리문해력을 키우기 위한</span> <br />
        바른 수학 공부법, 리딩수학
      </h2>
      <p>리딩수학의 수학 행성을 여행하며 <br />수리문해력을 키워 보세요!</p>
      <div class="hero__bottom">
        <img class="" src="@/assets/img/brand/bg/main_character.svg" alt="메인 캐릭터 이미지" />
      </div>
    </div>
    <div class="contents">
      <section>
        <div class="container">
          <div class="main__title">
            <h3 class="highlight--gray">수학 공부를 어떻게 해야 할 지 고민이에요.</h3>
          </div>
          <ul class="main__bubble">
            <li class="row">
              <img src="@/assets/img/brand/ico/main_bubble_1.svg" alt="고민하는 얼굴1" />
              <p class="bubble">문제를 읽어도 무슨 말인지 몰라서 못 풀겠어요.</p>
            </li>
            <li class="row">
              <p class="bubble">단순 계산은 잘 하는데 응용 문제는 쩔쩔매요.</p>
              <img src="@/assets/img/brand/ico/main_bubble_2.svg" alt="고민하는 얼굴2" />
            </li>
            <li class="row">
              <img src="@/assets/img/brand/ico/main_bubble_3.svg" alt="고민하는 얼굴3" />
              <p class="bubble">조금이라도 문제가 변형되면 무조건 틀려요.</p>
            </li>
          </ul>
        </div>
      </section>
      <section class="bg_student">
        <div class="container pb-0">
          <div class="main__title">
            <h3 class="highlight">수리문해력에 그 해답이 있어요.</h3>
            <p>
              수학은 문제를 제대로 읽고, 주어진 조건이 무엇인지 파악하고,<br />
              그 속에 있는 수학적 상황을 정확히 이해해야 답을 구할 수 있어요.<br />
              수리문해력은 위와 같은 수학의 모든 과정을 해석하고 이해하는 능력이에요.<br />
              수리문해력이 튼튼하다면 그 어떤 문제도 맞서 해결할 수 있어요!
            </p>
          </div>
          <img src="@/assets/img/brand/bg/main_student.png" alt="개념 훈련 이미지" />
        </div>
      </section>
      <section class="bg--gray">
        <div class="container">
          <div class="main__title">
            <h3>리딩수학과 함께 수리문해력을 키워 보세요.</h3>
            <p>
              리딩수학은 단순히 많은 수학 문제를 풀면서 유형에만 익숙하게 하는 방식이 아니라 <br />
              학생 스스로 수학적 사고력을 형성, 확장, 강화할 수 있게<br />
              입체적인 학습이 되도록 프로그램이 설계되었어요.
            </p>
          </div>
          <ul class="main__thumb">
            <li>
              <img src="@/assets/img/brand/thumb/thumb_main_1-1.png" alt="개념 훈련 이미지" />
              <span
                >입체적인 <br />
                3가지 훈련</span
              >
            </li>
            <li>
              <img src="@/assets/img/brand/thumb/thumb_main_1-2.png" alt="개념 훈련 이미지" />
              <span
                >재미있는 <br />
                수학 행성 여행</span
              >
            </li>
            <li>
              <img src="@/assets/img/brand/thumb/thumb_main_1-3.png" alt="개념 훈련 이미지" />
              <span
                >체계적인 <br />
                학습 관리</span
              >
            </li>
          </ul>
        </div>
      </section>
      <section class="contact">
        <div class="container">
          <div class="row">
            <img src="@/assets/img/brand/ico/contact_character_2.svg" alt="5일 무료체험 캐릭터 이미지1" />
            <div class="col">
              <a href="" class=""
                >스스로 공부하는 습관을 심어주어 <br />수리문해력을 키워주는 리딩수학,<br />
                지금 바로 시작하세요!</a
              >
              <StartFreeButotn />
            </div>
            <img
              class="only_pc"
              src="@/assets/img/brand/ico/contact_character_1.svg"
              alt="5일 무료체험 캐릭터 이미지2"
            />
          </div>
        </div>
      </section>
    </div>
  </BrandLayout>
</template>

<script setup lang="ts">
  import { Head } from '@inertiajs/vue3';
  import BrandLayout from '@/brand/layouts/BrandLayout.vue';
  import StartFreeButotn from '@/brand/components/buttons/StartFreeButotn.vue';
</script>
