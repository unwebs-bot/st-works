<?php
/*
Template Name: Company Vision
*/
get_header(); ?>

<section class="sub-visual about">
  <div class="sub-visual-bg"
    style="background-image: url('<?php echo get_theme_file_uri('/assets/images/company_building.png'); ?>');"></div>
  <div class="sub-visual-content">
    <h1 class="sub-page-title">비전</h1>
  </div>

  <div class="sub-lnb">
    <div class="uw-container">
      <ul class="sub-lnb-list">
        <li class="sub-lnb-item"><a href="<?php echo home_url('/about/ceo/'); ?>">CEO 인사말</a></li>
        <li class="sub-lnb-item"><a href="<?php echo home_url('/about/history/'); ?>">연혁</a></li>
        <li class="sub-lnb-item active"><a href="<?php echo home_url('/about/vision/'); ?>">비전</a></li>
        <li class="sub-lnb-item"><a href="<?php echo home_url('/about/location/'); ?>">오시는길</a></li>
      </ul>
    </div>
  </div>
</section>

<section class="sub-page vision">
  <div class="uw-section-header scroll-trigger">
    <h2 class="uw-sub-page-title">VISION & MISSION</h2>
    <p class="uw-sub-page-desc">에스티웍스가 추구하는 가치와 미래입니다.</p>
  </div>

  <div class="uw-vision-intro scroll-trigger">
    <p class="uw-vision-statement">
      "스마트 틴팅 기술로<br>
      <span class="uw-text-blue">에너지 절감과 새로운 공간 가치</span>를 창출하여<br>
      지속 가능한 미래에 기여하는 글로벌 리더가 되겠습니다."
    </p>
  </div>

  <div class="uw-vision-cards">
    <!-- Card 1: Trust -->
    <div class="uw-vision-card scroll-trigger">
      <div class="uw-vision-card-image">
        <img src="<?php echo get_theme_file_uri('/assets/images/vision_01.jpg'); ?>" alt="Trust"
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
        <img src="<?php echo get_theme_file_uri('/assets/images/vision_02.jpg'); ?>" alt="Innovation"
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
        <img src="<?php echo get_theme_file_uri('/assets/images/vision_03.jpg'); ?>" alt="Sustainability"
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