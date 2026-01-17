<?php
/**
 * UW Board Style 03 (Thumbnail Card) Template
 * 
 * Vars available:
 * - $slug
 * - $board
 */

$post_id = get_the_ID();
extract($args);
global $wp;
$single_url = add_query_arg(array('view' => 'single', 'id' => $post_id), home_url($wp->request));

// 썸네일
$thumb_url = get_the_post_thumbnail_url($post_id, 'medium');
?>
<li class="uw-card-item">
  <a href="<?php echo esc_url($single_url); ?>" class="uw-card-link">
    <div class="uw-thumb-wrap">
      <?php if ($thumb_url): ?>
        <img src="<?php echo esc_url($thumb_url); ?>" alt="<?php the_title_attribute(); ?>">
      <?php else: ?>
        <div class="uw-thumb-placeholder">
          <i class="xi-image-o"></i>
        </div>
      <?php endif; ?>
    </div>
    <div class="uw-content-wrap">
      <h3 class="uw-title"><?php the_title(); ?></h3>
      <span class="uw-date"><?php echo get_the_date('Y.m.d'); ?></span>
    </div>
  </a>
</li>