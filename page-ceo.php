<?php
/*
Template Name: CEO 인사말
*/
get_header(); ?>

<section class="sub-visual about">
  <div class="sub-visual-bg"
    style="background-image: url('<?php echo get_theme_file_uri('/assets/images/company_building.png'); ?>');"></div>
  <div class="sub-visual-content">
    <h1 class="sub-page-title">CEO 인사말</h1>
  </div>

  <div class="sub-lnb">
    <div class="uw-container">
      <ul class="sub-lnb-list">
        <li class="sub-lnb-item active"><a href="<?php echo home_url('/about/ceo/'); ?>">CEO 인사말</a></li>
        <li class="sub-lnb-item"><a href="<?php echo home_url('/about/history/'); ?>">연혁</a></li>
        <li class="sub-lnb-item"><a href="<?php echo home_url('/about/vision/'); ?>">비전</a></li>
        <li class="sub-lnb-item"><a href="<?php echo home_url('/about/location/'); ?>">오시는길</a></li>
      </ul>
    </div>
  </div>
</section>

<section class="sub-page ceo">
  <div class="uw-section-header scroll-trigger">
    <h2 class="uw-sub-page-title">
      안녕하십니까 <span class="uw-text-blue">에스티웍스</span> 입니다.<br>
      여러분의 방문을 진심으로 환영합니다.
    </h2>
  </div>
  <div class="uw-ceo-container">
    <div class="uw-ceo-content-wrapper">
      <div class="uw-ceo-left">
        <div class="uw-ceo-image-wrapper">
          <img src="<?php echo get_theme_file_uri('/assets/images/ceo_img.jpg'); ?>" alt="CEO Image" class="uw-ceo-img"
            onerror="this.src='https://placehold.co/600x400?text=CEO+Image'">
        </div>
      </div>

      <div class="uw-ceo-right">
        <p class="uw-ceo-desc">
          <strong>주식회사 에스티웍스는 “혁신 기술로 지속 가능한 미래를 설계한다.”</strong><br>라는 신념을 바탕으로 설립된 기술 중심의 스타트업입니다.
        </p>
        <p class="uw-ceo-desc">
          우리는 에너지 절감 기술을 개발하며 기후 문제 해결에 기여하는 것을 중요한 사명으로 삼고 있습니다.<br>
          단순한 해결책을 넘어, 지속 가능한 미래를 위한 기술 혁신에 집중하고 있으며,<br>
          빠르게 변화하는 시장 환경 속에서도 유연하게 대응하고자 합니다.
        </p>
        <p class="uw-ceo-desc">
          깊이 있는 기술력과 창의적인 사고를 바탕으로, 차별화된 가치를 제공하는 것이 우리의 목표입니다.<br>
          앞으로도 끊없는 연구와 도전을 통해 더 나은 세상과 건강한 지구를 만들어 가겠습니다.
        </p>
        <p class="uw-ceo-desc">
          에스티웍스가 걸어가는 지속 가능한 혁신의 여정에 함께해 주시길 바랍니다.
        </p>

        <div class="uw-ceo-signature">
          <span class="uw-sig-text">감사합니다.</span>
          <div class="uw-sig-name-box">
            대표이사 <strong>황훈</strong>
            <img src="<?php echo get_theme_file_uri('/assets/images/sign.png'); ?>" alt="Signature" class="uw-sig-img"
              onerror="this.style.display='none'">
          </div>
        </div>
      </div>
    </div>
  </div>
</section>

<?php get_footer(); ?>