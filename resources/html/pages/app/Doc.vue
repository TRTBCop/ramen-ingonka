<!-- temp: only for doc -->
<template>
  <AppLearningLayout>
    <div class="wrap_doc">
      <aside class="aside">
        <a href="#layout">layout</a>
        <a href="#header">header</a>
        <a href="#color">color</a>
        <a href="#typography">typography</a>
        <a href="#form">form</a>
        <a href="#button">button</a>
        <hr />
        <a href="#training_spl">훈련: 끊어읽기</a>
        <a href="#blank">훈련: 빈칸 맞히기</a>
        <a href="#orders">훈련: 순서 맞추기</a>
        <a href="#training_answers">훈련: 정답 고르기</a>
        <a href="#tab">tab</a>
        <a href="#keypad">keypad 키패드</a>
        <a href="#answer">정답영역</a>
        <hr />
        <a href="#login">로그인</a>
      </aside>

      <div class="container">
        <div class="box">
          <h2 id="layout">Layout</h2>
          <h3>공통 : 컨터이너 padding: 0 1rem</h3>
          <p>
            <code class="code">.container x</code> <br />
            <span>컨테이너가 없는 경우 따로 컨테이너를 쓰지 않음</span>
            <span> : 학습 메인, 자유모드, 기본모드 메인 등</span>
          </p>
          <p>
            <code class="code">.container--lg</code><br />
            <span>full width / max-width : 1200px</span>
            <span> : 마이페이지, 이용권 구매, 학습 기록, 게시판</span>
          </p>
          <p>
            <code class="code">.container--md</code><br />
            <span>full width / max-width : 960px</span>
            <span> : 딤깔리는 레이어</span>
          </p>
          <p>
            <code class="code">.container--sm</code><br />
            <span>max-width: 520px</span>
            <span> : 회원가입, id 및 pw찾기, 진단평가 결과 화면</span>
          </p>
        </div>

        <div class="box">
          <h2 id="header">Header <code class="code">.navbar</code></h2>
          <header class="navbar">
            <div class="col">
              <a href="javascript:;" class="logo"><img src="../../../assets/img/math/ico_logo.svg" alt="" /></a>
              <ol class="breadcrumb">
                <li><strong>김학생</strong></li>
              </ol>
            </div>

            <div class="btns">
              <button>
                <font-awesome-icon icon="fa-regular fa-light-emergency-on" />
              </button>
              <button>
                <font-awesome-icon icon="fa-regular fa-bullhorn" />
              </button>
              <button>
                <font-awesome-icon icon="fa-regular fa-bars" />
              </button>
            </div>
          </header>
          <header class="navbar">
            <div class="col">
              <a href="javascript:;" class="logo"><img src="../../../assets/img/math/ico_logo.svg" alt="" /></a>
              <ul class="breadcrumb">
                <li><strong>초등3-1</strong></li>
                <li
                  ><span>소단원 타이틀</span>
                  <font-awesome-icon class="ico_chevron" icon="fa-regular fa-chevron-right" />
                </li>
                <li
                  ><span>개념 훈련</span>
                  <font-awesome-icon class="ico_chevron" icon="fa-regular fa-chevron-right" />
                </li>
                <li>
                  <ol class="breadcrumb--sub">
                    <li class="done"> <span>단계 이름</span> </li>
                    <li class="on"> <span>단계 이름</span> </li>
                    <li class=""> <span>단계 이름</span> </li>
                    <li class=""> <span>단계 이름</span> </li>
                    <li class=""> <span>단계 이름</span></li>
                  </ol>
                </li>
              </ul>
            </div>

            <div class="col">
              <div class="btns">
                <button>
                  <font-awesome-icon icon="fa-regular fa-light-emergency-on" />
                </button>
                <button>
                  <font-awesome-icon icon="fa-regular fa-right-from-bracket" />
                </button>
              </div>
            </div>
          </header>
          <header class="navbar">
            <div class="col">
              <a href="javascript:;" class="logo"><img src="../../../assets/img/math/ico_logo.svg" alt="" /></a>
              <ol class="breadcrumb">
                <li><strong>초등3-1</strong></li>
                <li
                  ><span>소단원 타이틀</span>
                  <font-awesome-icon class="ico_chevron" icon="fa-regular fa-chevron-right" />
                </li>
                <li
                  ><span>유형 훈련</span>
                  <font-awesome-icon class="ico_chevron" icon="fa-regular fa-chevron-right" />
                </li>
                <li><span>1차 유사문제</span></li>
              </ol>
            </div>

            <div class="col">
              <!-- [D] 시간지나면 .timeover-->
              <div class="timer">
                <span class="time timeover">66:30</span>
                <span class="time timeover time-pause">66:30</span>
                <span class="time">36:30</span>
                <span class="time time-pause">36:30</span>
              </div>
              <div class="btns">
                <button>
                  <font-awesome-icon icon="fa-regular fa-light-emergency-on" />
                </button>
                <button>
                  <font-awesome-icon icon="fa-regular fa-right-from-bracket" />
                </button>
              </div>
            </div>
          </header>
          <header class="navbar">
            <div class="col">
              <a href="javascript:;" class="logo"><img src="../../../assets/img/math/ico_logo.svg" alt="" /></a>
              <ol class="breadcrumb">
                <li><strong>초등3-1</strong></li>
                <li
                  ><span>소단원 타이틀</span>
                  <font-awesome-icon class="ico_chevron" icon="fa-regular fa-chevron-right" />
                </li>
                <li
                  ><span>유형 훈련</span>
                  <font-awesome-icon class="ico_chevron" icon="fa-regular fa-chevron-right" />
                </li>
                <li><span>1차 유사문제</span></li>
              </ol>
            </div>

            <div class="col">
              <!-- [D] 시간지나면 .timeover-->
              <div class="timer">
                <span class="badge--lightgray"
                  ><i class="fa-regular fa-bell"></i>60분 안에 문제를 모두 풀어보세요!</span
                >
              </div>
              <div class="btns">
                <button>
                  <font-awesome-icon icon="fa-regular fa-light-emergency-on" />
                </button>
                <button>
                  <font-awesome-icon icon="fa-regular fa-right-from-bracket" />
                </button>
              </div>
            </div>
          </header>
        </div>

        <div class="box">
          <h2 id="color">color</h2>
          <div>
            <p class="is--primary">is--primary</p>
            <p class="is--success">is--success</p>
            <p class="is--danger">is--danger</p>
            <p class="is--warning">is--warning</p>
            <p class="is--gray">is--gray</p>
            <p class="is--placeholder">is--placeholder</p>
          </div>
          <div>
            <p class="color bg--primary">bg--primary</p>
            <p class="color bg--success">bg--success</p>
            <p class="color bg--danger">bg--danger</p>
            <p class="color bg--warning">bg--warning</p>
            <p class="color bg--gray">bg--gray</p>
          </div>
        </div>

        <div class="box">
          <h2 id="typography">typography</h2>
          <div>
            <div class="is--size1">.is--size1</div>
            <div class="is--size2">.is--size2</div>
            <div class="is--size3">.is--size3</div>
            <div class="is--size4">.is--size4</div>
            <div class="is--size5">.is--size5</div>
            <div class="is--size6">.is--size6</div>

            <p class="is--size3 txt--left">.txt--left</p>
            <p class="is--size3 txt--center">.txt--center</p>
            <p class="is--size3 txt--right">.txt--right</p>
          </div>
        </div>

        <div class="box">
          <h2 id="form">form</h2>

          <div class="input">
            <input type="text" class="input__text" placeholder=".input > .input__text" />
          </div>

          <div class="input">
            <input type="text" class="input__text" placeholder=".input > .input__text:focus" autofocus />
          </div>

          <div class="input error">
            <input type="text" class="input__text" placeholder=".input.error > .input__text" />
          </div>

          <div class="input">
            <input type="text" class="input__text" placeholder=".input > .input__text:disabled" disabled />
          </div>

          <div class="input">
            <font-awesome-icon icon="fa-regular fa-lock" />
            <input type="password" class="input__text" placeholder="비밀번호" value="password" />
            <button class="btn_pw">
              <font-awesome-icon icon="fa-regular fa-eye" />
            </button>
          </div>

          <div class="input">
            <font-awesome-icon icon="fa-regular fa-lock" />
            <input type="text" class="input__text" placeholder="비밀번호" value="password" />
            <button class="btn_pw">
              <font-awesome-icon icon="fa-regular fa-eye-slash" />
            </button>
          </div>
        </div>

        <div class="box">
          <h2 id="badge">button <code class="code">.badge--colorname</code></h2>
          <div class="mt-1">
            <span class="badge--gray">.badge--gray</span>
            <span class="badge--gray"> <font-awesome-icon icon="fa-regular fa-bell" />.badge--gray</span>
            <span class="badge--lightgray">.badge--lightgray</span>
            <span class="badge--lightgray"><font-awesome-icon icon="fa-regular fa-bell" />.badge--lightgray</span>
            <span class="badge--success--alpha">.badge--success--alpha</span>
            <span class="badge--success--alpha"
              ><font-awesome-icon icon="fa-regular fa-bell" />.badge--success--alpha</span
            >
            <span class="badge--primary--alpha">.badge--primary--alpha</span>
            <span class="badge--primary--alpha"
              ><font-awesome-icon icon="fa-regular fa-bell" />.badge--primary--alpha</span
            >
            <span class="badge--purple--alpha">.badge--purple--alpha</span>
            <span class="badge--purple--alpha"
              ><font-awesome-icon icon="fa-regular fa-bell" />.badge--purple--alpha</span
            >
            <span class="badge--brand--alpha">.badge--brand--alpha</span>
            <span class="badge--brand--alpha"><font-awesome-icon icon="fa-regular fa-bell" />.badge--brand--alpha</span>
          </div>
        </div>

        <div class="box">
          <h2 id="button">button <code class="code">.btns > button</code></h2>
          <div class="btns">
            <button class="btn--primary">.btn--primary</button>
            <button class="btn--gray">.btn--gray</button>
            <button class="btn--brand">.btn--brand</button>
            <button class="btn--white">.btn--white</button>
            <button class="btn--sub">.btn--sub</button>
          </div>
          <h2>button loading</h2>
          <div class="btns">
            <button class="btn--primary" :disabled="isLoading" @click="fetchData">
              <span>
                <i v-if="isLoading" class="fa-regular fa-spinner fa-spin"></i
                >{{ isLoading ? '로딩 중...' : '로딩 버튼' }}</span
              >
            </button>
          </div>
          <h2>button loading</h2>
          <div class="btns">
            <button class="btn--primary">정답 </button>
            <button class="btn--gray">오답 </button>
          </div>

          <h2>button group <code class="code">.panel__btns > button</code></h2>
          <div class="panel__btns">
            <button class="btn--primary">.btn--primary</button>
            <button class="btn--gray">.btn--gray</button>
          </div>

          <h2>button group <code class="code">.panel__btns--row > button</code></h2>
          <div class="panel__btns--row">
            <button class="btn--primary">.btn--primarddy</button>
            <button class="btn--gray">.btn--gray</button>
          </div>
        </div>

        <div class="box">
          <h2 id="training_spl">끊어읽기</h2>
          <h4>.articles > span.spl : 읽기 전</h4>
          <h4>.articles > span.spl.show : 읽은 후</h4>
          <h4>.articles > span.spl.current : 현재 시점</h4>
          <div class="articles">
            <p>
              <span class="spl show">시간의</span>
              <span class="spl show">덧셈과 뺄셈은</span>
              <span class="spl show">어떻게</span>
              <span class="spl show current">계산할까요?</span>
              <span class="spl show current">끊어읽기 어절이면서 <em class="blank__focus">빈칸</em>인 경우</span>
              <br />
              <span class="spl">&nbsp;일상생활에서</span>
              <span class="spl">시각과 시간을</span>
              <span class="spl">정확히</span>
            </p>
          </div>
        </div>

        <div class="box">
          <h2>이미지 매칭 버튼</h2>
          <h4>.articles > span.spl.link : 눌린 후 매칭버튼</h4>
          <h4>.articles > span.spl.show : 클릭한 매칭버튼</h4>
          <h4>.articles > span.spl.current : 현재 시점 매칭버튼</h4>
          <div class="articles">
            <p>
              <span class="spl show">시간의</span>
              <span class="spl show">덧셈과 뺄셈은</span>
              <span class="spl show link">눌린 후 매칭버튼</span>
              <span class="spl show link__pressed">클릭한 매칭버튼</span>
              <span class="spl show link current">&nbsp;현재 시점 매칭버튼</span>
            </p>
          </div>
        </div>

        <div class="box">
          <h2 id="blank">빈칸 맞히기</h2>
          <div class="articles">
            <span class="spl show">
              <em class="blank">강아지</em>
              <code class="code">span.spl > <strong>.blank</strong></code>
            </span>

            <hr />

            <span class="spl show">
              <em class="blank blank__correct">강아지</em>
              <code class="code">span.spl > <strong>.blank.blank__correct</strong></code>
            </span>

            <hr />

            <span class="spl show">
              <em class="blank blank__wrong">강아지</em>
              <code class="code">span.spl > <strong>.blank.blank__wrong</strong></code>
            </span>

            <hr />

            <span class="spl show">
              <em class="blank blank__wrong__check">강아지</em>
              <code class="code">span.spl > <strong>.blank.blank__wrong__check</strong></code>
            </span>

            <hr />

            <span class="spl show">
              <em class="blank blank__focus"></em>
              <em class="blank blank__focus">텍스트</em>
              <em class="blank blank__focus"
                ><img
                  src="https://dailykor.com/data/math/text_img/5_1_0_1606466005.png"
                  style="
                    width: 168px;
                    display: inline-block;
                    vertical-align: bottom;
                    margin: 0px 5px;
                    text-align: center;
                    max-width: calc(100% - 10px);
                    cursor: pointer;
                    padding: 0px;
                    position: relative;
                    border: 0px;
                    font: inherit;
                  "
                  fr-original-style="width: 168px; display: inline-block; vertical-align: bottom; margin-right: 5px; margin-left: 5px; text-align: center; max-width: calc(100% - 10px);"
                  fr-original-class="fr-draggable"
              /></em>

              <em class="blank blank__focus">
                <svg
                  xmlns="http://www.w3.org/2000/svg"
                  xmlns:wrs="http://www.wiris.com/xml/mathml-extension"
                  height="43"
                  width="149"
                  wrs:baseline="27"
                >
                  <!--MathML: <math xmlns="http://www.w3.org/1998/Math/MathML"><mfrac><mrow><mi>cos</mi><mn>60</mn><mo>&#xB0;</mo></mrow><mrow><mi>cos</mi><mn>45</mn><mo>&#xB0;</mo><mo>&#xD7;</mo><mi>tan</mi><mn>60</mn><mo>&#xB0;</mo></mrow></mfrac><mo>=</mo><mi>c</mi></math>-->
                  <defs></defs>
                  <line
                    stroke="#000000"
                    stroke-linecap="square"
                    stroke-width="1"
                    x1="2.5"
                    x2="119.5"
                    y1="21.5"
                    y2="21.5"
                  ></line>
                  <text font-family="Arial" font-size="16" text-anchor="middle" x="48.5" y="16">cos</text>
                  <text font-family="Arial" font-size="16" text-anchor="middle" x="70.5" y="16">60</text>
                  <text
                    font-family="math13252874406a754f022374db7c6"
                    font-size="16"
                    text-anchor="middle"
                    x="83.5"
                    y="16"
                  >
                    °
                  </text>
                  <text font-family="Arial" font-size="16" text-anchor="middle" x="16.5" y="39">cos</text>
                  <text font-family="Arial" font-size="16" text-anchor="middle" x="38.5" y="39">45</text>
                  <text
                    font-family="math13252874406a754f022374db7c6"
                    font-size="16"
                    text-anchor="middle"
                    x="51.5"
                    y="39"
                  >
                    °
                  </text>
                  <text
                    font-family="math13252874406a754f022374db7c6"
                    font-size="16"
                    text-anchor="middle"
                    x="62.5"
                    y="39"
                  >
                    ×
                  </text>
                  <text font-family="Arial" font-size="16" text-anchor="middle" x="81.5" y="39">tan</text>
                  <text font-family="Arial" font-size="16" text-anchor="middle" x="101.5" y="39">60</text>
                  <text
                    font-family="math13252874406a754f022374db7c6"
                    font-size="16"
                    text-anchor="middle"
                    x="114.5"
                    y="39"
                  >
                    °
                  </text>
                  <text
                    font-family="math13252874406a754f022374db7c6"
                    font-size="16"
                    text-anchor="middle"
                    x="130.5"
                    y="27"
                  >
                    =
                  </text>
                  <text font-family="Arial" font-size="16" font-style="italic" text-anchor="middle" x="143.5" y="27">
                    c
                  </text>
                </svg>
              </em>
              <code class="code">span.spl > <strong>blank.blank__focus</strong></code>
            </span>

            <hr />

            <span class="spl show">
              <em class="blank blank__on">3</em>
              <em class="blank blank__on">강아지</em>
              <em class="blank blank__on">use to be young</em>

              <em class="blank blank__on"
                ><img
                  src="https://dailykor.com/data/math/text_img/5_1_0_1606466005.png"
                  style="
                    width: 168px;
                    display: inline-block;
                    vertical-align: bottom;
                    margin: 0px 5px;
                    text-align: center;
                    max-width: calc(100% - 10px);
                    cursor: pointer;
                    padding: 0px;
                    position: relative;
                    border: 0px;
                    font: inherit;
                  "
                  fr-original-style="width: 168px; display: inline-block; vertical-align: bottom; margin-right: 5px; margin-left: 5px; text-align: center; max-width: calc(100% - 10px);"
                  fr-original-class="fr-draggable"
              /></em>

              <em class="blank blank__on">
                <svg
                  xmlns="http://www.w3.org/2000/svg"
                  xmlns:wrs="http://www.wiris.com/xml/mathml-extension"
                  height="43"
                  width="149"
                  wrs:baseline="27"
                >
                  <!--MathML: <math xmlns="http://www.w3.org/1998/Math/MathML"><mfrac><mrow><mi>cos</mi><mn>60</mn><mo>&#xB0;</mo></mrow><mrow><mi>cos</mi><mn>45</mn><mo>&#xB0;</mo><mo>&#xD7;</mo><mi>tan</mi><mn>60</mn><mo>&#xB0;</mo></mrow></mfrac><mo>=</mo><mi>c</mi></math>-->
                  <defs></defs>
                  <line
                    stroke="#000000"
                    stroke-linecap="square"
                    stroke-width="1"
                    x1="2.5"
                    x2="119.5"
                    y1="21.5"
                    y2="21.5"
                  ></line>
                  <text font-family="Arial" font-size="16" text-anchor="middle" x="48.5" y="16">cos</text>
                  <text font-family="Arial" font-size="16" text-anchor="middle" x="70.5" y="16">60</text>
                  <text
                    font-family="math13252874406a754f022374db7c6"
                    font-size="16"
                    text-anchor="middle"
                    x="83.5"
                    y="16"
                  >
                    °
                  </text>
                  <text font-family="Arial" font-size="16" text-anchor="middle" x="16.5" y="39">cos</text>
                  <text font-family="Arial" font-size="16" text-anchor="middle" x="38.5" y="39">45</text>
                  <text
                    font-family="math13252874406a754f022374db7c6"
                    font-size="16"
                    text-anchor="middle"
                    x="51.5"
                    y="39"
                  >
                    °
                  </text>
                  <text
                    font-family="math13252874406a754f022374db7c6"
                    font-size="16"
                    text-anchor="middle"
                    x="62.5"
                    y="39"
                  >
                    ×
                  </text>
                  <text font-family="Arial" font-size="16" text-anchor="middle" x="81.5" y="39">tan</text>
                  <text font-family="Arial" font-size="16" text-anchor="middle" x="101.5" y="39">60</text>
                  <text
                    font-family="math13252874406a754f022374db7c6"
                    font-size="16"
                    text-anchor="middle"
                    x="114.5"
                    y="39"
                  >
                    °
                  </text>
                  <text
                    font-family="math13252874406a754f022374db7c6"
                    font-size="16"
                    text-anchor="middle"
                    x="130.5"
                    y="27"
                  >
                    =
                  </text>
                  <text font-family="Arial" font-size="16" font-style="italic" text-anchor="middle" x="143.5" y="27">
                    c
                  </text>
                </svg>
              </em>
              <code class="code">span.spl > <strong>blank.blank__on</strong></code>
            </span>
          </div>
        </div>

        <div class="box">
          <h2 id="orders">순서 맞추기</h2>

          <div class="articles">
            <h3>끊어읽기 영역</h3>
            <span class="spl show">
              <em class="order__blank"></em>
              <code class="code">span.spl > <strong>.order__blank</strong></code>
            </span>

            <hr />

            <span class="spl show">
              <em class="order__blank"></em>
              <em class="order__blank order__blank__focus">강아지</em>
              <em class="order__blank">
                <img
                  src="https://dailykor.com/data/math/text_img/5_1_0_1606466005.png"
                  style="
                    width: 168px;
                    display: inline-block;
                    vertical-align: bottom;
                    margin: 0px 5px;
                    text-align: center;
                    max-width: calc(100% - 10px);
                    cursor: pointer;
                    padding: 0px;
                    position: relative;
                    border: 0px;
                    font: inherit;
                  "
                  fr-original-style="width: 168px; display: inline-block; vertical-align: bottom; margin-right: 5px; margin-left: 5px; text-align: center; max-width: calc(100% - 10px);"
                  fr-original-class="fr-draggable"
                />
              </em>

              <em class="order__blank">
                <svg
                  xmlns="http://www.w3.org/2000/svg"
                  xmlns:wrs="http://www.wiris.com/xml/mathml-extension"
                  height="43"
                  width="149"
                  wrs:baseline="27"
                >
                  <!--MathML: <math xmlns="http://www.w3.org/1998/Math/MathML"><mfrac><mrow><mi>cos</mi><mn>60</mn><mo>&#xB0;</mo></mrow><mrow><mi>cos</mi><mn>45</mn><mo>&#xB0;</mo><mo>&#xD7;</mo><mi>tan</mi><mn>60</mn><mo>&#xB0;</mo></mrow></mfrac><mo>=</mo><mi>c</mi></math>-->
                  <defs></defs>
                  <line
                    stroke="#000000"
                    stroke-linecap="square"
                    stroke-width="1"
                    x1="2.5"
                    x2="119.5"
                    y1="21.5"
                    y2="21.5"
                  ></line>
                  <text font-family="Arial" font-size="16" text-anchor="middle" x="48.5" y="16">cos</text>
                  <text font-family="Arial" font-size="16" text-anchor="middle" x="70.5" y="16">60</text>
                  <text
                    font-family="math13252874406a754f022374db7c6"
                    font-size="16"
                    text-anchor="middle"
                    x="83.5"
                    y="16"
                  >
                    °
                  </text>
                  <text font-family="Arial" font-size="16" text-anchor="middle" x="16.5" y="39">cos</text>
                  <text font-family="Arial" font-size="16" text-anchor="middle" x="38.5" y="39">45</text>
                  <text
                    font-family="math13252874406a754f022374db7c6"
                    font-size="16"
                    text-anchor="middle"
                    x="51.5"
                    y="39"
                  >
                    °
                  </text>
                  <text
                    font-family="math13252874406a754f022374db7c6"
                    font-size="16"
                    text-anchor="middle"
                    x="62.5"
                    y="39"
                  >
                    ×
                  </text>
                  <text font-family="Arial" font-size="16" text-anchor="middle" x="81.5" y="39">tan</text>
                  <text font-family="Arial" font-size="16" text-anchor="middle" x="101.5" y="39">60</text>
                  <text
                    font-family="math13252874406a754f022374db7c6"
                    font-size="16"
                    text-anchor="middle"
                    x="114.5"
                    y="39"
                  >
                    °
                  </text>
                  <text
                    font-family="math13252874406a754f022374db7c6"
                    font-size="16"
                    text-anchor="middle"
                    x="130.5"
                    y="27"
                  >
                    =
                  </text>
                  <text font-family="Arial" font-size="16" font-style="italic" text-anchor="middle" x="143.5" y="27">
                    c
                  </text>
                </svg>
              </em>
              <code class="code">span.spl > <strong>.order__blank.order__blank__focus</strong></code>
            </span>

            <hr />

            <span class="spl show">
              <em class="order__blank order__blank__on">3</em>
              <em class="order__blank order__blank__on">강아지</em>
              <em class="order__blank order__blank__on">use to be young</em>

              일반 텍스트

              <em class="order__blank order__blank__on"
                ><img
                  src="https://dailykor.com/data/math/text_img/5_1_0_1606466005.png"
                  style="
                    width: 168px;
                    display: inline-block;
                    vertical-align: bottom;
                    margin: 0px 5px;
                    text-align: center;
                    max-width: calc(100% - 10px);
                    cursor: pointer;
                    padding: 0px;
                    position: relative;
                    border: 0px;
                    font: inherit;
                  "
                  fr-original-style="width: 168px; display: inline-block; vertical-align: bottom; margin-right: 5px; margin-left: 5px; text-align: center; max-width: calc(100% - 10px);"
                  fr-original-class="fr-draggable"
              /></em>

              <em class="order__blank order__blank__on order__blank__result">
                <svg
                  xmlns="http://www.w3.org/2000/svg"
                  xmlns:wrs="http://www.wiris.com/xml/mathml-extension"
                  height="20"
                  width="43"
                  wrs:baseline="16"
                >
                  <!--MathML: <math xmlns="http://www.w3.org/1998/Math/MathML"><mo>=</mo><mn>3</mn><mo>&#xF7;</mo></math>-->
                  <defs></defs>
                  <text
                    font-family="math168aae17e4d513c259172de8723"
                    font-size="16"
                    text-anchor="middle"
                    x="8.5"
                    y="16"
                  >
                    =
                  </text>
                  <text font-family="Arial" font-size="16" text-anchor="middle" x="21.5" y="16">3</text>
                  <text
                    font-family="math168aae17e4d513c259172de8723"
                    font-size="16"
                    text-anchor="middle"
                    x="34.5"
                    y="16"
                  >
                    ÷
                  </text></svg
                ><svg
                  xmlns="http://www.w3.org/2000/svg"
                  xmlns:wrs="http://www.wiris.com/xml/mathml-extension"
                  height="45"
                  width="34"
                  wrs:baseline="30"
                >
                  <!--MathML: <math xmlns="http://www.w3.org/1998/Math/MathML"><mfrac><msqrt><mn>3</mn></msqrt><mn>3</mn></mfrac></math>-->
                  <defs></defs>
                  <line
                    stroke="#000000"
                    stroke-linecap="square"
                    stroke-width="1"
                    x1="2.5"
                    x2="30.5"
                    y1="24.5"
                    y2="24.5"
                  ></line>
                  <polyline
                    fill="none"
                    points="12,-16 11,-16 5,0 2,-6"
                    stroke="#000000"
                    stroke-linecap="square"
                    stroke-width="1"
                    transform="translate(4.5,20.5)"
                  ></polyline>
                  <polyline
                    fill="none"
                    points="5,0 2,-6 0,-5"
                    stroke="#000000"
                    stroke-linecap="square"
                    stroke-width="1"
                    transform="translate(4.5,20.5)"
                  ></polyline>
                  <line
                    stroke="#000000"
                    stroke-linecap="square"
                    stroke-width="1"
                    x1="16.5"
                    x2="28.5"
                    y1="4.5"
                    y2="4.5"
                  ></line>
                  <text font-family="Arial" font-size="16" text-anchor="middle" x="22.5" y="19">3</text>
                  <text font-family="Arial" font-size="16" text-anchor="middle" x="16.5" y="41">3</text></svg
                ><svg
                  xmlns="http://www.w3.org/2000/svg"
                  xmlns:wrs="http://www.wiris.com/xml/mathml-extension"
                  height="45"
                  width="71"
                  wrs:baseline="30"
                >
                  <!--MathML: <math xmlns="http://www.w3.org/1998/Math/MathML"><mo>-</mo><mn>2</mn><mo>&#xD7;</mo><mfrac><msqrt><mn>3</mn></msqrt><mn>2</mn></mfrac></math>-->
                  <defs></defs>
                  <text
                    font-family="math1298aafb3379f56b0a3af85c9c2"
                    font-size="16"
                    text-anchor="middle"
                    x="6.5"
                    y="30"
                  >
                    −
                  </text>
                  <text font-family="Arial" font-size="16" text-anchor="middle" x="17.5" y="30">2</text>
                  <text
                    font-family="math1298aafb3379f56b0a3af85c9c2"
                    font-size="16"
                    text-anchor="middle"
                    x="29.5"
                    y="30"
                  >
                    ×
                  </text>
                  <line
                    stroke="#000000"
                    stroke-linecap="square"
                    stroke-width="1"
                    x1="39.5"
                    x2="67.5"
                    y1="24.5"
                    y2="24.5"
                  ></line>
                  <polyline
                    fill="none"
                    points="12,-16 11,-16 5,0 2,-6"
                    stroke="#000000"
                    stroke-linecap="square"
                    stroke-width="1"
                    transform="translate(41.5,20.5)"
                  ></polyline>
                  <polyline
                    fill="none"
                    points="5,0 2,-6 0,-5"
                    stroke="#000000"
                    stroke-linecap="square"
                    stroke-width="1"
                    transform="translate(41.5,20.5)"
                  ></polyline>
                  <line
                    stroke="#000000"
                    stroke-linecap="square"
                    stroke-width="1"
                    x1="53.5"
                    x2="65.5"
                    y1="4.5"
                    y2="4.5"
                  ></line>
                  <text font-family="Arial" font-size="16" text-anchor="middle" x="59.5" y="19">3</text>
                  <text font-family="Arial" font-size="16" text-anchor="middle" x="53.5" y="41">2</text>
                </svg>
              </em>

              <em class="order__blank order__blank__on order__blank__result">
                <svg
                  xmlns="http://www.w3.org/2000/svg"
                  xmlns:wrs="http://www.wiris.com/xml/mathml-extension"
                  height="43"
                  width="149"
                  wrs:baseline="27"
                >
                  <!--MathML: <math xmlns="http://www.w3.org/1998/Math/MathML"><mfrac><mrow><mi>cos</mi><mn>60</mn><mo>&#xB0;</mo></mrow><mrow><mi>cos</mi><mn>45</mn><mo>&#xB0;</mo><mo>&#xD7;</mo><mi>tan</mi><mn>60</mn><mo>&#xB0;</mo></mrow></mfrac><mo>=</mo><mi>c</mi></math>-->
                  <defs></defs>
                  <line
                    stroke="#000000"
                    stroke-linecap="square"
                    stroke-width="1"
                    x1="2.5"
                    x2="119.5"
                    y1="21.5"
                    y2="21.5"
                  ></line>
                  <text font-family="Arial" font-size="16" text-anchor="middle" x="48.5" y="16">cos</text>
                  <text font-family="Arial" font-size="16" text-anchor="middle" x="70.5" y="16">60</text>
                  <text
                    font-family="math13252874406a754f022374db7c6"
                    font-size="16"
                    text-anchor="middle"
                    x="83.5"
                    y="16"
                  >
                    °
                  </text>
                  <text font-family="Arial" font-size="16" text-anchor="middle" x="16.5" y="39">cos</text>
                  <text font-family="Arial" font-size="16" text-anchor="middle" x="38.5" y="39">45</text>
                  <text
                    font-family="math13252874406a754f022374db7c6"
                    font-size="16"
                    text-anchor="middle"
                    x="51.5"
                    y="39"
                  >
                    °
                  </text>
                  <text
                    font-family="math13252874406a754f022374db7c6"
                    font-size="16"
                    text-anchor="middle"
                    x="62.5"
                    y="39"
                  >
                    ×
                  </text>
                  <text font-family="Arial" font-size="16" text-anchor="middle" x="81.5" y="39">tan</text>
                  <text font-family="Arial" font-size="16" text-anchor="middle" x="101.5" y="39">60</text>
                  <text
                    font-family="math13252874406a754f022374db7c6"
                    font-size="16"
                    text-anchor="middle"
                    x="114.5"
                    y="39"
                  >
                    °
                  </text>
                  <text
                    font-family="math13252874406a754f022374db7c6"
                    font-size="16"
                    text-anchor="middle"
                    x="130.5"
                    y="27"
                  >
                    =
                  </text>
                  <text font-family="Arial" font-size="16" font-style="italic" text-anchor="middle" x="143.5" y="27">
                    c
                  </text>
                </svg>
              </em>

              <code class="code">span.spl > <strong>.order__blank.order__blank__on</strong></code>
            </span>

            <span class="spl show">
              <em class="order__blank order__blank__wrong">3</em>
              <em class="order__blank order__blank__wrong">강아지</em>
              <em class="order__blank order__blank__wrong">use to be young</em>
            </span>
            <code class="code">span.spl > <strong>.order__blank .order__blank__wrong</strong></code>

            <span class="spl show">
              <em class="order__blank order__blank__correct">3</em>
              <em class="order__blank order__blank__correct">강아지</em>
              <em class="order__blank order__blank__correct">use to be young</em>
            </span>
            <code class="code">span.spl > <strong>.order__blank .order__blank__correct</strong></code>

            <span class="spl show">
              <em class="order__blank order__blank__wrong__check">3</em>
              <em class="order__blank order__blank__wrong__check">강아지</em>
              <em class="order__blank order__blank__wrong__check">use to be young</em>
            </span>
            <code class="code">span.spl > <strong>.order__blank .order__blank__wrong__check</strong></code>
          </div>

          <h4>.order__btns > button : 기본(문제풀 때)</h4>
          <h4>.order__btns > button:disabled : 틀림(문제풀 때)</h4>
          <h4>.order__btns > order__btn__correct : 맞춤(오답체크용)</h4>
          <h4>.order__btns > order__btn__wrong : 틀렸음(오답체크용)</h4>
          <h4>.order__btns > order__btn__wrong__check : 틀렸지만 답을 확인했음(오답체크용)</h4>

          <div class="order__btns">
            <button disabled>모두 같으므로</button>
            <button disabled>삼각형 ㄱㄴㅇ, 삼각형 ㅇㄴㄷ, 삼각형 ㄱㅇㄷ은</button>
            <button>모두 같으므로</button>
            <button>삼각형 ㄱㄴㅇ, 삼각형 ㅇㄴㄷ, 삼각형 ㄱㅇㄷ은</button>
            <button class="order__btn__correct">삼각형 ㄱㄴㅇ, 삼각형 ㅇㄴㄷ, 삼각형 ㄱㅇㄷ은</button>
            <button class="order__btn__correct">한 원에서</button>
            <button class="order__btn__wrong__check">한 원에서</button>
            <button class="order__btn__wrong__check">한 원에서</button>
            <button class="order__btn__wrong">원의 반지름의 길이는</button>
            <button class="order__btn__wrong">모두 이등변삼각형입니다.</button>
          </div>
        </div>

        <div class="box">
          <h2 id="training_answers">정답 고르기 (헷갈리면 말해주세용)</h2>
          <h3>공통</h3>
          <h4>객관식인경우 / .answers > .answers--choice</h4>
          <h4>주관식인경우 / .answers.answers--input</h4>
          <h4>한번 감싸져야 할 경우 /.answers > .answers--bubble.show</h4>
          <h4>.answer__correct : 정답</h4>
          <h4>.answer__wrong : 오답</h4>
          <h4>.answer__check : 다중선택</h4>
          <h3>왼쪽에 있는 경우</h3>
          <h4>.answers > .answers--bubble.show</h4>

          <h3>오른쪽에 있는 경우</h3>
          <h4>.answers.answers--input</h4>
          <h4>button > i.answer__number : 객관식인경우 넘버링(css로 적용)</h4>
          <h4>div > i.answer__sign : 주관식인경우 넘버링(개발에서 바로 적용)</h4>
          <h4></h4>
          <div class="mt-1">
            <div class="answers--choice">
              <button>
                <i class="answer__number"></i>
                <span>기본</span>
              </button>
              <button class="answer__correct">
                <i class="answer__number"></i>
                <span>안녕하세요 저는 정답입니다</span>
              </button>
              <button class="answer__wrong">
                <i class="answer__number"></i>
                <span>오답</span>
              </button>
              <button class="answer__check">
                <i class="answer__number"></i>
                <span>다중선택: 여러개를 선택해야하는 경우</span>
              </button>
            </div>
          </div>

          <div class="mt-1">
            <div class="answers--choice">
              <button>
                <i class="answer__number"></i>
                <span><img src="../../../assets/img/math/testimg.png" alt="" /></span>
              </button>
              <button class="answer__correct">
                <i class="answer__number"></i>
                <span><img src="../../../assets/img/math/testimg.png" alt="" /></span>
              </button>
              <button class="answer__wrong">
                <i class="answer__number"></i>
                <span><img src="../../../assets/img/math/testimg.png" alt="" /></span>
              </button>
              <button class="answer__check">
                <i class="answer__number"></i>
                <span><img src="../../../assets/img/math/testimg.png" alt="" /></span>
              </button>
              <button class="answer__check">
                <i class="answer__number"></i>
                <span><img src="../../../assets/img/math/testimg.png" alt="" /></span>
              </button>
            </div>
          </div>

          <div class="">
            <div class="answers--bubble show" style="position: initial">
              <div class="answers--choice">
                <button class="answer__correct"><i class="answer__number"></i><span>정답</span></button>
                <button class="answer__wrong"><i class="answer__number"></i><span>오답</span></button>
                <button class="answer__check"><i class="answer__number"></i><span>다중선택</span></button>
                <button><i class="answer__number"></i><span>기본</span></button>
              </div>
            </div>
          </div>

          <div class="answers answers--input">
            <div class="answer__active">
              <i class="answer__sign">㉠</i>
              <span class="">1</span>
            </div>
            <div class="answer__wrong">
              <i class="answer__sign">㉡</i>
              <span class="">1</span>
            </div>
            <div class="answer__correct">
              <i class="answer__sign">㉢</i>
              <span class="">1</span>
            </div>
            <div class="answer__wrong__check">
              <i class="answer__sign">㉣</i>
              <span class="">1</span>
            </div>
            <div class="">
              <i class="answer__sign">㉤</i>
              <span class="">1</span>
            </div>
          </div>

          <div class="frame__body">
            <div class="toggle_area">
              <div class="toggle__head">
                <h3 class="">1번</h3>
                <button class="btn_dropdown"></button>
              </div>
              <div class="answers--input">
                <div class="answer__active">
                  <i class="answer__sign">(1-1)</i>
                  <span class="">1</span>
                </div>
                <div class="answers--bubble show">
                  <div class="answers--choice"
                    ><button><i class="answer__number"></i><span>기본</span></button
                    ><button class="answer__correct"
                      ><i class="answer__number"></i><span>안녕하세요 저는 정답입니다</span></button
                    ><button class="answer__wrong__check"
                      ><i class="answer__number"></i><span>저는 틀렸다가 오답체크 했습니다.</span></button
                    ><button class="answer__wrong"><i class="answer__number"></i><span>오답</span></button
                    ><button class="answer__check"
                      ><i class="answer__number"></i><span>다중선택: 여러개를 선택해야하는 경우</span></button
                    ></div
                  >
                </div>
                <div class="answer__correct">
                  <i class="answer__sign">2</i>
                  <span class="">1</span>
                </div>
                <div class="answers--bubble show"
                  ><div class="keypad"
                    ><div class="keypad__answer"></div
                    ><div class="keypad__btns"
                      ><button type="button">1</button><button type="button">2</button><button type="button">3</button
                      ><button type="button">4</button><button type="button">5</button><button type="button">6</button
                      ><button type="button">7</button><button type="button">8</button><button type="button">9</button
                      ><button type="button">0</button><button type="button">.</button
                      ><button type="button"
                        ><svg
                          class="svg-inline--fa fa-minus"
                          aria-hidden="true"
                          focusable="false"
                          data-prefix="far"
                          data-icon="minus"
                          role="img"
                          xmlns="http://www.w3.org/2000/svg"
                          viewBox="0 0 448 512"
                        >
                          <path
                            class=""
                            fill="currentColor"
                            d="M432 256c0 13.3-10.7 24-24 24L40 280c-13.3 0-24-10.7-24-24s10.7-24 24-24l368 0c13.3 0 24 10.7 24 24z"
                          ></path></svg></button
                      ><button type="button"
                        ><svg
                          class="svg-inline--fa fa-plus"
                          aria-hidden="true"
                          focusable="false"
                          data-prefix="far"
                          data-icon="plus"
                          role="img"
                          xmlns="http://www.w3.org/2000/svg"
                          viewBox="0 0 448 512"
                        >
                          <path
                            class=""
                            fill="currentColor"
                            d="M248 72c0-13.3-10.7-24-24-24s-24 10.7-24 24V232H40c-13.3 0-24 10.7-24 24s10.7 24 24 24H200V440c0 13.3 10.7 24 24 24s24-10.7 24-24V280H408c13.3 0 24-10.7 24-24s-10.7-24-24-24H248V72z"
                          ></path></svg></button
                      ><button type="button" class="keypad__sub"
                        ><svg
                          class="svg-inline--fa fa-rotate-left"
                          aria-hidden="true"
                          focusable="false"
                          data-prefix="far"
                          data-icon="rotate-left"
                          role="img"
                          xmlns="http://www.w3.org/2000/svg"
                          viewBox="0 0 512 512"
                        >
                          <path
                            class=""
                            fill="currentColor"
                            d="M140.8 122.9C171.7 96.2 212 80 256 80c97.2 0 176 78.8 176 176s-78.8 176-176 176c-39.7 0-76.2-13.1-105.6-35.2c-10.6-8-25.6-5.8-33.6 4.8s-5.8 25.6 4.8 33.6C159 463.3 205.6 480 256 480c123.7 0 224-100.3 224-224S379.7 32 256 32c-57.3 0-109.6 21.5-149.2 56.9L76.3 58.3C69.7 51.7 60.7 48 51.3 48C31.8 48 16 63.8 16 83.3V200c0 13.3 10.7 24 24 24H156.7c19.5 0 35.3-15.8 35.3-35.3c0-9.4-3.7-18.3-10.3-25l-40.8-40.8zm-76.8-9L126.1 176H64V113.9z"
                          ></path></svg></button
                      ><button type="button" class="keypad__sub"
                        ><svg
                          class="svg-inline--fa fa-delete-left"
                          aria-hidden="true"
                          focusable="false"
                          data-prefix="far"
                          data-icon="delete-left"
                          role="img"
                          xmlns="http://www.w3.org/2000/svg"
                          viewBox="0 0 576 512"
                        >
                          <path
                            class=""
                            fill="currentColor"
                            d="M205.3 64c-17 0-33.3 6.7-45.3 18.7L9.4 233.4c-6 6-9.4 14.1-9.4 22.6s3.4 16.6 9.4 22.6L160 429.3c12 12 28.3 18.7 45.3 18.7H512c35.3 0 64-28.7 64-64V128c0-35.3-28.7-64-64-64H205.3zM528 128V384c0 8.8-7.2 16-16 16H205.3c-4.2 0-8.3-1.7-11.3-4.7L54.6 256 193.9 116.7c3-3 7.1-4.7 11.3-4.7H512c8.8 0 16 7.2 16 16zm-95 47c-9.4-9.4-24.6-9.4-33.9 0l-47 47-47-47c-9.4-9.4-24.6-9.4-33.9 0s-9.4 24.6 0 33.9l47 47-47 47c-9.4 9.4-9.4 24.6 0 33.9s24.6 9.4 33.9 0l47-47 47 47c9.4 9.4 24.6 9.4 33.9 0s9.4-24.6 0-33.9l-47-47 47-47c9.4-9.4 9.4-24.6 0-33.9z"
                          ></path></svg></button
                      ><button class="keypad__check" type="button"
                        ><svg
                          class="svg-inline--fa fa-check"
                          aria-hidden="true"
                          focusable="false"
                          data-prefix="far"
                          data-icon="check"
                          role="img"
                          xmlns="http://www.w3.org/2000/svg"
                          viewBox="0 0 448 512"
                        >
                          <path
                            class=""
                            fill="currentColor"
                            d="M441 103c9.4 9.4 9.4 24.6 0 33.9L177 401c-9.4 9.4-24.6 9.4-33.9 0L7 265c-9.4-9.4-9.4-24.6 0-33.9s24.6-9.4 33.9 0l119 119L407 103c9.4-9.4 24.6-9.4 33.9 0z"
                          ></path></svg></button></div></div
                ></div>
              </div>
            </div>
          </div>
        </div>

        <div class="box bg--gray">
          <h2 id="tab">tab count</h2>
          <h4>기본탭 : <code class="code">.tab</code></h4>
          <h4>문제영역 : <code class="code">.tab.tab--count</code></h4>
          <h4>현재 : .tab.tab--count > .current</h4>
          <h4>정답 : .tab.tab--count > .correct</h4>
          <h4>오답 : .tab.tab--count > .wrong</h4>
          <h4>체크 : .tab.tab--count > .check</h4>
          <h4>세모 : .tab.tab--count > .triangle</h4>
          <ul class="tabs tabs--count">
            <li class=""></li>
            <li class="correct"></li>
            <li class="wrong"></li>
            <li class="check"></li>
            <li class="triangle"></li>
            <li></li>
            <li></li>
            <li></li>
            <li></li>
            <li></li>
          </ul>
          <ul class="tabs tabs--count">
            <li class="current"></li>
            <li class="correct current"></li>
            <li class="wrong current"></li>
            <li class="check current"></li>
            <li class="triangle current"></li>
            <li class="current"></li>
            <li class="current"></li>
            <li class="current"></li>
            <li class="current"></li>
            <li class="current"></li>
          </ul>
        </div>

        <div class="box bg--gray">
          <h2 id="keypad">keypad</h2>
          <p>
            <code class="code">.keypad</code> : 기본 <br /><br />
            <code class="code">.answers--bubble</code> : 말풍선모양의 답안: 기본안보임 //
            <code class="code">.answers--bubble.show</code> : 말풍선모양의 보임 <br /><br />
            <small>키보드로 입력 불가능하게 해주세요 (버튼으로만 입력 가능하게)</small> </p
          ><br />
          <h4 data-v-c953f01b="">키패드 기본형태</h4>
          <div class="keypad">
            <div class="keypad__answer">
              <input type="text" name="answer" class="" placeholder="정답을 입력해주세요." />
            </div>
            <div class="keypad__btns">
              <button type="button">1</button>
              <button type="button">2</button>
              <button type="button">3</button>
              <button type="button">4</button>
              <button type="button">5</button>
              <button type="button">6</button>
              <button type="button">7</button>
              <button type="button">8</button>
              <button type="button">9</button>
              <button type="button">0</button>
              <button type="button">.</button>
              <button type="button">
                <font-awesome-icon icon="fa-regular fa-minus" />
              </button>
              <button type="button">
                <font-awesome-icon icon="fa-regular fa-plus" />
              </button>
              <button type="button" class="keypad__sub">
                <font-awesome-icon icon="fa-regular fa-rotate-left" />
              </button>
              <button type="button" class="keypad__sub">
                <font-awesome-icon icon="fa-regular fa-backspace" />
              </button>
              <button type="button" class="keypad__check">
                <font-awesome-icon icon="fa-regular fa-check" />
              </button>
            </div>
          </div>
          <br />
          <h4 data-v-c953f01b="">왼쪽에 있는 키패드 (입력창이 키패드 안에 있음)</h4>
          <div class="answers--bubble show" style="position: initial">
            <div class="keypad">
              <div class="keypad__answer">
                <input type="text" name="answer" class="" placeholder="정답을 입력해주세요." />
              </div>
              <div class="keypad__btns">
                <button type="button">1</button>
                <button type="button">2</button>
                <button type="button">3</button>
                <button type="button">4</button>
                <button type="button">5</button>
                <button type="button">6</button>
                <button type="button">7</button>
                <button type="button">8</button>
                <button type="button">9</button>
                <button type="button">0</button>
                <button type="button">.</button>
                <button type="button">
                  <font-awesome-icon icon="fa-regular fa-minus" />
                </button>
                <button type="button">
                  <font-awesome-icon icon="fa-regular fa-plus" />
                </button>
                <button type="button" class="keypad__sub">
                  <font-awesome-icon icon="fa-regular fa-rotate-left" />
                </button>
                <button type="button" class="keypad__sub">
                  <font-awesome-icon icon="fa-regular fa-backspace" />
                </button>
                <button type="button" class="keypad__check">
                  <font-awesome-icon icon="fa-regular fa-check" />
                </button>
              </div>
            </div>
          </div>
          <br />
          <h4 data-v-c953f01b="">오른쪽에 있는 키패드 (입력창이 키패드 안에 없음)</h4>
          <div class="answers--bubble show" style="position: initial">
            <div class="keypad">
              <div class="keypad__btns">
                <button type="button">1</button>
                <button type="button">2</button>
                <button type="button">3</button>
                <button type="button">4</button>
                <button type="button">5</button>
                <button type="button">6</button>
                <button type="button">7</button>
                <button type="button">8</button>
                <button type="button">9</button>
                <button type="button">0</button>
                <button type="button">.</button>
                <button type="button">
                  <font-awesome-icon icon="fa-regular fa-minus" />
                </button>
                <button type="button">
                  <font-awesome-icon icon="fa-regular fa-plus" />
                </button>
                <button type="button" class="keypad__sub">
                  <font-awesome-icon icon="fa-regular fa-rotate-left" />
                </button>
                <button type="button" class="keypad__sub">
                  <font-awesome-icon icon="fa-regular fa-backspace" />
                </button>
                <button type="button" class="keypad__check">
                  <font-awesome-icon icon="fa-regular fa-check" />
                </button>
              </div>
            </div>
          </div>
        </div>

        <div id="login" class="box">
          <h2>Login</h2>
          <h4>로그인 : .login_wrap max-width: 400px</h4>
          <h4>다음버튼 : .panel__btns > .btn--brand</h4>
          <h4>input 영역 : .input</h4>
          <h4>input: .input__text</h4>
          <h4>SNS 계정으로 로그인 : .login_social > .btn--white </h4>
          <h4>에러메세지 : .box__error > span </h4>
          <div class="login_wrap">
            <h2 class="brand_logo"><img src="../../../assets/img/math/brand_logo.svg" alt="" /></h2>

            <div class="panel_group">
              <div class="input error">
                <font-awesome-icon icon="fa-regular fa-user" />
                <input type="text" class="input__text" placeholder="아이디" />
              </div>
              <div class="input">
                <font-awesome-icon icon="fa-regular fa-lock" />
                <input type="password" class="input__text" placeholder="비밀번호" />
                <button class="btn_pw">
                  <font-awesome-icon icon="fa-regular fa-eye" />
                </button>
              </div>
            </div>
            <ul class="box__error">
              <li>
                <span class="">아이디: 아이디를 입력해 주세요.</span>
              </li>
              <li>
                <span class="">비밀번호: 비밀번호를 입력해 주세요.</span>
              </li>
            </ul>
            <div class="keep__check">
              <input id="keep" type="checkbox" class="input__keep" />
              <label for="keep">아이디 저장</label>
            </div>

            <div class="panel__btns">
              <button class="btn--brand">다음</button>
            </div>

            <!-- 카카오/네이버 로그인 -->
            <div class="login_social">
              <strong class="title"> SNS 계정으로 로그인 </strong>
              <button class="btn--white">
                <img src="../../../assets/img/math/ico_naver.svg" alt="" />
                네이버 로그인
              </button>
              <button class="btn--white">
                <img src="../../../assets/img/math/ico_kakao.svg" alt="" />
                카카오 톡로그인
              </button>
            </div>
          </div>

          <h2>회원가입</h2>
          <h4>회색박스 : .input__box</h4>
          <h4>input + 버튼 : .input--row</h4><br />
          <div class="container--sm">
            <div class="input__box">
              <h4>계정 정보</h4>
              <div class="input--row">
                <div class="input error">
                  <font-awesome-icon icon="fa-regular fa-user" />
                  <input type="text" class="input__text" placeholder="아이디" />
                </div>
                <button class="btn--gray">중복확인</button>
              </div>
              <div class="input">
                <font-awesome-icon icon="fa-regular fa-lock" />
                <input type="password" class="input__text" placeholder="비밀번호" />
                <button class="btn_pw">
                  <font-awesome-icon icon="fa-regular fa-eye" />
                </button>
              </div>
              <div class="input">
                <font-awesome-icon icon="fa-regular fa-lock" />
                <input type="password" class="input__text" placeholder="비밀번호 확인" />
                <button class="btn_pw">
                  <font-awesome-icon icon="fa-regular fa-eye" />
                </button>
              </div>

              <ul class="box__error">
                <li>
                  <span class="">다시 로그인해 주세요</span>
                </li>
                <li>
                  <span class="">다시 로그인해 주세요</span>
                </li>
              </ul>
            </div>
          </div>
        </div>
      </div>
    </div>
  </AppLearningLayout>
