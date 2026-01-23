# St-works Theme - Phase A 전체 검수 보고서

> **작성일**: 2026-01-22
> **검토 범위**: 테마 전체 (PHP, CSS, JavaScript)
> **테마 버전**: 1.0

---

## 목차
1. [기술적 결함](#1-기술적-결함)
2. [성능 및 SEO 최적화](#2-성능-및-seo-최적화)
3. [UX/UI 개선점](#3-uxui-개선점)
4. [코드 가독성 및 표준](#4-코드-가독성-및-표준)
5. [우선순위별 수정 가이드](#5-우선순위별-수정-가이드)

---

## 1. 기술적 결함

### 1.1 보안 취약점 (심각도: 높음)

#### A. XSS (Cross-Site Scripting)

| 파일 | 라인 | 문제점 | 수정 방안 |
|------|------|--------|----------|
| `inc/uw-board/class-uw-board-engine.php` | 551-552 | `$_REQUEST['token']` 직접 사용 | `$_POST` 명시적 사용 + nonce 검증 |
| `assets/js/board/uw-board.js` | 163, 269 | 라이트박스 `src` 속성 직접 사용 | `encodeURI()` 또는 `data-*` 속성 사용 |
| `footer.php` | 16-47 | 변수 출력 시 `esc_html()` 미사용 | 모든 출력에 이스케이프 적용 |

**수정 예시:**
```php
// Before (footer.php:17)
<span class="uw-footer-phone"><?php echo $telephone; ?></span>

// After
<span class="uw-footer-phone"><?php echo esc_html($telephone); ?></span>
```

#### B. 파일 업로드 검증 미흡

| 파일 | 라인 | 문제점 |
|------|------|--------|
| `inc/uw-board/class-uw-board-engine.php` | 860-873 | `finfo_open()` 존재 확인 없음 |
| `inc/uw-inquiry/class-uw-inquiry-handler.php` | 485-488 | `finfo_open()` 존재 확인 없음 |

**수정 코드:**
```php
// 파일 검증 전 확인
if (!function_exists('finfo_open')) {
    wp_send_json_error(array('message' => '파일 검증 기능을 사용할 수 없습니다.'));
    return;
}
```

---

### 1.2 중복 코드 및 오류

#### A. functions.php - 중복 스타일 등록

**위치**: [functions.php:15-16](functions.php#L15-L16)

```php
// 문제: 동일한 스타일 2번 등록
wp_enqueue_style('google-play-font', '//fonts.googleapis.com/css2?family=Play:wght@400;700&display=swap');
wp_enqueue_style('google-play-font', '//fonts.googleapis.com/css2?family=Play:wght@400;700&display=swap');  // 삭제 필요
```

#### B. 텍스트 도메인 불일치

**위치**: [functions.php:8](functions.php#L8)

```php
// 문제: 'myfourthwp' vs 'st-works'
load_theme_textdomain('myfourthwp', ...)  // 테마명과 불일치

// 수정
load_theme_textdomain('st-works', get_template_directory() . '/languages');
```

#### C. front-page.php - 중복 class 속성

**위치**: [front-page.php:267-268](front-page.php#L267-L268)

```html
<!-- 문제: class 속성 중복 -->
<a href="..." class="uw-contact-card" data-animate="fade-up" class="delay-1000">

<!-- 수정 -->
<a href="..." class="uw-contact-card delay-1000" data-animate="fade-up">
```

---

### 1.3 JavaScript 오류

#### A. app.js - 지도 API 오류

**위치**: [assets/js/app.js:6-21](assets/js/app.js#L6-L21)

```javascript
// 문제점:
// 1. mapDiv null 체크 없음
// 2. daum.maps와 kakao.maps 혼용
// 3. 하드코딩된 이미지 경로

const mapDiv = document.querySelector('#map');  // null 가능성
const options = {
  center: new daum.maps.LatLng(...)  // daum.maps 사용
};
const imageSrc = '/assets/images/bootstrap-logo.png';  // 잘못된 경로
const imageSize = new kakao.maps.Size(48, 48);  // kakao.maps 사용 (불일치)
```

**수정 코드:**
```javascript
document.addEventListener('DOMContentLoaded', function() {
  const mapDiv = document.querySelector('#map');
  if (!mapDiv || typeof kakao === 'undefined') return;

  const options = {
    center: new kakao.maps.LatLng(37.57599184507025, 126.9769613271878),
    scrollwheel: false,
    level: 3
  };

  const map = new kakao.maps.Map(mapDiv, options);
  // ... 나머지 코드
});
```

#### B. 이벤트 핸들러 메모리 누수

**위치**: [assets/js/board/uw-board.js:263-278](assets/js/board/uw-board.js#L263-L278)

```javascript
// 문제: 중복 이벤트 등록 가능
$('.uw-post-content img').on('click', function() { ... });

// 수정: 이벤트 위임 사용
if (!window.uwLightboxInitialized) {
  $(document).on('click', '.uw-post-content img', function() { ... });
  window.uwLightboxInitialized = true;
}
```

---

### 1.4 브라우저 호환성 이슈

#### A. ES6+ 문법 (IE11 미지원)

| 파일 | 문법 | 수정 필요 |
|------|------|----------|
| `assets/js/gallery/uw-gallery.js` | 화살표 함수 `=>` | `function()` 사용 |
| `assets/js/gallery/uw-gallery.js` | `Array.from()` | `Array.prototype.slice.call()` |
| `assets/js/common.js` | `const/let` | `var` (또는 Babel 사용) |

#### B. CSS Vendor Prefix 누락

```css
/* 현재 (문제) */
transform: scaleX(0);
appearance: none;
user-select: none;

/* 권장 */
-webkit-transform: scaleX(0);
-ms-transform: scaleX(0);
transform: scaleX(0);

-webkit-appearance: none;
-moz-appearance: none;
appearance: none;

-webkit-user-select: none;
-moz-user-select: none;
-ms-user-select: none;
user-select: none;
```

---

## 2. 성능 및 SEO 최적화

### 2.1 성능 문제점

#### A. CSS @import 사용 (렌더 블로킹)

**위치**: [assets/css/style.css](assets/css/style.css)

현재 모든 CSS 파일을 `@import`로 불러오는데, 이는 렌더링을 차단합니다.

**현재 방식:**
```css
@import 'base/_reset.css';
@import 'base/_variables.css';
@import 'layout/_structure.css';
/* ... 17개 이상 @import */
```

**권장 방식 (2가지 중 선택):**

1. **빌드 도구 사용** (권장): Gulp/Webpack으로 CSS 병합
2. **PHP에서 개별 로드** (대안):
```php
// functions.php에서 개별 파일 enqueue
wp_enqueue_style('st-reset', get_theme_file_uri('/assets/css/base/_reset.css'));
wp_enqueue_style('st-variables', get_theme_file_uri('/assets/css/base/_variables.css'));
// ...
```

#### B. 폰트 중복 로드

**위치**: [functions.php:15-16](functions.php#L15-L16) & [assets/css/base/_variables.css:29-30](assets/css/base/_variables.css#L29-L30)

```php
// functions.php - Google Fonts 로드
wp_enqueue_style('google-play-font', '//fonts.googleapis.com/css2?family=Play...');
```

```css
/* _variables.css - 동일 폰트 중복 @import */
@import url('https://fonts.googleapis.com/css2?family=Poppins...');
@import url('https://cdn.jsdelivr.net/gh/orioncactus/pretendard...');
```

**권장:** CSS 내 `@import` 제거, `functions.php`에서만 로드

#### C. 조건부 리소스 로드 미적용

```php
// 현재: 모든 페이지에서 Board CSS/JS 로드
wp_enqueue_style('xeicon', ...);
wp_enqueue_style('cm-bbs', ...);
wp_enqueue_style('uw-board-skin', ...);

// 권장: 필요한 페이지에서만 로드
if (is_singular('uw_board') || has_shortcode(get_the_content(), 'uw_board')) {
    wp_enqueue_style('xeicon', ...);
    // ...
}
```

---

### 2.2 SEO 문제점

#### A. 메타 정보 불일치

**위치**: [header.php:13-17](header.php#L13-L17)

```html
<!-- 문제: 테마명 "St-works"인데 메타 정보가 "크레딧커넥트" -->
<meta name="description" content="크레딧커넥트(CreditConnect) - 스마트 틴팅 기술의 글로벌 리더...">
<meta name="keywords" content="스마트틴팅, 스마트필름, PDLC, 조광유리, 크레딧커넥트">
<meta property="og:title" content="CreditConnect - Corporate Site Renewal">
```

**권장:** 동적 메타 정보 또는 실제 사이트 정보로 수정

#### B. Open Graph 이미지 누락

```html
<!-- 추가 필요 -->
<meta property="og:image" content="<?php echo get_theme_file_uri('/assets/images/og-image.jpg'); ?>">
<meta property="og:url" content="<?php echo home_url(); ?>">
```

#### C. 사이트 타이틀 처리 (deprecated)

**위치**: [header.php:9](header.php#L9)

```php
// 현재 (deprecated)
<title><?php wp_title('|', true, 'right'); ?><?php bloginfo('name'); ?></title>

// 권장
// functions.php에 추가:
add_theme_support('title-tag');
// header.php에서 <title> 태그 삭제 (WordPress가 자동 생성)
```

#### D. 구조화된 데이터 없음

Schema.org 마크업 추가 권장:
```php
<script type="application/ld+json">
{
  "@context": "https://schema.org",
  "@type": "Organization",
  "name": "St-works",
  "url": "<?php echo home_url(); ?>",
  "logo": "<?php echo get_theme_file_uri('/assets/images/logo.png'); ?>"
}
</script>
```

---

## 3. UX/UI 개선점

### 3.1 접근성 (Accessibility)

#### A. 버튼 접근성 레이블 없음

**위치**: [front-page.php:18-20](front-page.php#L18-L20)

```html
<!-- 문제: 스크린 리더가 읽을 수 없음 -->
<button class="uw-visual-page" data-index="0">01</button>

<!-- 권장 -->
<button class="uw-visual-page" data-index="0" aria-label="슬라이드 1로 이동">01</button>
```

#### B. 이미지 alt 텍스트 부실

```html
<!-- 문제: 의미 없는 alt -->
<img src="..." alt="Patent 1">

<!-- 권장 -->
<img src="..." alt="스마트 틴팅 필름 관련 특허 인증서">
```

#### C. 키보드 네비게이션 미지원

오버레이 메뉴에서 키보드 탐색 개선 필요:
```javascript
// ESC 키로 메뉴 닫기
document.addEventListener('keydown', function(e) {
  if (e.key === 'Escape' && overlayMenu.classList.contains('is-active')) {
    closeOverlayMenu();
  }
});
```

---

### 3.2 모바일 터치 영역

#### A. 버튼 크기 미달

Apple HIG 권장 최소 터치 영역: 44×44px

```css
/* 현재 (문제) */
.uw-visual-page {
  /* 클릭 영역 불명확 */
}

/* 권장 */
.uw-visual-page {
  min-width: 44px;
  min-height: 44px;
  display: flex;
  align-items: center;
  justify-content: center;
}
```

---

### 3.3 반응형 브레이크포인트 현황

현재 사용 중인 브레이크포인트:
- **1300px**: 슬라이더 전용
- **1180px**: 헤더 전용
- **1024px**: 태블릿 (다수 파일)
- **782px**: WordPress 관리자 전용
- **768px**: 모바일 (다수 파일)
- **480px**: 소형 모바일 (일부 파일)

**권장:** 브레이크포인트 변수화
```css
:root {
  --breakpoint-desktop: 1024px;
  --breakpoint-tablet: 768px;
  --breakpoint-mobile: 480px;
}
```

---

## 4. 코드 가독성 및 표준

### 4.1 네이밍 컨벤션

#### A. 일관성 있는 BEM 패턴 (양호)

```css
/* 좋은 예시 - 현재 사용 중 */
.uw-visual {}
.uw-visual-item {}
.uw-visual-item.is-active {}
```

#### B. CSS 변수 명명 불일치

**위치**: [assets/css/base/_variables.css](assets/css/base/_variables.css)

```css
/* 문제: 두 가지 네이밍 체계 혼용 */
--primary-color: #0F172A;      /* 일반 네이밍 */
--uw-primary: #1d8795;         /* UW 프리픽스 */
--secondary-color: #00B4D8;    /* 일반 네이밍 */
--uw-secondary: #0085CA;       /* UW 프리픽스 */
```

**권장:** `--uw-*` 프리픽스로 통일 또는 일반 네이밍으로 통일

---

### 4.2 코드 구조화

#### A. functions.php 개선 필요

현재 구조:
```php
// 모든 기능이 하나의 파일에 혼재
add_action('after_setup_theme', ...);
add_action('wp_enqueue_scripts', ...);
require_once('uw-board');
require_once('uw-inquiry');
require_once('uw-gallery');
```

권장 구조:
```php
// functions.php
require_once get_template_directory() . '/inc/theme-setup.php';      // 테마 설정
require_once get_template_directory() . '/inc/enqueue-scripts.php';   // 에셋 로드
require_once get_template_directory() . '/inc/custom-functions.php';  // 헬퍼 함수

// 모듈 로드
foreach (glob(get_template_directory() . '/inc/uw-*/class-*-cpt.php') as $file) {
    require_once $file;
}
// ...
```

---

### 4.3 하드코딩된 값

#### A. 저작권 연도

**위치**: [footer.php:52](footer.php#L52)

```php
// 문제
<p class="uw-footer-copyright">© 2025 STWORKS Corp. ALL RIGHTS RESERVED.</p>

// 수정
<p class="uw-footer-copyright">© <?php echo date('Y'); ?> STWORKS Corp. ALL RIGHTS RESERVED.</p>
```

#### B. 연락처 정보

**위치**: [front-page.php:273-274](front-page.php#L273-L274)

```html
<!-- 문제: 하드코딩 -->
<span class="uw-contact-email">contact@st-works.co.kr</span>
<span class="uw-contact-tel">044-715-7050</span>

<!-- 권장: ACF 변수 사용 -->
<?php include get_theme_file_path('inc/variables.php'); ?>
<span class="uw-contact-email"><?php echo esc_html($email ?? 'contact@st-works.co.kr'); ?></span>
<span class="uw-contact-tel"><?php echo esc_html($telephone ?? '044-715-7050'); ?></span>
```

---

## 5. 우선순위별 수정 가이드

### 긴급 (즉시 수정)

| # | 항목 | 파일 | 영향도 |
|---|------|------|--------|
| 1 | XSS 취약점 수정 | `footer.php` | 보안 |
| 2 | `finfo_open()` 검증 추가 | `class-uw-board-engine.php` | 보안 |
| 3 | 중복 Google Fonts 삭제 | `functions.php:16` | 성능 |
| 4 | 중복 class 속성 수정 | `front-page.php:267` | 오류 |

### 높음 (1주 내)

| # | 항목 | 파일 | 영향도 |
|---|------|------|--------|
| 1 | 메타 정보 수정 | `header.php` | SEO |
| 2 | app.js 지도 오류 수정 | `assets/js/app.js` | 기능 |
| 3 | 텍스트 도메인 통일 | `functions.php` | 국제화 |
| 4 | OG 이미지 추가 | `header.php` | SEO |

### 보통 (2주 내)

| # | 항목 | 파일 | 영향도 |
|---|------|------|--------|
| 1 | Vendor prefix 추가 | CSS 전체 | 호환성 |
| 2 | ES6 문법 대체 | JS 전체 | 호환성 |
| 3 | CSS @import 최적화 | `style.css` | 성능 |
| 4 | 조건부 스크립트 로드 | `functions.php` | 성능 |

### 낮음 (필요시)

| # | 항목 | 파일 | 영향도 |
|---|------|------|--------|
| 1 | CSS 변수명 통일 | `_variables.css` | 유지보수 |
| 2 | functions.php 분리 | `functions.php` | 유지보수 |
| 3 | 접근성 개선 | 템플릿 전체 | UX |
| 4 | 구조화 데이터 추가 | `header.php` | SEO |

---

## 부록: 수정 체크리스트

```
[ ] footer.php - esc_html() 이스케이프 추가
[ ] functions.php - 중복 enqueue 삭제 (라인 16)
[ ] functions.php - 텍스트 도메인 'st-works'로 변경
[ ] front-page.php - 중복 class 속성 병합 (라인 267)
[ ] header.php - 메타 정보 실제 사이트에 맞게 수정
[ ] header.php - OG 이미지 추가
[ ] header.php - title-tag 지원 추가
[ ] app.js - 지도 API 오류 수정
[ ] class-uw-board-engine.php - finfo_open() 검증 추가
[ ] class-uw-inquiry-handler.php - finfo_open() 검증 추가
[ ] _variables.css - CSS 변수명 통일
[ ] _variables.css - @import 최적화
```

---

*이 보고서는 Phase A (전체 검수 및 최적화) 결과입니다.*
*Phase B (시스템화 및 템플릿 구조화)는 별도로 진행됩니다.*
