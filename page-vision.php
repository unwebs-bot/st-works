<?php
/*
Template Name: Company Vision
*/
get_header(); ?>

<?php get_template_part('template-parts/sub-visual'); ?>

<section class="sub-page vision">
  <div class="uw-section-header scroll-trigger">
    <h2 class="uw-sub-page-title">VISION & MISSION</h2>
    <p class="uw-sub-page-desc">스마트 틴팅 기술로 에너지 절감과 새로운 공간 가치를 창출하여<br>
      지속 가능한 미래에 기여하는 글로벌 리더가 되겠습니다.</p>
  </div>


  <div class="uw-vision-cards">
    <!-- Card 1: Trust -->
    <div class="uw-vision-card scroll-trigger">
      <div class="uw-vision-card-image">
        <img src="<?php echo get_theme_file_uri('/assets/images/vision_01.png'); ?>" alt="Trust"
          onerror="this.src='https://placehold.co/450x450?text=Trust'">
        <div class="uw-vision-card-overlay">
          <span class="uw-vision-card-label">Core Value 01</span>
          <h3 class="uw-vision-card-title">신뢰와 책임</h3>
        </div>
      </div>
    </div>

    <!-- Card 2: Innovation -->
    <div class="uw-vision-card scroll-trigger delay-200">
      <div class="uw-vision-card-image">
        <img src="<?php echo get_theme_file_uri('/assets/images/vision_02.png'); ?>" alt="Innovation"
          onerror="this.src='https://placehold.co/450x450?text=Innovation'">
        <div class="uw-vision-card-overlay">
          <span class="uw-vision-card-label">Core Value 02</span>
          <h3 class="uw-vision-card-title">도전과 혁신</h3>
        </div>
      </div>
    </div>

    <!-- Card 3: Sustainability -->
    <div class="uw-vision-card scroll-trigger delay-400">
      <div class="uw-vision-card-image">
        <img src="<?php echo get_theme_file_uri('/assets/images/vision_03.png'); ?>" alt="Sustainability"
          onerror="this.src='https://placehold.co/450x450?text=Sustainability'">
        <div class="uw-vision-card-overlay">
          <span class="uw-vision-card-label">Core Value 03</span>
          <h3 class="uw-vision-card-title">지속 가능성</h3>
        </div>
      </div>
    </div>
  </div>
</section>

<?php get_footer(); ?>