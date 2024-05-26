<template>
  <div id="wrap">
    <div class="result_wrap bg--white">
      <div class="result">
        <div class="result__title">
          <h3
            >{{ pageData.props.name }}<span v-if="pageData.props.access_id">({{ pageData.props.access_id }})</span>님,
            반가워요!</h3
          >
          <p>리딩수학에 가입해주셔서 감사합니다.</p>
          <Vue3Lottie class="ico__result" :animation-data="ImgCheck" :height="200" :width="200" :loop="false" />
        </div>

        <div class="slide_up">
          <div>
            <span
              >{{ dayjs(pageData.props.free_trial_period.start).format('YYYY년 MM월 DD일') }}부터
              {{ dayjs(pageData.props.free_trial_period.end).format('YYYY년 MM월 DD일') }}까지 <br />
              5일 동안 무료 체험을 진행할 수 있어요.</span
            >
          </div>

          <ul class="benefits">
            <li>
              <strong>5일 무료 체험</strong>
              <font-awesome-icon class="ico--benefit" icon="fa-regular fa-laptop"></font-awesome-icon>
            </li>
            <li>
              <strong>소단원 1회차</strong><br /><span>훈련 3개</span>
              <font-awesome-icon class="ico--benefit" icon="fa-regular fa-scroll"></font-awesome-icon>
            </li>
            <li>
              <strong>진단평가</strong><br /><span>1학기</span>
              <font-awesome-icon class="ico--benefit" icon="fa-regular fa-school"></font-awesome-icon>
            </li>
          </ul>

          <span>개념으로 문제를 해결하는 1등들의 바른 수학 공부법, 시작해볼까요?</span>
          <div class="btns">
            <button class="btn--brand" @click="goLoginPage">로그인하고 무료체험 시작하기</button>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup lang="ts">
  import { PageProps } from '@/app/types/pageData';
  import { usePage } from '@inertiajs/vue3';
  import { dayjs } from 'element-plus';
  import ImgCheck from '@/assets/img/math/check.json';
  import { Vue3Lottie } from 'vue3-lottie';
  import { goLoginPage } from '@/app/core/helpers/routerHelper';

  interface Page extends PageProps {
    name: string;
    access_id: string;
    free_trial_period: {
      start: string;
      end: string;
      round: 3;
    };
    route_name: string;
  }

  const pageData = usePage<Page>();
</script>
