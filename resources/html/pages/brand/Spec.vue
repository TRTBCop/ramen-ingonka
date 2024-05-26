<template>
  <BrandSub>
    <div class="hero">
      <div class="container">
        <h2>사용 안내</h2>
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
            <!--          <tr>-->
            <!--            <th scope="row">구분</th>-->
            <!--            <td>태블릿 / PC</td>-->
            <!--            <td>PC</td>-->
            <!--          </tr>-->
            <tr>
              <th scope="row">운영체제<br />(OS)</th>
              <td>Windows 11<br />Android 13<br />iPadOS 16</td>
              <td>
                <span class="is--primary">Windows 10</span>
              </td>
            </tr>
            <tr>
              <th scope="row"
                >브라우저 <br />
                버전</th
              >
              <td>Chrome 119</td>
              <td>
                <span class="is--primary">Chrome 119</span><br />
                <a href="https://www.google.com/intl/ko_kr/chrome/" target="blank" class="btn--brand"
                  >구글 크롬 <br />
                  다운로드</a
                >
              </td>
            </tr>
            <tr>
              <th scope="row">해상도</th>
              <td>1200*800</td>
              <td><span class="is--primary">1024*768</span></td>
            </tr>
          </tbody>
        </table>
      </div>
    </div>
  </BrandSub>
</template>

<script setup lang="ts">
  import BrandSub from '@/html/layouts/BrandSub.vue';
</script>
