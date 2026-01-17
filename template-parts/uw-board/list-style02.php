<?php
/**
 * UW Board Style 02 (Minimal Card) Template
 * 
 * Vars available:
 * - $slug
 * - $board
 */

$post_id = get_the_ID();
extract($args);
global $wp;
$single_url = add_query_arg(array('view' => 'single', 'id' => $post_id), home_url($wp->request));

// 카테고리: 설정된 이름 또는 기본값
$category = get_post_meta($post_id, '_uw_category', true);
if (!$category)
  $category = isset($board['label']) ? $board['label'] : '공지사항';
?>
<li class="uw-card-item">
  <a href="<?php echo esc_url($single_url); ?>">
    <span class="uw-category">
      <?php echo esc_html($category); ?>
    </span>
    <strong class="uw-title">
      <?php the_title(); ?>
    </strong>
    <span class="uw-date">
      <?php echo get_the_date('Y-m-d'); ?>
    </span>
  </a>
</li>