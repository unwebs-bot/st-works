<?php

add_action('after_setup_theme', 'st_works_setup');
function st_works_setup()
{
    add_theme_support('post-thumbnails');
    add_theme_support('title-tag');
    register_nav_menu('header-menu', 'Header Menu');
    load_theme_textdomain('st-works', get_template_directory() . '/languages');
}

add_action('wp_enqueue_scripts', 'st_works_enqueue_assets');
function st_works_enqueue_assets()
{
    // Fonts (with preconnect for performance)
    wp_enqueue_style('google-fonts', '//fonts.googleapis.com/css2?family=Play:wght@400;700&family=Poppins:wght@300;400;500;600;700&display=swap');
    wp_enqueue_style('pretendard-font', '//cdn.jsdelivr.net/gh/orioncactus/pretendard/dist/web/static/pretendard.css');

    // Icons
    wp_enqueue_style('fa-style', '//cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css');
    wp_enqueue_style('theme-style', get_stylesheet_uri(), array(), '1.0'); // Root style.css (Metadata)
    wp_enqueue_style('main-style', get_theme_file_uri('/assets/css/style.css'), array('theme-style'), '9.5'); // Real Main Styles
    wp_enqueue_style('app-style', get_theme_file_uri('/assets/css/app.css'), array('main-style'));

    // js
    wp_enqueue_script('bs-script', '//cdn.jsdelivr.net/npm/bootstrap@5.1.1/dist/js/bootstrap.bundle.min.js', NULL, '5.1.1', true);
    wp_enqueue_script('common-script', get_theme_file_uri('/assets/js/common.js'), NULL, '1.0', true);
    wp_enqueue_script('app-script', get_theme_file_uri('/assets/js/app.js'), array('common-script'), '1.0', true);
    wp_enqueue_script('main-script', get_theme_file_uri('/assets/js/main.js'), array('common-script'), '1.0', true);

    // Board engine assets - 조건부 로드 (Board 관련 페이지에서만)
    if (st_works_needs_board_assets()) {
        wp_enqueue_style('xeicon', 'https://cdn.jsdelivr.net/gh/xpressengine/XEIcon@2.3.3/xeicon.min.css');
        wp_enqueue_style('cm-bbs', get_theme_file_uri('/assets/css/board/cm-bbs.css'), array(), '1.1.0');
        wp_enqueue_style('uw-board-skin', get_theme_file_uri('/assets/css/board/board.css'), array('cm-bbs'), '1.1.0');
    }
}

/**
 * Board 관련 에셋이 필요한 페이지인지 확인
 */
function st_works_needs_board_assets()
{
    global $post;

    // Board CPT 페이지
    if (is_singular('uw_board') || is_post_type_archive('uw_board')) {
        return true;
    }

    // 콘텐츠에 Board 숏코드가 있는 경우
    if (is_a($post, 'WP_Post') && (
        has_shortcode($post->post_content, 'uw_board') ||
        has_shortcode($post->post_content, 'latest_posts')
    )) {
        return true;
    }

    // 페이지 템플릿에서 Board 숏코드를 사용하는 경우
    if (is_a($post, 'WP_Post')) {
        $template = get_page_template_slug($post->ID);
        $board_templates = array(
            'support/notice.php',
            'support/news.php',
        );
        if (in_array($template, $board_templates, true)) {
            return true;
        }
    }

    // 프론트페이지 (최신글 표시용)
    if (is_front_page()) {
        return true;
    }

    return false;
}

/**
 * Preconnect 힌트 추가 (폰트 로딩 최적화)
 */
add_action('wp_head', 'st_works_preconnect_hints', 1);
function st_works_preconnect_hints()
{
    echo '<link rel="preconnect" href="https://fonts.googleapis.com">' . "\n";
    echo '<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>' . "\n";
    echo '<link rel="preconnect" href="https://cdn.jsdelivr.net" crossorigin>' . "\n";
}

/**
 * ===========================================================================
 * SEO & Security Enhancements
 * ===========================================================================
 */

