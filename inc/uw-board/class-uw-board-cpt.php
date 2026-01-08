<?php
/**
 * UW Board CPT & Taxonomy Registration
 * 
 * @package St-works
 * @since 1.0.0
 */

if (!defined('ABSPATH')) {
  exit;
}

class UW_Board_CPT
{

  /**
   * Instance
   */
  private static $instance = null;

  /**
   * Get instance
   */
  public static function get_instance()
  {
    if (null === self::$instance) {
      self::$instance = new self();
    }
    return self::$instance;
  }

  /**
   * Constructor
   */
  private function __construct()
  {
    add_action('init', array($this, 'register_post_type'));
    add_action('init', array($this, 'register_taxonomy'));
  }

  /**
   * Register CPT: uw_board
   */
  public function register_post_type()
  {
    $labels = array(
      'name' => '게시판 글',
      'singular_name' => '게시판 글',
      'menu_name' => '게시판',
      'add_new' => '새 글 작성',
      'add_new_item' => '새 글 작성',
      'edit_item' => '글 수정',
      'new_item' => '새 글',
      'view_item' => '글 보기',
      'search_items' => '글 검색',
      'not_found' => '글이 없습니다',
      'not_found_in_trash' => '휴지통에 글이 없습니다',
    );

    $args = array(
      'labels' => $labels,
      'public' => false,
      'publicly_queryable' => false,
      'show_ui' => false,  // 커스텀 관리자 UI 사용
      'show_in_menu' => false,
      'query_var' => false,
      'rewrite' => false,
      'capability_type' => 'post',
      'has_archive' => false,
      'hierarchical' => false,
      'supports' => array('title', 'editor', 'author', 'thumbnail'),
      'show_in_rest' => false,
    );

    register_post_type('uw_board', $args);
  }

  /**
   * Register Taxonomy: uw_board_type
   */
  public function register_taxonomy()
  {
    $labels = array(
      'name' => '게시판 유형',
      'singular_name' => '게시판 유형',
      'search_items' => '게시판 검색',
      'all_items' => '모든 게시판',
      'edit_item' => '게시판 수정',
      'update_item' => '게시판 업데이트',
      'add_new_item' => '새 게시판 추가',
      'new_item_name' => '새 게시판 이름',
      'menu_name' => '게시판 유형',
    );

    $args = array(
      'hierarchical' => false,
      'labels' => $labels,
      'show_ui' => false,
      'show_admin_column' => false,
      'query_var' => false,
      'rewrite' => false,
      'show_in_rest' => false,
    );

    register_taxonomy('uw_board_type', 'uw_board', $args);
  }
}

// Initialize
UW_Board_CPT::get_instance();
