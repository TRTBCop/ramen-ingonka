<template>
  <DefaultLayout>
    <section class="container">
      <strong>마케팅 활용 동의</strong>
      <div class="policy">
        <h2>본 동의 문에는 전화, 문자 등을 활용한 광고성 정보 수신 동의 내용이 포함되어 있습니다.</h2>
        <p>
          마케팅 활용에 선택적으로 동의하신 고객님에 한하여 보다 다양한 정보를 제공하고 서비스 질을 향상시키기 위한 상품
          및 서비스 이용 안내, 이벤트 및 마케팅 참여 기회 제공 안내를 받으실 수 있습니다.
        </p>
        <table>
          <colgroup>
            <col style="width: calc(100% / 3)" />
            <col style="width: calc(100% / 3)" />
            <col style="width: calc(100% / 3)" />
          </colgroup>
          <thead>
            <tr>
              <th>이용목적</th>
              <th>이용항목</th>
              <th>보유·이용 기간</th>
            </tr>
          </thead>
          <tbody>
            <tr>
              <td rowspan="2">전화, 문자 등을 이용한 이벤트 및 마케팅 안내, 추천 상품 및 서비스 정보 전달</td>
              <td>학부모(법정대리인 포함) 이름 및 휴대폰번호</td>
              <td rowspan="2">동의철회 또는 회원 탈퇴 시 즉시 파기</td>
            </tr>
            <tr>
              <td>회원 이름 및 휴대폰번호</td>
            </tr>
          </tbody>
        </table>
        <p>※ 마케팅 활용 동의 철회 안내 : ‘로그인 > 마이페이지’ 및 고객센터(1800-5039)</p>
      </div>
    </section>
  </DefaultLayout>
</template>

<script setup lang="ts">
  import DefaultLayout from '@/html/layouts/DefaultLayout.vue';
</script>
