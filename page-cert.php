<?php
/*
Template Name: Business Cert
*/
get_header(); ?>

<?php get_template_part('template-parts/sub-visual'); ?>

<section class="sub-page cert">
  <div class="uw-section-header scroll-trigger">
    <h2 class="uw-sub-page-title">특허 & 인증</h2>
    <p class="uw-sub-page-desc">
      건축부터 자동차까지 10건의 등록 특허로 증명된 독자 기술력, 우리는 기술로 새로운 가능성을 만듭니다.
    </p>
  </div>

  <div class="patent-grid">
    <div class="patent-item scroll-trigger" style="transition-delay: 0.1s;" onclick="openLightbox(this)">
      <img src="https://cdn.imweb.me/thumbnail/20250703/d12bd40fafc30.png" alt="특허증" class="patent-img">
    </div>
    <div class="patent-item scroll-trigger" style="transition-delay: 0.2s;" onclick="openLightbox(this)">
      <img src="https://cdn.imweb.me/thumbnail/20250714/2eb98c4c4be11.png" alt="특허증" class="patent-img">
    </div>
    <div class="patent-item scroll-trigger" style="transition-delay: 0.3s;" onclick="openLightbox(this)">
      <img src="https://cdn.imweb.me/thumbnail/20250703/b67c02d8c527c.png" alt="특허증" class="patent-img">
    </div>
    <div class="patent-item scroll-trigger" style="transition-delay: 0.4s;" onclick="openLightbox(this)">
      <img src="https://cdn.imweb.me/thumbnail/20250703/d12bd40fafc30.png" alt="특허증" class="patent-img">
    </div>
    <div class="patent-item scroll-trigger" style="transition-delay: 0.5s;" onclick="openLightbox(this)">
      <img src="https://cdn.imweb.me/thumbnail/20250714/2eb98c4c4be11.png" alt="특허증" class="patent-img">
    </div>
    <div class="patent-item scroll-trigger" style="transition-delay: 0.1s;" onclick="openLightbox(this)">
      <img src="https://cdn.imweb.me/thumbnail/20250703/b67c02d8c527c.png" alt="특허증" class="patent-img">
    </div>
    <div class="patent-item scroll-trigger" style="transition-delay: 0.2s;" onclick="openLightbox(this)">
      <img src="https://cdn.imweb.me/thumbnail/20250703/d12bd40fafc30.png" alt="특허증" class="patent-img">
    </div>
    <div class="patent-item scroll-trigger" style="transition-delay: 0.3s;" onclick="openLightbox(this)">
      <img src="https://cdn.imweb.me/thumbnail/20250714/2eb98c4c4be11.png" alt="특허증" class="patent-img">
    </div>
    <div class="patent-item scroll-trigger" style="transition-delay: 0.4s;" onclick="openLightbox(this)">
      <img src="https://cdn.imweb.me/thumbnail/20250703/b67c02d8c527c.png" alt="특허증" class="patent-img">
    </div>
    <div class="patent-item scroll-trigger" style="transition-delay: 0.5s;" onclick="openLightbox(this)">
      <img src="https://cdn.imweb.me/thumbnail/20250703/d12bd40fafc30.png" alt="특허증" class="patent-img">
    </div>
  </div>
</section>

<section class="section-container validation-section">
  <div class="uw-section-header scroll-trigger" style="text-align: center;">
    <span class="uw-title-en">Performance Validation</span>
    <h2 class="uw-sub-page-title" style="text-align: center;">성능 검증</h2>
    <p class="uw-sub-page-desc">
      한국 정부의 고효율 기자재, 냉방용 창유리 필름 규격 만족
    </p>
  </div>

  <div class="validation-wrapper scroll-trigger">
    <img src="https://cdn.imweb.me/upload/S20250609dd4fe969290a8/bda52938142e6.png" alt="성능검증결과" class="validation-img">
  </div>
</section>

<div class="lightbox-overlay" id="lightbox" onclick="closeLightbox(event)">
  <div class="close-btn">×</div>
  <img src="" alt="Enlarged Patent" class="lightbox-content" id="lightbox-img">
</div>

<script src="<?php echo get_theme_file_uri('/assets/js/cert.js'); ?>"></script>

<?php get_footer(); ?>