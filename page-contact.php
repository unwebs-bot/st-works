<?php
/**
 * Template Name: 문의하기
 * 
 * 문의 페이지 템플릿
 * 입력폼 숏코드를 사용하여 동적으로 폼 렌더링
 * 
 * @package St-works
 */

get_header(); ?>

<?php get_template_part('template-parts/sub-visual'); ?>

<section class="sub-page contact">
  <div class="page-contact-content">
    <div class="page-contact-header scroll-trigger">
      <h1>문의하기</h1>
      <p>궁금하신 사항이나 문의사항을 남겨주시면 빠른 시일 내에 답변드리겠습니다.</p>
    </div>

    <div class="contact-form-wrapper scroll-trigger">
      <?php
      /**
       * 입력폼 표시
       * 
       * template-parts/content-inquiry-form.php 템플릿 파트 사용
       * 특정 폼 ID를 지정하려면:
       * set_query_var('form_id', 123);
       */
      get_template_part('template-parts/content-inquiry', 'form');
      ?>
    </div>
  </div>
</section>

<style>
  /* 문의하기 페이지 추가 스타일 */
  .sub-page.contact {
    padding-bottom: 100px;
  }

  .contact-form-wrapper {
    margin-top: 40px;
  }

  .uw-inquiry-notice {
    text-align: center;
    padding: 60px 40px;
    background: #f8f9fa;
    border: 1px dashed #ccc;
    border-radius: 8px;
  }

  .uw-inquiry-notice p {
    margin: 10px 0;
    color: #666;
  }

  .uw-inquiry-notice .button {
    display: inline-block;
    margin-top: 15px;
    padding: 10px 25px;
    background: #0073e6;
    color: #fff;
    text-decoration: none;
    border-radius: 4px;
  }

  .uw-inquiry-notice .button:hover {
    background: #005bb5;
  }
</style>

<?php get_footer(); ?>