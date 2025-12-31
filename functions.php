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
  wp_enqueue_style('reset-style', get_template_directory_uri() . '/assets/css/reset.css', array(), '9.0');
  wp_enqueue_style('fa-style', '//cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css');
  wp_enqueue_style('theme-style', get_stylesheet_uri(), array('reset-style'), '1.0'); // Root style.css (Metadata)
  wp_enqueue_style('main-style', get_theme_file_uri('/assets/css/style.css'), array('theme-style'), '9.5'); // Real Main Styles
  wp_enqueue_style('components-style', get_theme_file_uri('/assets/css/components.css'), array('main-style'), '1.0');
  wp_enqueue_style('app-style', get_theme_file_uri('/assets/css/app.css'), array('components-style'));
  wp_enqueue_style('subpage-style', get_theme_file_uri('/assets/css/subpage.css'), array('app-style'));

  // js
  wp_enqueue_script('bs-script', '//cdn.jsdelivr.net/npm/bootstrap@5.1.1/dist/js/bootstrap.bundle.min.js', NULL, '5.1.1', true);
  wp_enqueue_script('common-script', get_theme_file_uri('/assets/js/common.js'), NULL, '1.0', true);
  wp_enqueue_script('app-script', get_theme_file_uri('/assets/js/app.js'), array('common-script'), '1.0', true);
  wp_enqueue_script('main-script', get_theme_file_uri('/assets/js/main.js'), array('common-script'), '1.0', true);
}