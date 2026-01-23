# St-works Theme - Phase B 시스템화 및 템플릿 가이드

> **작성일**: 2026-01-22
> **목적**: 재사용 가능한 표준 템플릿으로 변환
> **적용 범위**: 테마 전체 아키텍처

---

## 목차
1. [모듈화 (Modularization)](#1-모듈화-modularization)
2. [변수화 (Variable Extraction)](#2-변수화-variable-extraction)
3. [폴더 구조 재설계](#3-폴더-구조-재설계)
4. [자동화 포인트](#4-자동화-포인트)
5. [신규 프로젝트 적용 가이드](#5-신규-프로젝트-적용-가이드)

---

## 1. 모듈화 (Modularization)

### 1.1 현재 컴포넌트 분석

| 컴포넌트 | 현재 위치 | 재사용성 | 권장 조치 |
|---------|----------|---------|----------|
| 헤더 | `header.php` + `template-parts/header/` | 높음 | 유지 |
| 푸터 | `footer.php` | 중간 | 모듈화 필요 |
| 서브 비주얼 | `template-parts/sub-visual.php` | 높음 | 설정 분리 필요 |
| 게시판 | `inc/uw-board/` | 높음 | 독립 플러그인 가능 |
| 갤러리 | `inc/uw-gallery/` | 높음 | 독립 플러그인 가능 |
| 문의폼 | `inc/uw-inquiry/` | 높음 | 독립 플러그인 가능 |

### 1.2 신규 모듈 분리 제안

#### A. 섹션 헤더 컴포넌트
현재 front-page.php에서 반복 사용되는 패턴:

```php
// 현재: 코드 중복
<div class="uw-section-common-header">
  <span class="uw-sub-title">Business</span>
  <h2 class="uw-title">주요 사업 안내</h2>
</div>
```

**신규 컴포넌트 생성:** `template-parts/components/section-header.php`
```php
<?php
/**
 * Section Header Component
 *
 * @param array $args {
 *   @type string $sub_title   영문 서브 타이틀
 *   @type string $title       메인 타이틀
 *   @type string $animate     애니메이션 타입 (fade-up, fade-in 등)
 * }
 */
$sub_title = $args['sub_title'] ?? '';
$title = $args['title'] ?? '';
$animate = $args['animate'] ?? 'fade-up';
?>
<div class="uw-section-common-header">
  <?php if ($sub_title): ?>
    <span class="uw-sub-title" data-animate="<?php echo esc_attr($animate); ?>">
      <?php echo esc_html($sub_title); ?>
    </span>
  <?php endif; ?>
  <h2 class="uw-title delay-200" data-animate="<?php echo esc_attr($animate); ?>">
    <?php echo esc_html($title); ?>
  </h2>
</div>
```

**사용 예시:**
```php
get_template_part('template-parts/components/section-header', null, array(
  'sub_title' => 'Business',
  'title' => '주요 사업 안내'
));
```

#### B. 카드 컴포넌트
```php
// template-parts/components/card.php
<?php
$type = $args['type'] ?? 'default';  // default, business, product, contact
$title = $args['title'] ?? '';
$desc = $args['desc'] ?? '';
$link = $args['link'] ?? '#';
$image = $args['image'] ?? '';
$num = $args['num'] ?? '';
?>
<div class="uw-card uw-card--<?php echo esc_attr($type); ?>" data-animate="fade-up">
  <a href="<?php echo esc_url($link); ?>" class="uw-card__link">
    <?php if ($image): ?>
      <div class="uw-card__bg" style="background-image: url('<?php echo esc_url($image); ?>')"></div>
    <?php endif; ?>
    <div class="uw-card__content">
      <?php if ($num): ?>
        <span class="uw-card__num"><?php echo esc_html($num); ?></span>
      <?php endif; ?>
      <h3 class="uw-card__title"><?php echo esc_html($title); ?></h3>
      <?php if ($desc): ?>
        <p class="uw-card__desc"><?php echo wp_kses_post($desc); ?></p>
      <?php endif; ?>
    </div>
  </a>
</div>
```

#### C. CTA 버튼 컴포넌트
```php
// template-parts/components/button.php
<?php
$text = $args['text'] ?? 'Click';
$link = $args['link'] ?? '#';
$style = $args['style'] ?? 'primary';  // primary, secondary, outline, ghost
$size = $args['size'] ?? 'medium';     // small, medium, large
$icon = $args['icon'] ?? '';
$target = $args['target'] ?? '_self';
?>
<a href="<?php echo esc_url($link); ?>"
   class="uw-btn uw-btn--<?php echo esc_attr($style); ?> uw-btn--<?php echo esc_attr($size); ?>"
   target="<?php echo esc_attr($target); ?>">
  <?php echo esc_html($text); ?>
  <?php if ($icon): ?>
    <i class="<?php echo esc_attr($icon); ?>"></i>
  <?php endif; ?>
</a>
```

### 1.3 플러그인 분리 가능 모듈

다음 모듈들은 독립 플러그인으로 분리하여 다른 테마에서도 사용 가능:

| 모듈 | 플러그인명 제안 | 의존성 |
|------|---------------|--------|
| `uw-board` | UW Board Engine | jQuery, XEIcon |
| `uw-gallery` | UW Gallery Pro | jQuery, Masonry (선택) |
| `uw-inquiry` | UW Form Builder | jQuery |

**플러그인 구조 예시:**
```
uw-board-plugin/
├── uw-board.php                 # 플러그인 메인
├── includes/
│   ├── class-uw-board-cpt.php
│   ├── class-uw-board-admin.php
│   └── class-uw-board-engine.php
├── assets/
│   ├── css/
│   └── js/
├── templates/                   # 오버라이드 가능한 템플릿
│   ├── list-style01.php
│   ├── list-style02.php
│   └── list-style03.php
└── readme.txt
```

---

## 2. 변수화 (Variable Extraction)

### 2.1 테마 설정 JSON 구조

**신규 파일:** `config/theme-config.json`
```json
{
  "theme": {
    "name": "St-works",
    "version": "1.0.0",
    "text_domain": "st-works"
  },
  "colors": {
    "primary": "#1d8795",
    "secondary": "#0085CA",
    "accent": "#FF6B35",
    "text": "#111111",
    "text_light": "#666666",
    "background": "#FFFFFF",
    "background_alt": "#F8F9FA",
    "border": "#E5E5E5"
  },
  "typography": {
    "font_family_main": "'Play', 'Pretendard', -apple-system, sans-serif",
    "font_family_heading": "'Poppins', sans-serif",
    "font_size_base": "16px",
    "line_height_base": 1.6
  },
  "layout": {
    "max_width": "1500px",
    "header_height": "80px",
    "gutter": "30px",
    "section_padding": "120px"
  },
  "breakpoints": {
    "desktop": "1024px",
    "tablet": "768px",
    "mobile": "480px"
  },
  "transitions": {
    "default": "all 0.3s ease",
    "smooth": "all 0.4s cubic-bezier(0.25, 1, 0.5, 1)"
  },
  "social": {
    "facebook": "",
    "instagram": "",
    "youtube": "",
    "linkedin": ""
  },
  "company": {
    "name": "STWORKS Corp.",
    "ceo": "",
    "email": "contact@st-works.co.kr",
    "phone": "044-715-7050",
    "fax": "",
    "address": ""
  }
}
```

### 2.2 PHP 설정 로더

**신규 파일:** `inc/class-theme-config.php`
```php
<?php
/**
 * Theme Configuration Loader
 */
class Theme_Config {
    private static $instance = null;
    private $config = array();

    public static function get_instance() {
        if (null === self::$instance) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    private function __construct() {
        $this->load_config();
    }

    private function load_config() {
        $config_file = get_template_directory() . '/config/theme-config.json';

        if (file_exists($config_file)) {
            $json = file_get_contents($config_file);
            $this->config = json_decode($json, true);
        }
    }

    public function get($key, $default = '') {
        $keys = explode('.', $key);
        $value = $this->config;

        foreach ($keys as $k) {
            if (isset($value[$k])) {
                $value = $value[$k];
            } else {
                return $default;
            }
        }

        return $value;
    }

    public function get_all() {
        return $this->config;
    }
}

// Helper function
function theme_config($key, $default = '') {
    return Theme_Config::get_instance()->get($key, $default);
}
```

**사용 예시:**
```php
// 색상 가져오기
$primary_color = theme_config('colors.primary', '#1d8795');

// 회사 정보
$company_name = theme_config('company.name', 'Company');
$phone = theme_config('company.phone', '');
```

### 2.3 CSS 변수 자동 생성

**신규 파일:** `inc/class-theme-css-vars.php`
```php
<?php
/**
 * Generate CSS Variables from Config
 */
class Theme_CSS_Vars {
    public static function generate() {
        $config = Theme_Config::get_instance();
        $colors = $config->get('colors', array());
        $layout = $config->get('layout', array());
        $typography = $config->get('typography', array());

        $css = ":root {\n";

        // Colors
        foreach ($colors as $name => $value) {
            $css .= "  --uw-{$name}: {$value};\n";
        }

        // Layout
        foreach ($layout as $name => $value) {
            $css .= "  --uw-{$name}: {$value};\n";
        }

        // Typography
        if (isset($typography['font_family_main'])) {
            $css .= "  --uw-font-main: {$typography['font_family_main']};\n";
        }

        $css .= "}\n";

        return $css;
    }

    public static function inline_vars() {
        echo '<style id="theme-config-vars">' . self::generate() . '</style>';
    }
}

// Hook into wp_head
add_action('wp_head', array('Theme_CSS_Vars', 'inline_vars'), 5);
```

### 2.4 사이트맵 설정 분리

**현재 문제:** `sub-visual.php`에 사이트맵이 하드코딩됨

**신규 파일:** `config/sitemap.php`
```php
<?php
/**
 * Site Navigation Map
 *
 * 이 파일을 수정하여 네비게이션 구조를 변경합니다.
 */
return array(
    'about' => array(
        'label' => 'About Us',
        'bg_class' => 'about',
        'items' => array(
            array('slug' => '/about/ceo/', 'label' => 'CEO 인사말'),
            array('slug' => '/about/history/', 'label' => '연혁'),
            array('slug' => '/about/vision/', 'label' => '비전'),
            array('slug' => '/about/location/', 'label' => '오시는길'),
        )
    ),
    'business' => array(
        'label' => 'Business',
        'bg_class' => 'business',
        'items' => array(
            array('slug' => '/business/tech/', 'label' => '핵심기술'),
            array('slug' => '/business/areas/', 'label' => '사업분야'),
            array('slug' => '/business/cert/', 'label' => '특허&인증'),
        )
    ),
    'product' => array(
        'label' => 'Product',
        'bg_class' => 'product',
        'items' => array(
            array('slug' => '/product/product/', 'label' => '제품소개'),
        )
    ),
    'support' => array(
        'label' => 'Support',
        'bg_class' => 'support',
        'items' => array(
            array('slug' => '/support/notice/', 'label' => '공지사항'),
            array('slug' => '/support/news/', 'label' => '뉴스'),
        )
    ),
    'contact' => array(
        'label' => 'Contact',
        'bg_class' => 'contact',
        'items' => array(
            array('slug' => '/contact/contact/', 'label' => '상담 문의'),
        )
    ),
);
```

---

## 3. 폴더 구조 재설계

### 3.1 현재 구조 vs 권장 구조

```
현재 구조:                          권장 구조:
St-works/                          St-works/
├── assets/                        ├── assets/
│   ├── css/                       │   ├── css/
│   │   ├── base/                  │   │   ├── base/
│   │   ├── components/            │   │   ├── components/
│   │   ├── layout/                │   │   ├── layout/
│   │   ├── pages/                 │   │   ├── pages/
│   │   ├── board/                 │   │   └── modules/      (새로 통합)
│   │   ├── gallery/               │   │       ├── board/
│   │   └── inquiry/               │   │       ├── gallery/
│   ├── js/                        │   │       └── inquiry/
│   │   ├── board/                 │   ├── js/
│   │   ├── gallery/               │   │   ├── core/         (새로 분리)
│   │   └── inquiry/               │   │   │   ├── app.js
│   └── images/                    │   │   │   ├── common.js
├── inc/                           │   │   │   └── main.js
│   ├── uw-board/                  │   │   ├── pages/        (페이지별)
│   ├── uw-gallery/                │   │   └── modules/      (새로 통합)
│   ├── uw-inquiry/                │   │       ├── board/
│   └── variables.php              │   │       ├── gallery/
├── template-parts/                │   │       └── inquiry/
│   ├── header/                    │   └── images/
│   └── uw-board/                  │       ├── icons/
├── about/                         │       ├── hero/
├── business/                      │       └── content/
├── product/                       ├── config/              (새로 생성)
├── support/                       │   ├── theme-config.json
├── front-page.php                 │   └── sitemap.php
├── header.php                     ├── inc/
├── footer.php                     │   ├── core/            (새로 분리)
├── functions.php                  │   │   ├── class-theme-config.php
└── style.css                      │   │   ├── class-theme-css-vars.php
                                   │   │   ├── enqueue-scripts.php
                                   │   │   └── theme-setup.php
                                   │   ├── helpers/         (새로 분리)
                                   │   │   └── template-functions.php
                                   │   └── modules/         (새로 통합)
                                   │       ├── uw-board/
                                   │       ├── uw-gallery/
                                   │       └── uw-inquiry/
                                   ├── template-parts/
                                   │   ├── components/      (새로 생성)
                                   │   │   ├── section-header.php
                                   │   │   ├── card.php
                                   │   │   └── button.php
                                   │   ├── header/
                                   │   ├── footer/          (새로 분리)
                                   │   └── modules/
                                   │       └── board/
                                   ├── page-templates/      (새로 통합)
                                   │   ├── page-ceo.php
                                   │   ├── page-history.php
                                   │   └── ...
                                   ├── front-page.php
                                   ├── header.php
                                   ├── footer.php
                                   ├── functions.php        (최소화)
                                   └── style.css
```

### 3.2 functions.php 분리 구조

**권장 functions.php:**
```php
<?php
/**
 * Theme Functions - Main Entry Point
 *
 * 이 파일은 최소한의 로딩만 담당합니다.
 * 실제 기능은 inc/ 하위 파일에서 구현합니다.
 */

// 상수 정의
define('ST_THEME_VERSION', '1.0.0');
define('ST_THEME_DIR', get_template_directory());
define('ST_THEME_URI', get_template_directory_uri());

// 핵심 기능 로드
require_once ST_THEME_DIR . '/inc/core/class-theme-config.php';
require_once ST_THEME_DIR . '/inc/core/class-theme-css-vars.php';
require_once ST_THEME_DIR . '/inc/core/theme-setup.php';
require_once ST_THEME_DIR . '/inc/core/enqueue-scripts.php';

// 헬퍼 함수
require_once ST_THEME_DIR . '/inc/helpers/template-functions.php';

// 모듈 자동 로드
$modules = array('uw-board', 'uw-gallery', 'uw-inquiry');
foreach ($modules as $module) {
    $module_path = ST_THEME_DIR . '/inc/modules/' . $module;
    if (is_dir($module_path)) {
        foreach (glob($module_path . '/class-*.php') as $file) {
            require_once $file;
        }
    }
}
```

### 3.3 파일 이동 가이드

| 현재 위치 | 이동 위치 | 비고 |
|----------|----------|------|
| `inc/variables.php` | `config/theme-config.json` | JSON으로 변환 |
| `inc/uw-board/` | `inc/modules/uw-board/` | 폴더 이동 |
| `assets/css/board/` | `assets/css/modules/board/` | 폴더 이동 |
| `assets/js/board/` | `assets/js/modules/board/` | 폴더 이동 |
| `template-parts/uw-board/` | `template-parts/modules/board/` | 폴더 이동 |
| `page-*.php` (개별) | `page-templates/` | 폴더 통합 |

---

## 4. 자동화 포인트

### 4.1 빌드 자동화 (Gulp)

**신규 파일:** `gulpfile.js`
```javascript
const gulp = require('gulp');
const sass = require('gulp-sass')(require('sass'));
const concat = require('gulp-concat');
const uglify = require('gulp-uglify');
const cleanCSS = require('gulp-clean-css');
const autoprefixer = require('gulp-autoprefixer');
const browserSync = require('browser-sync').create();

// 경로 설정
const paths = {
    styles: {
        src: 'assets/css/**/*.css',
        dest: 'dist/css/'
    },
    scripts: {
        src: 'assets/js/**/*.js',
        dest: 'dist/js/'
    }
};

// CSS 태스크: 병합 + Autoprefixer + 압축
gulp.task('styles', function() {
    return gulp.src([
        'assets/css/base/_reset.css',
        'assets/css/base/_variables.css',
        'assets/css/layout/*.css',
        'assets/css/components/*.css',
        'assets/css/pages/*.css'
    ])
    .pipe(concat('style.min.css'))
    .pipe(autoprefixer({ cascade: false }))
    .pipe(cleanCSS())
    .pipe(gulp.dest(paths.styles.dest))
    .pipe(browserSync.stream());
});

// JS 태스크: 병합 + 압축
gulp.task('scripts', function() {
    return gulp.src([
        'assets/js/core/common.js',
        'assets/js/core/app.js',
        'assets/js/core/main.js'
    ])
    .pipe(concat('app.min.js'))
    .pipe(uglify())
    .pipe(gulp.dest(paths.scripts.dest));
});

// 감시 태스크
gulp.task('watch', function() {
    browserSync.init({
        proxy: 'st-works.local'  // Local 도메인
    });
    gulp.watch(paths.styles.src, gulp.series('styles'));
    gulp.watch(paths.scripts.src, gulp.series('scripts'));
    gulp.watch('**/*.php').on('change', browserSync.reload);
});

// 기본 태스크
gulp.task('default', gulp.series('styles', 'scripts', 'watch'));
gulp.task('build', gulp.series('styles', 'scripts'));
```

**package.json:**
```json
{
  "name": "st-works-theme",
  "version": "1.0.0",
  "scripts": {
    "dev": "gulp",
    "build": "gulp build"
  },
  "devDependencies": {
    "browser-sync": "^2.29.0",
    "gulp": "^4.0.2",
    "gulp-autoprefixer": "^8.0.0",
    "gulp-clean-css": "^4.3.0",
    "gulp-concat": "^2.6.1",
    "gulp-sass": "^5.1.0",
    "gulp-uglify": "^3.0.2",
    "sass": "^1.69.0"
  }
}
```

### 4.2 테마 복제 스크립트

**신규 파일:** `scripts/clone-theme.sh`
```bash
#!/bin/bash

# 사용법: ./clone-theme.sh "새테마명" "/경로/wp-content/themes/"

NEW_THEME_NAME=$1
DEST_PATH=$2
SOURCE_DIR=$(dirname "$(dirname "$(readlink -f "$0")")")

if [ -z "$NEW_THEME_NAME" ] || [ -z "$DEST_PATH" ]; then
    echo "사용법: ./clone-theme.sh '새테마명' '/경로/wp-content/themes/'"
    exit 1
fi

NEW_THEME_DIR="$DEST_PATH/$NEW_THEME_NAME"

# 1. 테마 복사
echo "테마 복사 중..."
cp -R "$SOURCE_DIR" "$NEW_THEME_DIR"

# 2. 텍스트 도메인 변경
echo "텍스트 도메인 변경 중..."
find "$NEW_THEME_DIR" -type f -name "*.php" -exec sed -i '' "s/'st-works'/'$NEW_THEME_NAME'/g" {} +

# 3. style.css 메타 변경
echo "테마 메타 정보 변경 중..."
sed -i '' "s/Theme Name: St-works/Theme Name: $NEW_THEME_NAME/" "$NEW_THEME_DIR/style.css"

# 4. 설정 파일 초기화
echo "설정 파일 초기화 중..."
cat > "$NEW_THEME_DIR/config/theme-config.json" << EOF
{
  "theme": {
    "name": "$NEW_THEME_NAME",
    "version": "1.0.0",
    "text_domain": "$NEW_THEME_NAME"
  },
  "colors": {
    "primary": "#1d8795",
    "secondary": "#0085CA"
  }
}
EOF

# 5. Git 초기화
echo "Git 초기화 중..."
rm -rf "$NEW_THEME_DIR/.git"
cd "$NEW_THEME_DIR" && git init

echo "완료! 새 테마: $NEW_THEME_DIR"
```

### 4.3 이미지 최적화 자동화

**Gulp 태스크 추가:**
```javascript
const imagemin = require('gulp-imagemin');

gulp.task('images', function() {
    return gulp.src('assets/images/**/*')
        .pipe(imagemin([
            imagemin.gifsicle({ interlaced: true }),
            imagemin.mozjpeg({ quality: 75, progressive: true }),
            imagemin.optipng({ optimizationLevel: 5 }),
            imagemin.svgo({
                plugins: [{ removeViewBox: false }]
            })
        ]))
        .pipe(gulp.dest('dist/images'));
});
```

### 4.4 코드 품질 검사 (Linting)

**PHPCS 설정:** `phpcs.xml`
```xml
<?xml version="1.0"?>
<ruleset name="St-works Theme Coding Standards">
    <description>WordPress Coding Standards</description>

    <file>.</file>
    <exclude-pattern>/vendor/</exclude-pattern>
    <exclude-pattern>/node_modules/</exclude-pattern>
    <exclude-pattern>*.min.js</exclude-pattern>
    <exclude-pattern>*.min.css</exclude-pattern>

    <rule ref="WordPress">
        <exclude name="WordPress.Files.FileName.InvalidClassFileName"/>
    </rule>
</ruleset>
```

**ESLint 설정:** `.eslintrc.json`
```json
{
  "env": {
    "browser": true,
    "jquery": true
  },
  "extends": "eslint:recommended",
  "parserOptions": {
    "ecmaVersion": 6
  },
  "rules": {
    "no-unused-vars": "warn",
    "no-console": "off",
    "semi": ["error", "always"]
  }
}
```

---

## 5. 신규 프로젝트 적용 가이드

### 5.1 빠른 시작 체크리스트

```
[ ] 1. 테마 복제
    - scripts/clone-theme.sh "프로젝트명" "/경로/"

[ ] 2. 설정 파일 수정
    - config/theme-config.json (색상, 회사정보)
    - config/sitemap.php (네비게이션)

[ ] 3. 로고 및 이미지 교체
    - assets/images/logo.png
    - assets/images/hero/ (메인 비주얼)

[ ] 4. 폰트 설정 (필요시)
    - functions.php 또는 config 수정

[ ] 5. 페이지 템플릿 선택
    - page-templates/ 에서 필요한 템플릿 활성화

[ ] 6. 모듈 활성화
    - 게시판: uw-board 숏코드 배치
    - 갤러리: uw-gallery 숏코드 배치
    - 문의폼: uw-inquiry 숏코드 배치

[ ] 7. 빌드 실행
    - npm install && npm run build
```

### 5.2 커스터마이징 난이도별 가이드

#### 레벨 1: 색상/로고 변경만 (10분)
```json
// config/theme-config.json 수정
{
  "colors": {
    "primary": "#새로운색상",
    "secondary": "#새로운색상"
  },
  "company": {
    "name": "새 회사명"
  }
}
```

#### 레벨 2: 페이지 구조 변경 (30분)
```php
// config/sitemap.php 수정
return array(
    'about' => array(
        'items' => array(
            // 메뉴 항목 추가/삭제
        )
    )
);
```

#### 레벨 3: 섹션 추가/제거 (1시간)
```php
// front-page.php 또는 page-templates 수정
// template-parts/components 활용
get_template_part('template-parts/components/section-header', null, array(
    'sub_title' => 'New Section',
    'title' => '새로운 섹션'
));
```

#### 레벨 4: 새 모듈 개발 (4시간+)
```
inc/modules/uw-newmodule/
├── class-uw-newmodule-cpt.php
├── class-uw-newmodule-admin.php
└── class-uw-newmodule-engine.php
```

### 5.3 주의사항

1. **WordPress 업데이트**: 자식 테마 사용 권장 (대규모 커스텀 시)
2. **백업**: 수정 전 항상 전체 백업
3. **스테이징**: 운영 서버 적용 전 테스트 환경 검증
4. **캐시**: 수정 후 브라우저 및 서버 캐시 초기화

---

## 부록: 빠른 참조

### A. 컴포넌트 사용 예시

```php
// 섹션 헤더
get_template_part('template-parts/components/section-header', null, array(
    'sub_title' => 'About',
    'title' => '회사 소개'
));

// 카드
get_template_part('template-parts/components/card', null, array(
    'type' => 'business',
    'title' => '제목',
    'desc' => '설명',
    'link' => '/page/',
    'image' => get_theme_file_uri('/assets/images/card.jpg')
));

// 버튼
get_template_part('template-parts/components/button', null, array(
    'text' => '자세히 보기',
    'link' => '/page/',
    'style' => 'primary'
));
```

### B. CSS 변수 참조

```css
/* 색상 */
var(--uw-primary)
var(--uw-secondary)
var(--uw-text)

/* 레이아웃 */
var(--uw-max-width)
var(--uw-header-height)
var(--uw-gutter)

/* 애니메이션 */
var(--uw-transition)
```

### C. 숏코드 참조

```
[uw_board slug="notice" style="style01" per_page="10"]
[uw_gallery id="123"]
[uw_inquiry id="456"]
[latest_posts id="notice" url="/support/notice/" limit="3"]
```

---

*이 가이드는 Phase B (시스템화 및 템플릿 구조화) 결과입니다.*
