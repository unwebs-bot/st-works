<?php
/**
 * UW Gallery CPT Registration
 * 
 * 갤러리 CPT 등록 및 이미지 사이즈 정의
 * 
 * @package St-works
 * @since 1.0.0
 */

if (!defined('ABSPATH')) {
  exit;
}

class UW_Gallery_CPT
{

  private static $instance = null;

  public static function get_instance()
  {
    if (null === self::$instance) {
      self::$instance = new self();
    }
    return self::$instance;
  }

  private function __construct()
  {
    add_action('init', array($this, 'register_post_type'));
    add_action('after_setup_theme', array($this, 'add_image_sizes'));
  }

  /**
   * 갤러리 CPT 등록
   */
  public function register_post_type()
  {
    $labels = array(
      'name' => '갤러리',
      'singular_name' => '갤러리',
      'menu_name' => '갤러리',
      'add_new' => '새 갤러리 추가',
      'add_new_item' => '새 갤러리 추가',
      'edit_item' => '갤러리 편집',
      'new_item' => '새 갤러리',
      'view_item' => '갤러리 보기',
      'search_items' => '갤러리 검색',
      'not_found' => '갤러리를 찾을 수 없습니다',
      'not_found_in_trash' => '휴지통에 갤러리가 없습니다',
    );

    $args = array(
      'labels' => $labels,
      'public' => false,
      'publicly_queryable' => false,
      'show_ui' => false, // 커스텀 관리 UI 사용
      'show_in_menu' => false,
      'query_var' => false,
      'rewrite' => false,
      'capability_type' => 'post',
      'has_archive' => false,
      'hierarchical' => false,
      'supports' => array('title'),
      'show_in_rest' => false,
    );

    register_post_type('uw_gallery', $args);
  }

  /**
   * 갤러리 전용 이미지 사이즈 등록
   * 서버 부하 방지를 위한 최적화된 사이즈
   */
  public function add_image_sizes()
  {
    // 썸네일 (리스트, 그리드 표시용)
    add_image_size('gallery-thumb', 400, 300, true);

    // 미디움 (라이트박스 미리보기)
    add_image_size('gallery-medium', 800, 600, true);

    // 풀사이즈 (라이트박스 원본)
    add_image_size('gallery-full', 1600, 1200, false);
  }

  /**
   * 갤러리 메타 데이터 기본 구조
   * 
   * 레이아웃 5종: grid, masonry, justified, thumbnail, slide
   * 공통 옵션 + 레이아웃별 특화 옵션
   */
  public static function get_default_meta()
  {
    return array(
      '_uw_gallery_items' => array(),
      '_uw_gallery_layout' => 'grid',

      // ===== 공통 필수 옵션 =====
      '_uw_gallery_gutter' => 15,                // 0-50px
      '_uw_gallery_border_width' => 0,           // 0-5px
      '_uw_gallery_border_radius' => 8,          // 0-30px
      '_uw_gallery_hover_effect' => 'zoom',      // none|zoom|fade|overlay
      '_uw_gallery_hover_overlay_color' => '#000000',
      '_uw_gallery_lightbox_theme' => 'dark',    // dark|light
      '_uw_gallery_lazy_load' => true,

      // ===== 텍스트 옵션 =====
      '_uw_gallery_text_position' => 'bottom',   // bottom|overlay
      '_uw_gallery_show_title' => true,
      '_uw_gallery_show_description' => false,
      '_uw_gallery_text_align' => 'left',        // left|center|right
      '_uw_gallery_overlay_opacity' => 70,       // 0-100%

      // ===== 카테고리 필터 =====
      '_uw_gallery_show_filter' => false,

      // ===== Grid 레이아웃 옵션 =====
      '_uw_gallery_grid_columns_pc' => 4,        // 1-6
      '_uw_gallery_grid_columns_tablet' => 3,    // 1-4
      '_uw_gallery_grid_columns_mobile' => 2,    // 1-3
      '_uw_gallery_grid_ratio' => '1:1',         // original|1:1|4:3|16:9

      // ===== Masonry 레이아웃 옵션 =====
      '_uw_gallery_masonry_column_mode' => 'fixed',  // fixed|auto
      '_uw_gallery_masonry_columns' => 4,            // 1-6
      '_uw_gallery_masonry_sort' => 'order',         // height|order

      // ===== Justified 레이아웃 옵션 =====
      '_uw_gallery_justified_row_height_min' => 200, // 100-400
      '_uw_gallery_justified_row_height_max' => 300, // 200-500
      '_uw_gallery_justified_last_row' => 'left',    // left|justify|hide

      // ===== Thumbnail 레이아웃 옵션 =====
      '_uw_gallery_thumbnail_main_ratio' => '16:9',  // 16:9|4:3|1:1
      '_uw_gallery_thumbnail_position' => 'bottom',  // bottom|right
      '_uw_gallery_thumbnail_transition' => 'click', // click|hover

      // ===== Slide 레이아웃 옵션 =====
      '_uw_gallery_slide_autoplay' => false,
      '_uw_gallery_slide_speed' => 5000,         // 1000-10000ms
      '_uw_gallery_slide_arrows' => true,
      '_uw_gallery_slide_dots' => true,
      '_uw_gallery_slide_loop' => true,

      // ===== 기타 =====
      '_uw_gallery_lightbox' => true,
      '_uw_gallery_custom_css' => '',
      '_uw_gallery_visibility' => 'public',

      // 하위 호환 (deprecated but kept)
      '_uw_gallery_columns' => 4,
      '_uw_gallery_mobile_cols' => 2,
    );
  }

  /**
   * 갤러리 아이템 기본 구조
   *
   * 이미지별 메타데이터: 제목, 설명, 다중 카테고리
   * 미디어 라이브러리와 독립적으로 저장됨
   */
  public static function get_default_item()
  {
    return array(
      'id' => 0,
      'thumb_id' => 0,
      'custom_thumb_id' => 0,  // 비디오 커스텀 썸네일 (수동 업로드)
      'type' => 'image',       // image | video
      'video_url' => '',
      'title' => '',           // 이미지 제목 (갤러리 인스턴스 전용)
      'description' => '',     // 상세 설명 (갤러리 인스턴스 전용)
      'categories' => array(), // 다중 카테고리 배열
      'order' => 0,
    );
  }

  /**
   * 갤러리 설정 가져오기
   */
  public static function get_gallery_settings($gallery_id)
  {
    $defaults = self::get_default_meta();
    $settings = array();

    foreach ($defaults as $key => $default) {
      $value = get_post_meta($gallery_id, $key, true);
      $settings[$key] = ($value !== '') ? $value : $default;
    }

    return $settings;
  }

  /**
   * 갤러리 설정 저장
   */
  public static function save_gallery_settings($gallery_id, $settings)
  {
    foreach ($settings as $key => $value) {
      update_post_meta($gallery_id, $key, $value);
    }
  }
}

// Initialize
UW_Gallery_CPT::get_instance();
