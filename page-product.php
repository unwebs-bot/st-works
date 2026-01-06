<?php
/*
Template Name: Product
*/
get_header(); ?>

<?php get_template_part('template-parts/sub-visual'); ?>

<!-- ==========================================================================
     Product Section: 제품소개
     ========================================================================== -->
<section class="sub-page product">
  <div class="uw-section-header scroll-trigger">
    <h2 class="uw-sub-page-title">제품소개</h2>
    <p class="uw-sub-page-desc">
      스마트 틴팅 필름은 에너지 절감과 프라이버시 보호를 동시에 실현하는 차세대 솔루션입니다.<br>
      건축용과 자동차용 맞춤 제품을 만나보세요.
    </p>
  </div>

  <div class="uw-product-container">
    <div class="uw-product-grid">

      <!-- Card 1: 일반 타입 -->
      <div class="uw-product-card scroll-trigger">
        <div class="uw-product-image-wrap">
          <img src="<?php echo get_theme_file_uri('/assets/images/Product_01.jpg'); ?>" alt=" 일반 스마트 틴팅 필름"
            class="uw-product-img" onerror="this.src='https://placehold.co/800x500/222/fff?text=General+Type'">
        </div>
        <div class="uw-product-content">
          <h3 class="uw-product-title">일반 타입</h3>
          <p class="uw-product-card-desc">
            인테리어용에 가장 적합한 스탠다드 모델입니다.<br>
            높은 자외선 차단율(99% 이상)과 즉각적인 반응 속도를 제공합니다.
          </p>
          <div class="uw-product-specs">
            <ul class="uw-specs-list">
              <li>
                <span class="uw-spec-label">PNLC 스펙</span>
                <span class="uw-spec-value">저전력(3W/㎡), 투과율 40~82%</span>
              </li>
              <li>
                <span class="uw-spec-label">핵심 특징</span>
                <span class="uw-spec-value">뛰어난 개방감, 선명한 불투명 효과</span>
              </li>
            </ul>
          </div>
        </div>
      </div>

      <!-- Card 2: 고효율 타입 -->
      <div class="uw-product-card scroll-trigger delay-200">
        <div class="uw-product-image-wrap">
          <img src="<?php echo get_theme_file_uri('/assets/images/product_02.jpg'); ?>" alt="고효율 스마트 틴팅 필름"
            class="uw-product-img" onerror="this.src='https://placehold.co/800x500/222/fff?text=High+Efficiency'">
        </div>
        <div class="uw-product-content">
          <h3 class="uw-product-title">고효율 타입 <span class="uw-product-badge">Premium</span></h3>
          <p class="uw-product-card-desc">
            냉방용 창호 및 차량 유리에 최적화된 솔루션입니다.<br>
            낮은 전력 소비와 압도적인 <span class="uw-text-primary">자외선 차단율(99% 이상)</span>을 경험하세요.
          </p>
          <div class="uw-product-specs">
            <ul class="uw-specs-list">
              <li>
                <span class="uw-spec-label">PNLC 스펙</span>
                <span class="uw-spec-value">투과율 1~60%, SHGC 0.1~0.5</span>
              </li>
              <li>
                <span class="uw-spec-label">작동 환경</span>
                <span class="uw-spec-value">1초 이내 전환, -20~60℃ 안정 작동</span>
              </li>
            </ul>
          </div>
        </div>
      </div>

    </div>
  </div>
</section>

<!-- ==========================================================================
     Specs Table Section
     ========================================================================== -->
<section class="uw-table-section">
  <div class="uw-container">
    <div class="uw-mobile-hint">← 표를 좌우로 밀어서 확인하세요 →</div>

    <div class="uw-table-wrapper">
      <table class="uw-full-table">
        <thead>
          <tr>
            <th rowspan="2" class="uw-main-th">항목</th>
            <th rowspan="2" class="uw-main-th">단위</th>
            <th colspan="2" class="uw-main-th">고효율 스마트 틴팅 필름</th>
            <th colspan="2" class="uw-main-th">스마트 틴팅 필름 (일반)</th>
          </tr>
          <tr>
            <th>PNLC 타입</th>
            <th>PDLC 타입</th>
            <th>PNLC 타입</th>
            <th>PDLC 타입</th>
          </tr>
        </thead>
        <tbody>
          <tr>
            <td rowspan="2" class="uw-col-item">가시광선 투과율</td>
            <td class="uw-col-unit">Power OFF (투명, %)</td>
            <td class="uw-val">1~60</td>
            <td class="uw-val">-</td>
            <td class="uw-val">40~82</td>
            <td class="uw-val">-</td>
          </tr>
          <tr>
            <td class="uw-col-unit">Power ON (투명, %)</td>
            <td class="uw-val">-</td>
            <td class="uw-val">1~60</td>
            <td class="uw-val">-</td>
            <td class="uw-val">40~82</td>
          </tr>

          <tr>
            <td class="uw-col-item">자외선 차단율</td>
            <td class="uw-col-unit">Power OFF/ON (%)</td>
            <td class="uw-val">99 이상</td>
            <td class="uw-val">99 이상</td>
            <td class="uw-val">99 이상</td>
            <td class="uw-val">99 이상</td>
          </tr>

          <tr>
            <td class="uw-col-item">SHGC (태양열 취득률)</td>
            <td class="uw-col-unit">-</td>
            <td class="uw-val">0.1~0.5</td>
            <td class="uw-val">0.1~0.5</td>
            <td class="uw-val">0.5~0.95</td>
            <td class="uw-val">0.5~0.95</td>
          </tr>

          <tr>
            <td class="uw-col-item">구동 전압</td>
            <td class="uw-col-unit">전압 (V)</td>
            <td class="uw-val">48~110</td>
            <td class="uw-val">48~110</td>
            <td class="uw-val">48~110</td>
            <td class="uw-val">48~110</td>
          </tr>

          <tr>
            <td class="uw-col-item">구동 온도</td>
            <td class="uw-col-unit">℃</td>
            <td class="uw-val">-20~60</td>
            <td class="uw-val">-20~60</td>
            <td class="uw-val">-20~60</td>
            <td class="uw-val">-20~60</td>
          </tr>

          <tr>
            <td class="uw-col-item">구동 속도</td>
            <td class="uw-col-unit">초</td>
            <td class="uw-val">1초 이내</td>
            <td class="uw-val">1초 이내</td>
            <td class="uw-val">1초 이내</td>
            <td class="uw-val">1초 이내</td>
          </tr>

          <tr>
            <td class="uw-col-item">소비 전력</td>
            <td class="uw-col-unit">W / ㎡</td>
            <td class="uw-val">3</td>
            <td class="uw-val">5</td>
            <td class="uw-val">3</td>
            <td class="uw-val">5</td>
          </tr>

          <tr>
            <td class="uw-col-item">표면 경도</td>
            <td class="uw-col-unit">연필 경도</td>
            <td class="uw-val">2H</td>
            <td class="uw-val">2H</td>
            <td class="uw-val">2H</td>
            <td class="uw-val">2H</td>
          </tr>

        </tbody>
      </table>
    </div>
  </div>
</section>

<?php get_footer(); ?>