</template>

<script setup lang="ts">
  import { ref } from 'vue';

  const isLoading = ref(false);

  const fetchData = async () => {
    isLoading.value = true;

    try {
      await new Promise((resolve) => setTimeout(resolve, 2000));
    } finally {
      isLoading.value = false;
    }
  };
</script>

<style scoped>
  body {
    user-select: unset !important;
  }

  .wrap_doc {
    display: flex;
    justify-content: center;
    max-width: 1200px;
    margin: 0 auto;
    position: relative;
  }

  .wrap_doc > .container {
    flex: 1;
    padding: 1rem;
  }

  .aside {
    width: 200px;
    position: sticky;
    top: 0;
    height: 100px;
    padding: 1rem;
    line-height: 2;
    text-transform: capitalize;
  }

  .aside a {
    display: block;
  }

  h2 {
    text-transform: capitalize;
  }

  .code {
    display: inline-block;
    background-color: black;
    color: yellow;
    font-family: 'Source Code Pro', 'SF Mono', Monaco, Inconsolata, 'Fira Mono', 'Droid Sans Mono', monospace, monospace;
    border-radius: 4px;
    padding: 2px 4px;
    user-select: all;
    font-size: 16px !important;
    text-transform: none !important;
  }
</style>

<style scoped>
  .articles {
    height: auto;
    min-height: auto !important;
    padding: 1rem;
  }

  .btns {
    justify-content: flex-start;
  }

  .box {
    width: 100%;
    border: 1px solid lightgrey;
    padding: 1rem;
    margin-bottom: 1.5rem;
    border-radius: 1rem;
    position: relative;
  }

  .color {
    width: 100%;
  }
