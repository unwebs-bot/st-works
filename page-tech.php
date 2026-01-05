<?php
/*
Template Name: 핵심기술
*/
get_header(); ?>

<section class="sub-visual business">
  <div class="sub-visual-bg"></div>
  <div class="sub-visual-content">
    <h1 class="sub-page-title">핵심기술</h1>
  </div>

  <div class="sub-lnb">
    <div class="uw-container">
      <ul class="sub-lnb-list">
        <li class="sub-lnb-item active"><a href="<?php echo home_url('/business/tech/'); ?>">핵심기술</a></li>
        <li class="sub-lnb-item"><a href="<?php echo home_url('/business/areas/'); ?>">사업분야</a></li>
        <li class="sub-lnb-item"><a href="<?php echo home_url('/business/cert/'); ?>">특허&인증</a></li>
      </ul>
    </div>
  </div>
</section>

<section class="sub-page tech">
  <div class="uw-section-header scroll-trigger">
    <h2 class="uw-sub-page-title">핵심기술</h2>
    <p class="uw-sub-page-desc">
      스마트 틴팅 기술(PNLC 타입)은 전기 ON/OFF 신호에 따라 유리의 투명도를 자유롭게 조절할 수 있는 첨단 솔루션으로 공간의 프라이버시와 채광을 동시에 제어할 수 있습니다.
    </p>
  </div>
  <div class="uw-tech-container">
    <!-- Row 1: PNLC -->
    <div class="tech-row">
      <div class="tech-info">
        <div style="color: var(--uw-primary); font-weight:700; margin-bottom:12px; font-size: 18px;">01</div>
        <h2 class="tech-title-main">PNLC 타입</h2>
        <p class="tech-desc-sub">
          전기가 흐를 때 어두워지는<br>
          역방향(Reverse) 차광 기술입니다.<br>
          채광 조절이 필요한 로비에 적합합니다.
        </p>
      </div>

      <div class="tech-card-wrapper">
        <div class="tech-card" data-type="pnlc" data-power="off">
          <div class="window-bg"
            style="background-image: url('https://images.unsplash.com/photo-1497366216548-37526070297c?ixlib=rb-1.2.1&auto=format&fit=crop&w=1600&q=80');">
          </div>

          <div class="smart-film-layer">
            <div class="privacy-icon">⚡ 전원 ON : 차광 모드</div>
          </div>

          <div class="control-panel">
            <div class="panel-text">
              <h4>PNLC Control</h4>
              <p>스위치를 눌러 차광 효과를 확인하세요</p>
            </div>
            <div class="switch-group">
              <span class="switch-txt active">OFF (투명)</span>
              <div class="custom-toggle" onclick="toggleCard(this)"></div>
              <span class="switch-txt">ON (차광)</span>
            </div>
          </div>
        </div>
      </div>
    </div>

    <!-- Row 2: PDLC -->
    <div class="tech-row">
      <div class="tech-info">
        <div style="color: var(--uw-primary); font-weight:700; margin-bottom:12px; font-size: 18px;">02</div>
        <h2 class="tech-title-main">PDLC 타입</h2>
        <p class="tech-desc-sub">
          평상시 시선을 차단하고,<br>
          전기가 흐르면 투명해집니다.<br>
          프라이빗한 회의실에 추천합니다.
        </p>
      </div>

      <div class="tech-card-wrapper">
        <div class="tech-card" data-type="pdlc" data-power="off">
          <div class="window-bg"
            style="background-image: url('https://images.unsplash.com/photo-1604328698692-f76ea9498e76?ixlib=rb-1.2.1&auto=format&fit=crop&w=1600&q=80');">
          </div>

          <div class="smart-film-layer">
            <div class="privacy-icon">🔒 전원 OFF : 프라이버시</div>
          </div>

          <div class="control-panel">
            <div class="panel-text">
              <h4>PDLC Control</h4>
              <p>스위치를 눌러 투명도를 확인하세요</p>
            </div>
            <div class="switch-group">
              <span class="switch-txt active">OFF (차광)</span>
              <div class="custom-toggle" onclick="toggleCard(this)"></div>
              <span class="switch-txt">ON (투명)</span>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</section>

<script src="<?php echo get_theme_file_uri('/assets/js/tech.js'); ?>"></script>

<?php get_footer(); ?>