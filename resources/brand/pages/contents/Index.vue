<template>
  <BrandLayout>
    <Head><title>콘텐츠 소개</title></Head>
    <div class="hero">
      <div class="container">
        <h2>콘텐츠 소개</h2>
      </div>
    </div>
    <div class="sub contents">
      <section>
        <div class="container">
          <div class="contents__title">
            <h3>리딩수학은 달라요!</h3>
            <h4>입체적인 3가지 훈련</h4>
          </div>
          <ul class="lst__thumb">
            <li>
              <h5>개념 훈련</h5>
              <p>수학적 개념을 충분히 이해할 수 있도록 다양한 방법으로 수학적 사고를 유도해요.</p>
              <img src="@/assets/img/brand/thumb/thumb_contents_1-1.png" alt="개념 훈련 이미지" />
            </li>
            <li>
              <h5>유형 훈련</h5>
              <p>다양한 유형의 문제를 풀며 학습한 개념을 문제에 적용해 스스로 해결해 갈 수 있는 능력을 키워요.</p>
              <img src="@/assets/img/brand/thumb/thumb_contents_1-2.png" alt="유형 훈련 이미지" />
            </li>
            <li>
              <h5>서술형 훈련</h5>
              <p>긴 문장으로 된 문제를 끊어 읽고 해결하는 법을 익히며 문제 해결력을 극대화해요.</p>
              <img src="@/assets/img/brand/thumb/thumb_contents_1-3.png" alt="서술형 훈련 이미지" />
            </li>
          </ul>
        </div>
      </section>
      <section class="bg--gray">
        <div class="container">
          <div class="contents__title">
            <h3>리딩수학은 재밌어요!</h3>
            <h4>수학 행성 여행하며 꾸미기</h4>
          </div>
          <ul class="lst__thumb">
            <li>
              <p
                >리딩수학의 모든 학기는 각각 다른 행성으로 이루어져 있으며, <br />
                학습을 완료하면 행성 아이템을 획득할 수 있어서 재미있게 학습할 수 있어요.</p
              >
              <img src="@/assets/img/brand/thumb/thumb_contents_2-1.png" alt="훈련 행성 이미지" />
            </li>
          </ul>
        </div>
      </section>
      <section>
        <div class="container">
          <div class="contents__title">
            <h3>리딩수학은 달라요!</h3>
            <h4>입체적인 3가지 훈련</h4>
          </div>
          <ul class="lst__thumb">
            <li>
              <h5>학습기록</h5>
              <p>훈련을 완료할 때마다 어떻게 학습했는지 분석해서 학습기록으로 제공해요.</p>
              <img src="@/assets/img/brand/thumb/thumb_contents_3-1.png" alt="학습 기록 이미지" />
            </li>
            <li>
              <h5>오답노트</h5>
              <p>다양한 유형의 문제를 풀며 학습한 개념을 문제에 적용해 스스로 해결해 갈 수 있는 능력을 키워요.</p>
              <img src="@/assets/img/brand/thumb/thumb_contents_3-2.png" alt="학습 기록 이미지" />
            </li>
            <li>
              <h5>월간보고서</h5>
              <p>한 달 동안 학습한 데이터를 분석해서 학생과 부모님을 위해 보고서로 제공해요.</p>
              <img src="@/assets/img/brand/thumb/thumb_contents_3-3.png" alt="학습 기록 이미지" />
            </li>
          </ul>
        </div>
      </section>
      <section class="bg--gray">
        <div class="container">
          <div class="contents__title">
            <h3>리딩수학이 진단해요!</h3>
            <h4>수리문해력 종합진단평가</h4>
          </div>
          <ul class="lst__thumb">
            <li>
              <p
                >수학적 역량별, 내용 영역별, 단원별로 꼼꼼히 진단해서<br />
                수리문해력 종합 성취도를 한눈에 파악할 수 있게 보고서로 제공해요.
              </p>
              <img src="@/assets/img/brand/thumb/thumb_contents_4-1.png" alt="진단평가 이미지" />
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
