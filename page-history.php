<?php
/*
Template Name: Company History
*/
get_header(); ?>

<section class="sub-visual about">
  <div class="sub-visual-bg"
    style="background-image: url('<?php echo get_theme_file_uri('/assets/images/company_building.png'); ?>');"></div>
  <div class="sub-visual-content">
    <h1 class="sub-page-title">연혁</h1>
  </div>

  <div class="sub-lnb">
    <div class="uw-container">
      <ul class="sub-lnb-list">
        <li class="sub-lnb-item"><a href="<?php echo home_url('/about/ceo/'); ?>">CEO 인사말</a></li>
        <li class="sub-lnb-item active"><a href="<?php echo home_url('/about/history/'); ?>">연혁</a></li>
        <li class="sub-lnb-item"><a href="<?php echo home_url('/about/vision/'); ?>">비전</a></li>
        <li class="sub-lnb-item"><a href="<?php echo home_url('/about/location/'); ?>">오시는길</a></li>
      </ul>
    </div>
  </div>
</section>

<section class="sub-page history">
  <div class="uw-section-header scroll-trigger">
    <h2 class="uw-sub-page-title">에스티웍스가<br>걸어온 길입니다.</h2>
    <p class="uw-sub-page-desc">끊임없는 도전과 혁신으로 성장해온 에스티웍스의 발자취를 소개합니다.</p>
  </div>

  <div class="uw-history-container">
    <div class="uw-history-timeline">

      <!-- 2024 -->
      <div class="uw-history-decade scroll-trigger">
        <div class="uw-history-decade-title">2024</div>
        <div class="uw-history-years">

          <div class="uw-history-year-group">
            <div class="uw-history-year-number">2024. 11</div>
            <ul class="uw-history-events">
              <li class="uw-history-event">
                <p class="uw-history-desc">중소벤처기업진흥공단 '성장공유형자금' 투자 유치 (전환사채)</p>
              </li>
            </ul>
          </div>

          <div class="uw-history-year-group">
            <div class="uw-history-year-number">2024. 07</div>
            <ul class="uw-history-events">
              <li class="uw-history-event">
                <p class="uw-history-desc">신용보증기금 "리틀펭귄" 선정 / Start-up NEST 16기 선정</p>
              </li>
            </ul>
          </div>

          <div class="uw-history-year-group">
            <div class="uw-history-year-number">2024. 06</div>
            <ul class="uw-history-events">
              <li class="uw-history-event">
                <p class="uw-history-desc">중소벤처기업부 TIPS(팁스) 선정</p>
              </li>
            </ul>
          </div>

          <div class="uw-history-year-group">
            <div class="uw-history-year-number">2024. 03</div>
            <ul class="uw-history-events">
              <li class="uw-history-event">
                <p class="uw-history-desc">기술보증기금 벤처캠프 14기 선정</p>
              </li>
            </ul>
          </div>

          <div class="uw-history-year-group">
            <div class="uw-history-year-number">2024. 01</div>
            <ul class="uw-history-events">
              <li class="uw-history-event">
                <p class="uw-history-desc">기업부설연구소 인증</p>
              </li>
            </ul>
          </div>
        </div>
      </div>

      <!-- 2023 -->
      <div class="uw-history-decade scroll-trigger">
        <div class="uw-history-decade-title">2023</div>
        <div class="uw-history-years">
          <div class="uw-history-year-group">
            <div class="uw-history-year-number">2023. 12</div>
            <ul class="uw-history-events">
              <li class="uw-history-event">
                <p class="uw-history-desc">창업성장기술개발사업(디딤돌) 선정</p>
              </li>
            </ul>
          </div>

          <div class="uw-history-year-group">
            <div class="uw-history-year-number">2023. 11</div>
            <ul class="uw-history-events">
              <li class="uw-history-event">
                <p class="uw-history-desc">시드 투자 유치 (충북창조경제혁신센터)</p>
              </li>
            </ul>
          </div>

          <div class="uw-history-year-group">
            <div class="uw-history-year-number">2023. 09</div>
            <ul class="uw-history-events">
              <li class="uw-history-event">
                <p class="uw-history-desc">벤처기업 인증 (혁신성장유형)</p>
              </li>
            </ul>
          </div>

          <div class="uw-history-year-group">
            <div class="uw-history-year-number">2023. 05</div>
            <ul class="uw-history-events">
              <li class="uw-history-event">
                <p class="uw-history-desc">법인 설립 ((주)에스티웍스)</p>
              </li>
            </ul>
          </div>
        </div>
      </div>

    </div>
  </div>
</section>

<?php get_footer(); ?>