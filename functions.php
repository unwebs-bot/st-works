<?php

add_action('after_setup_theme', 'myfourthwp_setup');
function myfourthwp_setup()
{
  add_theme_support('post-thumbnails');
  register_nav_menu('header-menu', 'Header Menu');
  load_theme_textdomain('myfourthwp', get_template_directory() . '/languages');
}

add_action('wp_enqueue_scripts', 'myfourthwp_files');
function myfourthwp_files()
{
  // css
  wp_enqueue_style('google-play-font', '//fonts.googleapis.com/css2?family=Play:wght@400;700&display=swap');
  wp_enqueue_style('google-play-font', '//fonts.googleapis.com/css2?family=Play:wght@400;700&display=swap');
  wp_enqueue_style('fa-style', '//cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css');
  wp_enqueue_style('theme-style', get_stylesheet_uri(), array(), '1.0'); // Root style.css (Metadata)
  wp_enqueue_style('main-style', get_theme_file_uri('/assets/css/style.css'), array('theme-style'), '9.5'); // Real Main Styles
  wp_enqueue_style('app-style', get_theme_file_uri('/assets/css/app.css'), array('main-style'));


  // Board engine assets (Global)
  wp_enqueue_style('xeicon', 'https://cdn.jsdelivr.net/gh/xpressengine/XEIcon@2.3.3/xeicon.min.css');
  wp_enqueue_style('cm-bbs', get_theme_file_uri('/assets/css/board/cm-bbs.css'), array(), '1.1.0');
  wp_enqueue_style('uw-board-skin', get_theme_file_uri('/assets/css/board/board.css'), array('cm-bbs'), '1.1.0');

  // js
  wp_enqueue_script('bs-script', '//cdn.jsdelivr.net/npm/bootstrap@5.1.1/dist/js/bootstrap.bundle.min.js', NULL, '5.1.1', true);
  wp_enqueue_script('common-script', get_theme_file_uri('/assets/js/common.js'), NULL, '1.0', true);
  wp_enqueue_script('app-script', get_theme_file_uri('/assets/js/app.js'), array('common-script'), '1.0', true);
  wp_enqueue_script('main-script', get_theme_file_uri('/assets/js/main.js'), array('common-script'), '1.0', true);
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