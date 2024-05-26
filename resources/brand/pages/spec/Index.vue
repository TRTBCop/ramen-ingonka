<template>
  <BrandLayout>
    <Head><title>사양 안내</title></Head>
    <div class="hero">
      <div class="container">
        <h2>사양 안내</h2>
        <p>
          최적의 학습 환경을 위해 <br />
          권장사양 이상의 디바이스 사용을 권장드립니다.
        </p>
      </div>
    </div>
    <div class="sub">
      <div class="container">
        <table class="spec__table">
          <colgroup>
            <col span="1" style="" />
            <col span="2" style="width: 40%" />
          </colgroup>
          <thead>
            <tr>
              <th></th>
              <th scope="col">권장사양</th>
              <th scope="col">나의 사양</th>
            </tr>
          </thead>
          <tbody>
            <tr>
              <th scope="row">운영체제<br />(OS)</th>
              <td>Windows 11<br />Android 13<br />iPadOS 16</td>
              <td>
                <span class="is--primary">{{ userOS }}</span>
              </td>
            </tr>
            <tr>
              <th scope="row"
                >브라우저 <br />
                버전</th
              >
              <td>Chrome 119</td>
              <td>
                <span class="is--primary">{{ userBrowser }}</span
                ><br />
                <a href="https://www.google.com/intl/ko_kr/chrome/" target="blank" class="btn--brand"
                  >구글 크롬 <br />
                  다운로드</a
                >
              </td>
            </tr>
            <tr>
              <th scope="row">해상도</th>
              <td>1200*800</td>
              <td
                ><span class="is--primary">{{ userDisplay }}</span></td
              >
            </tr>
          </tbody>
        </table>
      </div>
    </div>
  </BrandLayout>
</template>

<script setup lang="ts">
  import { ref } from 'vue';
  import { Head } from '@inertiajs/vue3';
  import BrandLayout from '@/brand/layouts/BrandLayout.vue';
  import { UAParser } from 'ua-parser-js';

  const parser = new UAParser();
  const result = parser.getResult();

  // 유저 운영체제
  const userOS = ref(`${result.os.name} ${result.os.version}`);

  // 유저 브라우저 버전
  const userBrowser = ref(`${result.browser.name} ${result.browser.version}`);

  const userDisplay = ref(`${window.screen.width}*${window.screen.height}`);
</script>
