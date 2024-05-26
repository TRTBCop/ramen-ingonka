<!-- temp: only for doc -->
<template>
  <AppLearningLayout>
    <div class="wrap_doc">
      <aside class="aside">
        <a href="#star">별</a>
        <a href="#planets">행성</a>
        <a href="#profile">프로필</a>
      </aside>

      <div class="container">
        <div class="box">
          <h2 id="star">별</h2>
          <h4>별 감싸는 태그 : <code class="code">.stars</code></h4>
          <h4>사이즈: <code class="code">.stars.stars--lg, .stars.stars--md, .stars.stars--sm</code></h4>
          <h4>등급: <code class="code">.star--s, .star--a, .star--b, .star--c, .star > i</code></h4
          ><br />
          <div class="stars">
            <i class="star star--lg"></i>
            <i class="star star--a star--lg"></i>
            <i class="star star--b star--lg"></i>
            <i class="star star--c star--lg"></i>
            <i class="star star--s star--lg"></i>
          </div>
          <div class="stars">
            <i class="star star--md"></i>
            <i class="star star--a star--md"></i>
            <i class="star star--b star--md"></i>
            <i class="star star--c star--md"></i>
            <i class="star star--s star--md"></i>
          </div>
          <div class="stars">
            <i class="star star--sm"></i>
            <i class="star star--a star--sm"></i>
            <i class="star star--b star--sm"></i>
            <i class="star star--c star--sm"></i>
            <i class="star star--s star--sm"></i>
          </div>
        </div>

        <div class="box">
          <h2 id="planets">행성</h2>
          <h4>학기별: <code class="code">.semester > .semester__thmb.semester--n-n </code></h4>
          <h4>초등: <code class="code">3-1/3-2/4-1/4-2/5-1/5-2/6-1/6-2 </code></h4><br />
          <ul class="lst__semester">
            <li class="semester">
              <i class="semester__thmb semester--3-1"></i>
            </li>
            <li class="semester">
              <i class="semester__thmb semester--3-2"></i>
            </li>
            <li class="semester">
              <i class="semester__thmb semester--4-1"></i>
            </li>
            <li class="semester">
              <i class="semester__thmb semester--4-2"></i>
            </li>
            <li class="semester">
              <i class="semester__thmb semester--5-1"></i>
            </li>
            <li class="semester">
              <i class="semester__thmb semester--5-2"></i>
            </li>
            <li class="semester">
              <i class="semester__thmb semester--6-1"></i>
            </li>
            <li class="semester">
              <i class="semester__thmb semester--6-2"></i>
            </li> </ul
          ><br />
          <h4>계통별: <code class="code">.semester > .semester__thmb.semester--n </code></h4>
          <h4>순서: <code class="code">1.수와연산 2.규칙성 3.도형 4.측정 5.자료와 가능성 </code></h4><br />
          <ul class="lst__semester">
            <li class="semester"> <i class="semester__thmb semester--1"></i> </li>
            <li class="semester"> <i class="semester__thmb semester--2"></i> </li>
            <li class="semester"> <i class="semester__thmb semester--3"></i> </li>
            <li class="semester"> <i class="semester__thmb semester--4"></i> </li>
            <li class="semester"> <i class="semester__thmb semester--5"></i> </li>
          </ul>
        </div>

        <div class="box">
          <h2 id="profile">프로필</h2>
          <h4>감싸는 태그 : <code class="code">.profile_wrap</code></h4>
          <h4
            >사이즈:
            <code class="code"
              >.profile__img--lg : 160*160, .profile__img--md : 120*120, .profile__img--sm : 80*80</code
            ></h4
          >
          <h4>타입: <code class="code">.profile__img--type0n</code></h4
          ><br />
          <div class="modal_body profile_wrap">
            <button class="current"><i class="profile__img profile__img--lg profile__img--type01"></i></button>
            <button><i class="profile__img profile__img--lg profile__img--type02"></i></button>
            <button><i class="profile__img profile__img--lg profile__img--type03"></i></button>
            <button><i class="profile__img profile__img--lg profile__img--type04"></i></button>
            <button><i class="profile__img profile__img--lg profile__img--type05"></i></button>
            <button><i class="profile__img profile__img--lg profile__img--type06"></i></button>
            <button><i class="profile__img profile__img--lg profile__img--type07"></i></button>
            <button><i class="profile__img profile__img--lg profile__img--type08"></i></button>
          </div>
          <div class="modal_body profile_wrap">
            <button class="current"><i class="profile__img profile__img--md profile__img--type01"></i></button>
            <button><i class="profile__img profile__img--md profile__img--type02"></i></button>
            <button><i class="profile__img profile__img--md profile__img--type03"></i></button>
            <button><i class="profile__img profile__img--md profile__img--type04"></i></button>
            <br />
            <button><i class="profile__img profile__img--md profile__img--type05"></i></button>
            <button><i class="profile__img profile__img--md profile__img--type06"></i></button>
            <button><i class="profile__img profile__img--md profile__img--type07"></i></button>
            <button><i class="profile__img profile__img--md profile__img--type08"></i></button>
          </div>
          <div class="modal_body profile_wrap">
            <button class="current"><i class="profile__img profile__img--sm profile__img--type01"></i></button>
            <button><i class="profile__img profile__img--sm profile__img--type02"></i></button>
            <button><i class="profile__img profile__img--sm profile__img--type03"></i></button>
            <button><i class="profile__img profile__img--sm profile__img--type04"></i></button>
            <button><i class="profile__img profile__img--sm profile__img--type05"></i></button>
            <button><i class="profile__img profile__img--sm profile__img--type06"></i></button>
            <button><i class="profile__img profile__img--sm profile__img--type07"></i></button>
            <button><i class="profile__img profile__img--sm profile__img--type08"></i></button>
          </div>
        </div>
      </div>
    </div>
  </AppLearningLayout>
</template>

<script></script>

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
  .box {
    width: 100%;
    border: 1px solid lightgrey;
    padding: 1rem;
    margin-bottom: 1.5rem;
    border-radius: 1rem;
    position: relative;
  }
  .lst__semester {
    display: flex;
    flex-wrap: wrap;
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
    src: url(data:font/truetype;charset=utf-8;base64,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)
      format('truetype');
    font-weight: normal;
    font-style: normal;
  }
</style>