</style>

<style scoped>
  @font-face {
    font-family: 'ae2ef524fbf3d9fe611d5a8e90fefdc';
    src: url(data:font/truetype;charset=utf-8;base64,AAEAAAAMAIAAAwBAT1MvMjv/LJYAAADMAAAATmNtYXDgWxEdAAABHAAAADRjdnQgAAAABwAAAVAAAAAEZ2x5ZoYrxVAAAAFUAAAA0WhlYWQOdyayAAACKAAAADZoaGVhC0UVwQAAAmAAAAAkaG10eCg8AIUAAAKEAAAACGxvY2EAAAVKAAACjAAAAAxtYXhwBIoEWwAAApgAAAAgbmFtZXSF9ZsAAAK4AAABrXBvc3QDogHPAAAEaAAAACBwcmVwukanGAAABIgAAAANAAAGtAGQAAUAAAgACAAAAAAACAAIAAAAAAAAAQIAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAACAgICAAAAAg8AMGe/57AAAHPgGyAAAAAAACAAEAAQAAABQAAwABAAAAFAAEACAAAAAEAAQAAQAAAGH//wAAAGH///+gAAEAAAAAAAAABwACAFUAAAMAA6sAAwAHAAAzESERJSERIVUCq/2rAgD+AAOr/FVVAwAAAwAt/3QEAwRZAAsAFwAdADsYAbAdELAD1LADELAU1LAUELAc1LAcELAJ1LAcELAOPLAJELAbPACwBhCwEdSwBhCwANSwABCwF9QwMQEiABEWEjMyEjcQJgYWAwIGIyImNTQ2MwE1BhMjEgIBs/7fFvWy07oDhYZwFgxOhVmysoUB7YwEslEEWf7f/t71/t8BM+MBp5yyLf6d/wBlyJzfsvxZjF0B5/1eAAAAAAEAAAABAACav9usXw889QADCAD/////1a3uPf/////Vre49AAH+9QQDBkMAAAAKAAIAAQAAAAAAAQAABz7+TgAAF3AAAf/8BAMAAQAAAAAAAAAAAAAAAAAAAAIDUgBVBEwALQAAAAAAAAAoAAAA0QABAAAAAgAeAAMAAAAAAAIAgAQAAAAAAAQAADsAAAAAAAAAFQECAAAAAAAAAAEAFgAAAAAAAAAAAAIADgAWAAAAAAAAAAMANAAkAAAAAAAAAAQAFgBYAAAAAAAAAAUAFgBuAAAAAAAAAAYACwCEAAAAAAAAAAgAHACPAAEAAAAAAAEAFgAAAAEAAAAAAAIADgAWAAEAAAAAAAMANAAkAAEAAAAAAAQAFgBYAAEAAAAAAAUAFgBuAAEAAAAAAAYACwCEAAEAAAAAAAgAHACPAAMAAQQJAAEAFgAAAAMAAQQJAAIADgAWAAMAAQQJAAMANAAkAAMAAQQJAAQAFgBYAAMAAQQJAAUAFgBuAAMAAQQJAAYACwCEAAMAAQQJAAgAHACPAE0AYQB0AGgAIABGAG8AbgB0ACAAMgBSAGUAZwB1AGwAYQByAE0AYQB0AGgAcwAgAEYAbwByACAATQBvAHIAZQAgAE0AYQB0AGgAIABGAG8AbgB0ACAAMgBNAGEAdABoACAARgBvAG4AdAAgADIAVgBlAHIAcwBpAG8AbgAgADEALgAwTWF0aF9Gb250XzIATQBhAHQAaABzACAARgBvAHIAIABNAG8AcgBlAAAAAAMAAAAAAAADnwHPAAAAAAAAAAAAAAAAAAAAAAAAAAC5ByIAAI2FGACyAAAAAAAA)
      format('truetype');
    font-weight: normal;
    font-style: normal;
  }

  @font-face {
    font-family: 'math1a56439fa686bc6a9cda8ba175e';
    src: url(data:font/truetype;charset=utf-8;base64,AAEAAAAMAIAAAwBAT1MvMi7iBBMAAADMAAAATmNtYXDEvmKUAAABHAAAAFRjdnQgDVUNBwAAAXAAAAA6Z2x5ZoPi2VsAAAGsAAACeGhlYWQQC2qxAAAEJAAAADZoaGVhCGsXSAAABFwAAAAkaG10eE2rRkcAAASAAAAAGGxvY2EAHTwYAAAEmAAAABxtYXhwBT0FPgAABLQAAAAgbmFtZaBxlY4AAATUAAABn3Bvc3QB9wD6AAAGdAAAACBwcmVwa1uragAABpQAAAAUAAADSwGQAAUAAAQABAAAAAAABAAEAAAAAAAAAQEAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAACAgICAAAAAg1UADev96AAAD6ACWAAAAAAACAAEAAQAAABQAAwABAAAAFAAEAEAAAAAMAAgAAgAEAD0AsADXAPciEv//AAAAPQCwANcA9yIS////xP9S/yz/Dd3zAAEAAAAAAAAAAAAAAAAAAAFUAywAgAEAAFYAKgJYAh4BDgEsAiwAWgGAAoAAoADUAIAAAAAAAAAAKwBVAIAAqwDVAQABKwAHAAAAAgBVAAADAAOrAAMABwAAMxEhESUhESFVAqv9qwIA/gADq/xVVQMAAAIAgADrAtUCFQADAAcAZRgBsAgQsAbUsAYQsAXUsAgQsAHUsAEQsADUsAYQsAc8sAUQsAQ8sAEQsAI8sAAQsAM8ALAIELAG1LAGELAH1LAHELAB1LABELAC1LAGELAFPLAHELAEPLABELAAPLACELADPDEwEyE1IR0BITWAAlX9qwJVAcBV1VVVAAIAVQHWAasDKwALABcASRgBsBgQsQgF9LESBfSxDwX0sQwF9LECBfSxGQX0swsVBQ8QPDw8ALAYELELAvSxDwX0sRIF9LEVBfSxBQX0swgMAhIQPDw8MDEAFhUUBiMuATc0Nh8BNCYjJgYVFBYXMjYBVlVVVlVWAVVVVSorKisrKisqAypVVVVVAVRWVVUBqisqASorKyoBKgACAIAAVQLVAoAAAwAHAEYYsAEUALEAABMQsQAJ5LEAARMQsAQ8sQYI9LACPDABsQgBExCxAAP2sAc8sQEF9bAGPLIFBwAQ9LACPLEJA+axBAX1sAM8EzMBIxEzASOAVQIAVVX+AFUCgP3VAiv91QADAIAAgAMAAoAAAwAHAAsAQRgBsAQQsQAD9LEEB/SwCDyxBRH0sAo8sQEH9LEMA+YAsQMMENWxAAX1sAMQsQgF9bELEfWwABCxBwX1sQQR9TAxEyEVIQEzFSMRMxUjgAKA/YABAICAgIABq1YBK4D/AIAAAQCAAVUC1QGrAAMAMBgBsAQQsQAD9rADPLECB/WwATyxBQPmALEAABMQsQAG5bEAARMQsAE8sQMF9bACPBMhFSGAAlX9qwGrVgABAAAAAQAA1XjOQV8PPPUAAwQA/////9Y6E3P/////1joTcwAA/yAEgAOrAAAACgACAAEAAAAAAAEAAAPo/2oAABdwAAD/tgSAAAEAAAAAAAAAAAAAAAAAAAAGA1IAVQNWAIACAABVA1YAgAOAAIADVgCAAAAAAAAAACgAAACyAAABTAAAAbsAAAIuAAACeAABAAAABgBeAAUAAAAAAAIAgAQAAAAAAAQAAN4AAAAAAAAAFQECAAAAAAAAAAEAEgAAAAAAAAAAAAIADgASAAAAAAAAAAMAMAAgAAAAAAAAAAQAEgBQAAAAAAAAAAUAFgBiAAAAAAAAAAYACQB4AAAAAAAAAAgAHACBAAEAAAAAAAEAEgAAAAEAAAAAAAIADgASAAEAAAAAAAMAMAAgAAEAAAAAAAQAEgBQAAEAAAAAAAUAFgBiAAEAAAAAAAYACQB4AAEAAAAAAAgAHACBAAMAAQQJAAEAEgAAAAMAAQQJAAIADgASAAMAAQQJAAMAMAAgAAMAAQQJAAQAEgBQAAMAAQQJAAUAFgBiAAMAAQQJAAYACQB4AAMAAQQJAAgAHACBAE0AYQB0AGgAIABGAG8AbgB0AFIAZQBnAHUAbABhAHIATQBhAHQAaABzACAARgBvAHIAIABNAG8AcgBlACAATQBhAHQAaAAgAEYAbwBuAHQATQBhAHQAaAAgAEYAbwBuAHQAVgBlAHIAcwBpAG8AbgAgADEALgAwTWF0aF9Gb250AE0AYQB0AGgAcwAgAEYAbwByACAATQBvAHIAZQAAAwAAAAAAAAH0APoAAAAAAAAAAAAAAAAAAAAAAAAAALkHEQAAjYUYALIAAAAVFBOxAAE/)
      format('truetype');
    font-weight: normal;
    font-style: normal;
  }
</style>
