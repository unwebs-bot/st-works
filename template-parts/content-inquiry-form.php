<?php
/**
 * Template Part: Inquiry Form Display
 * 
 * 입력폼 표시를 위한 템플릿 파트
 * 
 * Usage: <?php get_template_part('template-parts/content-inquiry', 'form'); ?>
 * 
 * Variables available via set_query_var():
 *   $form_id - (optional) Specific form ID to display
 * 
 * @package St-works
 */

// 특정 폼 ID가 전달되었는지 확인
$form_id = get_query_var('form_id', 0);

// 첫 번째 입력폼 자동 출력 (form_id가 없을 경우)
if (!$form_id) {
  $forms = get_posts(array(
    'post_type' => 'uw_inquiry_form',
    'post_status' => 'publish',
    'posts_per_page' => 1,
    'orderby' => 'date',
    'order' => 'ASC',
  ));

  if (!empty($forms)) {
    $form_id = $forms[0]->ID;
  }
}

if ($form_id) {
  echo do_shortcode('[uw_inquiry_form id="' . $form_id . '"]');
} else {
  // 폼이 없을 경우 안내 메시지
  ?>
  <div class="uw-inquiry-notice">
    <p><strong>입력폼이 아직 생성되지 않았습니다.</strong></p>
    <p>관리자 > 입력폼 메뉴에서 새 입력폼을 생성해주세요.</p>
    <?php if (current_user_can('manage_options')): ?>
      <p><a href="<?php echo admin_url('admin.php?page=uw-inquiry-create'); ?>" class="button">입력폼 생성하기</a></p>
    <?php endif; ?>
  </div>
  <?php
}