/**
 * robots.txt 커스터마이징
 */
add_filter('robots_txt', 'st_works_robots_txt', 10, 2);
function st_works_robots_txt($output, $public)
{
    if (!$public) {
        return $output;
    }

    $custom_rules = "
# STWORKS Custom Robots.txt
User-agent: *
Allow: /

# 관리자 및 시스템 폴더 차단
Disallow: /wp-admin/
Disallow: /wp-includes/
Disallow: /wp-content/plugins/
Disallow: /wp-content/cache/
Disallow: /wp-content/themes/*/inc/

# 검색 결과 및 쿼리 페이지 차단
Disallow: /*?s=
Disallow: /*?p=
Disallow: /search/

# 피드 허용
Allow: /feed/$

# 크롤링 지연 (서버 부하 방지)
Crawl-delay: 1

# Sitemap (SEO 플러그인 설치 시 자동 생성)
Sitemap: " . home_url('/sitemap_index.xml') . "
Sitemap: " . home_url('/sitemap.xml') . "
";

    return $custom_rules;
}

/**
 * 보안 HTTP 헤더 추가
 */
add_action('send_headers', 'st_works_security_headers');
function st_works_security_headers()
{
    if (!is_admin()) {
        // XSS 보호
        header('X-XSS-Protection: 1; mode=block');
        // 콘텐츠 타입 스니핑 방지
        header('X-Content-Type-Options: nosniff');
        // 클릭재킹 방지
        header('X-Frame-Options: SAMEORIGIN');
        // Referrer 정책
        header('Referrer-Policy: strict-origin-when-cross-origin');
    }
}

/**
 * WordPress 버전 정보 제거 (보안)
 */
remove_action('wp_head', 'wp_generator');
add_filter('the_generator', '__return_empty_string');

/**
 * 불필요한 헤더 정보 제거
 */
remove_action('wp_head', 'wlwmanifest_link');
remove_action('wp_head', 'rsd_link');
remove_action('wp_head', 'wp_shortlink_wp_head');

/**
 * REST API 링크 제거 (선택적 보안)
 */
remove_action('wp_head', 'rest_output_link_wp_head');
remove_action('template_redirect', 'rest_output_link_header', 11);

/**
 * Emoji 스크립트 제거 (성능 최적화)
 */
add_action('init', 'st_works_disable_emojis');
function st_works_disable_emojis()
{
    remove_action('wp_head', 'print_emoji_detection_script', 7);
    remove_action('admin_print_scripts', 'print_emoji_detection_script');
    remove_action('wp_print_styles', 'print_emoji_styles');
    remove_action('admin_print_styles', 'print_emoji_styles');
    remove_filter('the_content_feed', 'wp_staticize_emoji');
    remove_filter('comment_text_rss', 'wp_staticize_emoji');
    remove_filter('wp_mail', 'wp_staticize_emoji_for_email');
}

/**
 * ===========================================================================
 * UW Board Engine
 * ===========================================================================
 */
require_once get_template_directory() . '/inc/uw-board/class-uw-board-cpt.php';
require_once get_template_directory() . '/inc/uw-board/class-uw-board-admin.php';
require_once get_template_directory() . '/inc/uw-board/class-uw-board-engine.php';

/**
 * ===========================================================================
 * UW Inquiry Engine (입력폼 시스템)
 * ===========================================================================
 */
require_once get_template_directory() . '/inc/uw-inquiry/class-uw-inquiry-cpt.php';
require_once get_template_directory() . '/inc/uw-inquiry/class-uw-inquiry-admin.php';
require_once get_template_directory() . '/inc/uw-inquiry/class-uw-inquiry-handler.php';

/**
 * ===========================================================================
 * UW Gallery Engine (갤러리 시스템)
 * ===========================================================================
 */
require_once get_template_directory() . '/inc/uw-gallery/class-uw-gallery-cpt.php';
require_once get_template_directory() . '/inc/uw-gallery/class-uw-gallery-admin.php';
require_once get_template_directory() . '/inc/uw-gallery/class-uw-gallery-engine.php';

