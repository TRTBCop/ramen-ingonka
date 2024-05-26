<template>
  <DefaultLayout>
    <div class="modal">
      <div class="modal_head">
        <button class="btn--close">
          <font-awesome-icon icon="fa-regular fa-xmark" />
        </button>
      </div>
      <div class="modal_body">
        <strong class="is--size4">타이틀이 있는 모달</strong>
        <p class="">타이틀이 있는 모달입니다. (max-width: 320px)</p>
      </div>
      <div class="btns">
        <button class="btn--sub">버튼</button>
        <button class="btn--brand">버튼</button>
      </div>
    </div>
    <div class="modal">
      <div class="modal_head">
        <button class="btn--close">
          <font-awesome-icon icon="fa-regular fa-xmark" />
        </button>
      </div>
      <div class="modal_body">
        <p class="">타이틀이 없는 모달입니다.</p>
      </div>
      <div class="btns">
        <button class="btn--sub">버튼</button>
        <button class="btn--brand">버튼</button>
      </div>
    </div>
    <div class="modal modal--md"
      ><div class="modal_head"
        ><strong>결제 및 환불 규정</strong
        ><button class="btn--close"
          ><svg
            class="svg-inline--fa fa-xmark"
            aria-hidden="true"
            focusable="false"
            data-prefix="far"
            data-icon="xmark"
            role="img"
            xmlns="http://www.w3.org/2000/svg"
            viewBox="0 0 384 512"
          >
            <path
              class=""
              fill="currentColor"
              d="M345 137c9.4-9.4 9.4-24.6 0-33.9s-24.6-9.4-33.9 0l-119 119L73 103c-9.4-9.4-24.6-9.4-33.9 0s-9.4 24.6 0 33.9l119 119L39 375c-9.4 9.4-9.4 24.6 0 33.9s24.6 9.4 33.9 0l119-119L311 409c9.4 9.4 24.6 9.4 33.9 0s9.4-24.6 0-33.9l-119-119L345 137z"
            ></path></svg></button
      ></div>
      <div class="modal_body modal_body__txt"
        ><ul class="lst--bullet"
          ><li>
            이용권은 약정 기간 동안 월납 또는 일시납으로 금액을 지불하고 리딩수학의 서비스를 자유롭게 이용하는
            상품입니다. </li
          ><li>사용 중인 이용권이 있는 경우, 동일 계정에서 이용권 추가 구매 시 사용기간이 연장됩니다.</li
          ><li>이용권은 일시 정지가 불가합니다.</li
          ><li>이용권의 환불 시 이용한 일수만큼 정가 기준으로 일할 계산하여 제외한 금액이 환불됩니다.</li
          ><li>환불 후에는 리딩수학의 모든 서비스를 즉시 이용할 수 없습니다.</li
          ><li>상담 관련 문의사항은 고객센터로 문의 바랍니다. (1800-5039) </li></ul
        ></div
      >
      <div class="btns">
        <button class="btn--brand">확인</button>
      </div>
    </div>
    <div class="modal modal--md">
      <div class="modal_head">
        <button class="btn--close">
          <font-awesome-icon icon="fa-regular fa-xmark" />
        </button>
      </div>
      <div class="modal_body">
        <strong class="is--size4">타이틀이 있는 모달</strong>
        <p class="">사용 가능한 아이디입니다.</p>
        <p class="">사용 가능한 아이디입니다.</p>
      </div>
      <div class="btns">
        <button class="btn--sub">버튼</button>
        <button class="btn--brand">버튼</button>
      </div>
    </div>
    <div class="modal modal--md">
      <div class="modal_head">
        <button class="btn--close">
          <font-awesome-icon icon="fa-regular fa-xmark" />
        </button>
      </div>
      <div class="modal_body">
        <p class="">사용 가능한 아이디입니다.</p>
      </div>
      <div class="btns">
        <button class="btn--sub">버튼</button>
        <button class="btn--brand">버튼</button>
      </div>
    </div>
    <div class="modal modal--md">
      <div class="modal_head">
        <h3>모달 타이틀</h3>
        <button class="btn--close">
          <font-awesome-icon icon="fa-regular fa-xmark" />
        </button>
      </div>
      <div class="modal_body modal_body__txt">
        <p class=""
          >제1장 총칙 제1조 (목적) 본 약관은 주식회사 매일국어(이하 회사라고 합니다.)가 운영하는 웹사이트
          리딩수학(app.readingmath.app, 이하 “웹사이트”라고 합니다.)을 통하여 제공하는 교육서비스(이하 서비스라고
          합니다.)의 이용과 관련하여 회사와 이용자 사이에 권리와 의무 및 기타 필요한 사항을 규정하는 것을 목적으로
          합니다. 제2조 (정의) 본 약관에서 사용하는 용어의 정의는 다음과 같습니다. 서비스 : 회사가 운영하는 웹사이트를
          통해 제공하는 모든 교육서비스를 말합니다. 이용자 : 회사의 웹사이트에 접속하여 본 약관에 따라 회사가 제공하는
          서비스를 이용하는 회원 및 비회원을 말합니다. 회원 : 회사의 웹사이트에 접속하여 본 약관에 동의함으로써 회사와
          이용계약을 체결하고 아이디(ID)를 부여받은 이용자로서 회사가 제공하는 정보와 온라인 서비스를 지속적으로 이용할
          수 있는 자를 말합니다. 아이디(ID) : 이용자의 식별 및 서비스 이용을 위하여 이용자가 정하고 회사가 승인하는
          영문과 숫자의 조합을 말합니다. 비밀번호(Password) : 서비스 이용 시, 아이디와 일치되는 이용자임을 확인하고
          이용자의 개인정보 보호를 위하여 이용자 자신이 정한 영문과 숫자의 조합을 말합니다. 콘텐츠 : 회사에서 제공하는
          모든 서비스 및 기타 관련 정보를 의미하며, 정보통신망 이용촉진 및 정보보호 등에 관한 법률 제2조 제1항 제1호의
          규정에 의한 정보통신망에서 사용되는 부호, 문자, 음성, 음향, 이미지, 영상 등으로 표현된 자료 또는 정보를
          의미합니다. 무료 체험 : 정식 계약 체결 전에 제공된 기간 동안 무료로 서비스를 이용해보는 것을 말합니다. 운영자
          : 서비스의 전반적인 관리와 원활한 운영을 위하여 회사에서 선정한 사람 또는 회사를 말합니다. 이 약관에서
          사용하는 정의는 본 조에서 정하는 것을 제외하고는 관계법령 및 서비스 별 안내에서 정하는 바에 의합니다.
        </p>
      </div>
      <div class="btns">
        <button class="btn--brand">버튼</button>
      </div>
    </div>
    <div class="modal modal--md">
      <div class="modal_head">
        <button class="btn--close">
          <font-awesome-icon icon="fa-regular fa-xmark" />
        </button>
      </div>
      <!-- [D] 내용 height가 center가 아니어야 될 시 : .modal_body__top -->
      <div class="modal_body modal_body__top">
        <strong class="is--size4">학습할 학기를 선택해 주세요.</strong>
        <span>(선택한 학기는 마이페이지의 내 정보에서 변경이 가능해요.)</span>
        <label class="switch grade__switch">
          <input type="checkbox" checked />
          <span class="slider"><small class="switch__text"></small></span>
        </label>
        <ul class="modal__semester">
          <li>
            <input id="semester1" type="radio" name="lst_semester" value="1" />
            <label for="semester1">3학년 1학기</label>
          </li>
          <li>
            <input id="semester2" type="radio" name="lst_semester" value="2" />
            <label for="semester2">3학년 2학기</label>
          </li>
          <li>
            <input id="semester3" type="radio" name="lst_semester" value="3" />
            <label for="semester3">4학년 1학기</label>
          </li>
          <li>
            <input id="semester4" type="radio" name="lst_semester" value="4" />
            <label for="semester4">4학년 2학기</label>
          </li>
          <li>
            <input id="semester5" type="radio" name="lst_semester" value="5" />
            <label for="semester5">5학년 1학기</label>
          </li>
          <li>
            <input id="semester6" type="radio" name="lst_semester" value="6" />
            <label for="semester6">5학년 2학기</label>
          </li>
          <li>
            <input id="semester7" type="radio" name="lst_semester" value="7" />
            <label for="semester7">6학년 1학기</label>
          </li>
          <li>
            <input id="semester8" type="radio" name="lst_semester" value="8" />
            <label for="semester8">6학년 2학기</label>
          </li>
        </ul>
      </div>
      <div class="btns">
        <button class="btn--brand">시작하기</button>
      </div>
    </div>
    <div class="modal modal--md">
      <div class="modal_head">
        <h3>프로필 캐릭터</h3>
        <button class="btn--close">
          <font-awesome-icon icon="fa-regular fa-xmark" />
        </button>
      </div>
      <div class="modal_body profile_wrap">
        <button><i class="profile__img profile__img--md profile__img--type01"></i></button>
        <button class="current"><i class="profile__img profile__img--md profile__img--type02"></i></button>
        <button><i class="profile__img profile__img--md profile__img--type03"></i></button>
        <button><i class="profile__img profile__img--md profile__img--type04"></i></button>
        <button class="current"><i class="profile__img profile__img--md profile__img--type05"></i></button>
        <button><i class="profile__img profile__img--md profile__img--type06"></i></button>
        <button><i class="profile__img profile__img--md profile__img--type07"></i></button>
        <button><i class="profile__img profile__img--md profile__img--type08"></i></button>
      </div>
      <div class="btns">
        <button class="btn--brand">확인</button>
      </div>
    </div>
    <div class="modal modal--md"
      ><div class="modal_head"
        ><strong>결제 및 환불 규정</strong
        ><button class="btn--close"
          ><svg
            class="svg-inline--fa fa-xmark"
            aria-hidden="true"
            focusable="false"
            data-prefix="far"
            data-icon="xmark"
            role="img"
            xmlns="http://www.w3.org/2000/svg"
            viewBox="0 0 384 512"
          >
            <path
              class=""
              fill="currentColor"
              d="M345 137c9.4-9.4 9.4-24.6 0-33.9s-24.6-9.4-33.9 0l-119 119L73 103c-9.4-9.4-24.6-9.4-33.9 0s-9.4 24.6 0 33.9l119 119L39 375c-9.4 9.4-9.4 24.6 0 33.9s24.6 9.4 33.9 0l119-119L311 409c9.4 9.4 24.6 9.4 33.9 0s9.4-24.6 0-33.9l-119-119L345 137z"
            ></path></svg></button></div
      ><div class="modal_body modal_body__txt"
        ><ul
          ><li>
            이용권은 약정 기간 동안 월납 또는 일시납으로 금액을 지불하고 리딩수학의 서비스를 자유롭게 이용하는
            상품입니다. </li
          ><li>사용 중인 이용권이 있는 경우, 동일 계정에서 이용권 추가 구매 시 사용기간이 연장됩니다.</li
          ><li>이용권은 일시 정지가 불가합니다.</li
          ><li>이용권의 환불 시 이용한 일수만큼 정가 기준으로 일할 계산하여 제외한 금액이 환불됩니다.</li
          ><li>환불 후에는 리딩수학의 모든 서비스를 즉시 이용할 수 없습니다.</li
          ><li>상담 관련 문의사항은 고객센터로 문의 바랍니다. (1800-5039) </li></ul
        ></div
      ></div
    >
  </DefaultLayout>
</template>

<script setup lang="ts">
  import DefaultLayout from '@/html/layouts/DefaultLayout.vue';
</script>

<style scoped>
  .modal {
    position: initial;
    transform: initial;
    margin-bottom: 1rem;
  }
</style>
