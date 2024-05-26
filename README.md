<!-- PROJECT LOGO -->
<br />
<div align="center">
  <a href="#">
    <img src="https://dailykor.com/img/brand/logo_mono.png" alt="Logo">
  </a>

<h3 align="center">리딩수학</h3>

  <p align="center">
    본프로젝트는 기존매일국어리딩수학 분리 프로젝트입니다.
    <br />
    <br />
  </p>
</div>

# 프로젝트소개
## 설치
### 기본 환경
- php 8.2
- mysql 8.0 이상
- node 18.0
- composer 2.0 이상


1. 저장소에서 파일 다운로드
```bash
git clone git@github.com:sloop-dev/reading-math.git
```
.env.example => .env 파일 복사생성 or 설정
2. composer install
3. yarn install
4. yarn build or dev


### 사용 패키지
- https://github.com/lazychaser/laravel-nestedset
- 
# 퍼블리셔 협업
### 윈도우
#### xampp로 환경 세팅
1. https://www.apachefriends.org/ 에서 최신버전 다운로드 설치
   ```bash
   # 확인
    php -v #8.x
   ```
2. https://getcomposer.org/download/ Windows Installer 다운로드 설치
    ```bash
   # 확인
    composer -v #2.x
   ```
3. https://nodejs.org/ko/download node lts  windows installer 설치

   ```bash
   # 확인
   node -v #18.x
   ```
4. xampp/php/php.ini 파일에서 아래 코드 주석 풀기
   ```bash
   extension=gd
   extension=zip
   ```
#### 저장소 다운
1. 저장소에서 파일 다운로드
```bash
git clone git@github.com:sloop-dev/reading-math.git
```
2. .env.example => .env 파일 복사
   
#### 패키지 설치
1. 아래 명령어 순차적으로 입력  
   ```bash
    # 아래 명령어 실행전에 명령어 실행 경로가 reading-math 경로인지 확인하기
   composer install
   php artisan key:generate

   npm config set "@fortawesome:registry" https://npm.fontawesome.com/
   npm config set "//npm.fontawesome.com/:_authToken" D2350B4F-333F-442D-8F93-40186A51D340
   yarn install
   ```
   
#### 서버 실행 및 접속
1. 서버 실행
    ```bash
    # 실행 하고 나면 주소가 콘솔에 뜰텐데 해당 주소 이용
    php artisan serve    
    ```
2. 로컬 작업 서버 실행
   ```bash
   # 아래 명령어로 뜨는 주소는 무시
   yarn dev
   ```

#### 작업 방법
1. 디렉토리 설명
   ```bash
    ├── resources
    │   ├── html # 퍼블리셔가 작업할 공간
    │       ├── components # 반복 사용할 것 같은 vue 파일
    │       ├── layouts # scss 불러오기 및 자주 반복 되는 레이아웃 구조 공통화
    │       ├── pages # 해당 폴더에 폴더/파일을 만들면 url로 접근 가능 아래 '페이지 접근 방법' 참고
    │       ├── app.ts # vue 세팅하는 파일
    │   ├── aseets # 퍼블리셔, 개발자가 공통으로 사용하는 assets 파일
   ```
2. 페이지 접근 방법  
   ```bash
   http://localhost:xxx/html/brand-Index 접근시
   =>
   resources/html/pages/brand/Index.vue 내용 출력 (파일명컨밴션-PascalCase)
   ```
3. assets 폴더 접근 방법
   ```bash
   @/aseets 로 접근
   ex) <img src="@/assets/img/brand/ico/img_airplane.png" alt="" />
   ```
4. scss 불러오는 방법
    ```bash
    # resources/app.ts 파일에서 아래와 같은 형식으로 불러오고 있음

    const appName = window.location.pathname.split('/').pop()?.split('-').shift();
    switch (appName) {
        case 'app':
            await import('@/assets/css/scss/math_bootstrap4.5.css');
            await import('@/assets/css/scss/math.scss');
            break;
        case 'brand':          
            await import('@/assets/css/brand/brand.scss');
            break;
        default:
            break;
      }

    ```   


