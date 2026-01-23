<?php
/**
 * Template Name: 문의하기
 *
 * @package St-works
 */

// Contact page CSS 로드
wp_enqueue_style('contact-page', get_theme_file_uri('/assets/css/pages/_contact.css'), array(), '1.0.0');

get_header(); ?>

<?php get_template_part('template-parts/sub-visual'); ?>

<section class="sub-page contact">
  <div class="page-contact-content">
    <div class="contact-layout">
      <!-- Left: Info Section -->
      <div class="contact-info scroll-trigger">
        <h2 class="contact-info-title">문의하기</h2>
        <p class="contact-info-desc">
          궁금하신 사항이나 문의사항을 남겨주시면<br>
          빠른 시일 내에 담당자가 연락드리겠습니다.
        </p>
        <div class="contact-info-details">
          <div class="contact-info-item">
            <i class="fa-solid fa-phone"></i>
            <div>
              <span class="label">전화</span>
              <span class="value">044-867-8820</span>
            </div>
          </div>
          <div class="contact-info-item">
            <i class="fa-solid fa-envelope"></i>
            <div>
              <span class="label">이메일</span>
              <span class="value">info@stworks.co.kr</span>
            </div>
          </div>
          <div class="contact-info-item">
            <i class="fa-solid fa-clock"></i>
            <div>
              <span class="label">운영시간</span>
              <span class="value">평일 09:00 ~ 18:00</span>
            </div>
          </div>
        </div>
      </div>

      <!-- Right: Form Section -->
      <div class="contact-form-wrapper scroll-trigger delay-1">
        <?php
        get_template_part('template-parts/content-inquiry', 'form');
        ?>
      </div>
    </div>
  </div>
</section>

<?php get_footer(); ?>
