<?php
/**
 * UW Inquiry CPT & Taxonomy Registration
 * 
 * 입력폼(Input Form) 커스텀 포스트 타입 및 택소노미 등록
 * 
 * @package St-works
 * @since 1.0.0
 */

if (!defined('ABSPATH')) {
  exit;
}

class UW_Inquiry_CPT
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
    add_action('init', array($this, 'register_post_types'));
    add_action('init', array($this, 'register_taxonomy'));
  }

  /**
   * Register CPT: uw_inquiry_form (입력폼 설정)
   * Register CPT: uw_inquiry_entry (문의 접수 내역)
   */
  public function register_post_types()
  {
    // CPT 1: 입력폼 (Form Configuration)
    $form_labels = array(
      'name' => '입력폼',
      'singular_name' => '입력폼',
      'menu_name' => '입력폼',
      'add_new' => '새 폼 추가',
      'add_new_item' => '새 입력폼 추가',
      'edit_item' => '입력폼 수정',
      'new_item' => '새 입력폼',
      'view_item' => '입력폼 보기',
      'search_items' => '입력폼 검색',
      'not_found' => '입력폼이 없습니다',
      'not_found_in_trash' => '휴지통에 입력폼이 없습니다',
    );

    $form_args = array(
      'labels' => $form_labels,
      'public' => false,
      'publicly_queryable' => false,
      'show_ui' => false, // 커스텀 관리자 UI 사용
      'show_in_menu' => false,
      'query_var' => false,
      'rewrite' => false,
      'capability_type' => 'post',
      'has_archive' => false,
      'hierarchical' => false,
      'supports' => array('title'),
      'show_in_rest' => false,
    );

    register_post_type('uw_inquiry_form', $form_args);

    // CPT 2: 문의 내역 (Inquiry Entries)
    $entry_labels = array(
      'name' => '문의 내역',
      'singular_name' => '문의 내역',
      'menu_name' => '문의 내역',
      'add_new' => '새 문의',
      'add_new_item' => '새 문의 추가',
      'edit_item' => '문의 수정',
      'new_item' => '새 문의',
      'view_item' => '문의 보기',
      'search_items' => '문의 검색',
      'not_found' => '문의가 없습니다',
      'not_found_in_trash' => '휴지통에 문의가 없습니다',
    );

    $entry_args = array(
      'labels' => $entry_labels,
      'public' => false,
      'publicly_queryable' => false,
      'show_ui' => false,
      'show_in_menu' => false,
      'query_var' => false,
      'rewrite' => false,
      'capability_type' => 'post',
      'has_archive' => false,
      'hierarchical' => false,
      'supports' => array('title'),
      'show_in_rest' => false,
    );

    register_post_type('uw_inquiry_entry', $entry_args);
  }

  /**
   * Register Taxonomy: uw_inquiry_type (폼 유형 분류)
   */
  public function register_taxonomy()
  {
    $labels = array(
      'name' => '폼 유형',
      'singular_name' => '폼 유형',
      'search_items' => '폼 유형 검색',
      'all_items' => '모든 폼 유형',
      'edit_item' => '폼 유형 수정',
      'update_item' => '폼 유형 업데이트',
      'add_new_item' => '새 폼 유형 추가',
      'new_item_name' => '새 폼 유형 이름',
      'menu_name' => '폼 유형',
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

    register_taxonomy('uw_inquiry_type', array('uw_inquiry_form', 'uw_inquiry_entry'), $args);
  }

  /**
   * Get default form fields
   * 
   * @return array Default field configuration
   */
  public static function get_default_fields()
  {
    return array(
      array(
        'id' => 'field_inquiry_type',
        'type' => 'select',
        'label' => '문의 유형',
        'required' => true,
        'placeholder' => '문의 유형을 선택하세요',
        'options' => "제품 문의\n기술 지원\n견적 요청\n파트너십 제안\n채용 문의\n기타",
        'help_text' => '',
        'enabled' => true,
        'order' => 1,
      ),
      array(
        'id' => 'field_company',
        'type' => 'text',
        'label' => '회사/기관명',
        'required' => false,
        'placeholder' => '회사 또는 기관명을 입력하세요',
        'help_text' => '',
        'enabled' => true,
        'order' => 2,
      ),
      array(
        'id' => 'field_department',
        'type' => 'text',
        'label' => '부서명',
        'required' => false,
        'placeholder' => '부서명을 입력하세요',
        'help_text' => '',
        'enabled' => true,
        'order' => 3,
      ),
      array(
        'id' => 'field_name',
        'type' => 'text',
        'label' => '담당자명',
        'required' => true,
        'placeholder' => '담당자 성함을 입력하세요',
        'help_text' => '',
        'enabled' => true,
        'order' => 4,
      ),
      array(
        'id' => 'field_phone',
        'type' => 'tel',
        'label' => '연락처',
        'required' => true,
        'placeholder' => '010-0000-0000',
        'help_text' => '',
        'enabled' => true,
        'order' => 5,
      ),
      array(
        'id' => 'field_email',
        'type' => 'email',
        'label' => '이메일',
        'required' => true,
        'placeholder' => 'example@company.com',
        'help_text' => '회신받으실 이메일 주소를 정확히 입력해주세요.',
        'enabled' => true,
        'order' => 6,
      ),
      array(
        'id' => 'field_subject',
        'type' => 'text',
        'label' => '문의 제목',
        'required' => true,
        'placeholder' => '문의 제목을 입력하세요',
        'help_text' => '',
        'enabled' => true,
        'order' => 7,
      ),
      array(
        'id' => 'field_message',
        'type' => 'textarea',
        'label' => '문의 내용',
        'required' => true,
        'placeholder' => '문의하실 내용을 상세히 작성해 주세요.',
        'help_text' => '',
        'enabled' => true,
        'order' => 8,
      ),
      array(
        'id' => 'field_attachment',
        'type' => 'file',
        'label' => '첨부파일',
        'required' => false,
        'placeholder' => '관련 자료가 있다면 첨부해 주세요. (최대 10MB, PDF/DOC/XLS/이미지)',
        'help_text' => '',
        'enabled' => true,
        'order' => 9,
      ),
    );
  }

  /**
   * Get available field types
   * 
   * @return array Field types with labels
   */
  public static function get_field_types()
  {
    return array(
      'text' => '단답형 (Text)',
      'email' => '이메일 (Email)',
      'tel' => '연락처 (Phone)',
      'textarea' => '장답형 (Textarea)',
      'select' => '드롭다운 (Select)',
      'checkbox' => '체크박스 (Checkbox)',
      'radio' => '라디오 (Radio)',
      'file' => '파일첨부 (File)',
      'date' => '날짜선택 (Date)',
    );
  }
}

// Initialize
UW_Inquiry_CPT::get_instance();
