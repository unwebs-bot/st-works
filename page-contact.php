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

<style>
  /* 문의하기 페이지 2열 레이아웃 - 반응형 최적화 */

  /* Reset/Base */
  .sub-page.contact {
    padding-bottom: 100px;
    overflow-x: hidden;
  }

  .sub-page.contact * {
    box-sizing: border-box;
  }

  .page-contact-content {
    width: 100%;
    max-width: 1400px;
    margin: 0 auto;
    padding: 0 20px;
  }

  .contact-layout {
    display: grid;
    grid-template-columns: 380px 1fr;
    gap: 60px;
    align-items: start;
    width: 100%;
  }

  /* Left: Info Section */
  .contact-info {
    position: sticky;
    top: 120px;
    padding: 40px 0;
    width: 100%;
  }

  .contact-info-title {
    font-size: 32px;
    font-weight: 700;
    color: #222;
    margin: 0 0 16px 0;
    line-height: 1.3;
  }

  .contact-info-desc {
    font-size: 15px;
    color: #666;
    line-height: 1.8;
    margin: 0 0 32px 0;
  }

  .contact-info-details {
    display: flex;
    flex-direction: column;
    gap: 16px;
  }

  .contact-info-item {
    display: flex;
    align-items: flex-start;
    gap: 12px;
  }

  .contact-info-item i {
    width: 36px;
    height: 36px;
    display: flex;
    align-items: center;
    justify-content: center;
    background: #f0f7f8;
    color: #1d8795;
    border-radius: 8px;
    font-size: 14px;
    flex-shrink: 0;
  }

  .contact-info-item .label {
    display: block;
    font-size: 12px;
    color: #999;
    margin-bottom: 2px;
  }

  .contact-info-item .value {
    display: block;
    font-size: 14px;
    font-weight: 500;
    color: #333;
  }

  /* Right: Form Section */
  .contact-form-wrapper {
    width: 100%;
    background: #fff;
    border-radius: 12px;
    box-shadow: 0 4px 20px rgba(0, 0, 0, 0.06);
    padding: 40px;
    overflow: hidden;
  }

  /* Form field overrides for contact page */
  .contact-form-wrapper .uw-inquiry-form,
  .contact-form-wrapper .uw-inquiry {
    width: 100%;
    max-width: 100%;
  }

  .contact-form-wrapper .uw-inquiry-field-input,
  .contact-form-wrapper .uw-field-group input,
  .contact-form-wrapper .uw-field-group textarea,
  .contact-form-wrapper .uw-field-group select {
    width: 100%;
    max-width: 100%;
  }

  /* ============================================
     TABLET (1024px 이하)
     ============================================ */
  @media (max-width: 1024px) {
    .page-contact-content {
      padding: 0 24px;
    }

    .contact-layout {
      display: flex;
      flex-direction: column;
      gap: 32px;
    }

    .contact-info {
      position: static;
      padding: 0;
      text-align: center;
      width: 100%;
    }

    .contact-info-title {
      font-size: 28px;
    }

    .contact-info-desc {
      margin-bottom: 24px;
    }

    .contact-info-desc br {
      display: none;
    }

    .contact-info-details {
      flex-direction: row;
      flex-wrap: wrap;
      justify-content: center;
      gap: 24px;
    }

    .contact-info-item {
      flex-direction: column;
      align-items: center;
      text-align: center;
      gap: 8px;
      min-width: 120px;
    }

    .contact-form-wrapper {
      width: 100%;
      padding: 32px 24px;
    }
  }

  /* ============================================
     MOBILE (768px 이하)
     ============================================ */
  @media (max-width: 768px) {
    .sub-page.contact {
      padding-bottom: 60px;
    }

    .page-contact-content {
      padding: 0 16px;
    }

    .contact-layout {
      gap: 24px;
    }

    .contact-info-title {
      font-size: 24px;
      margin-bottom: 12px;
    }

    .contact-info-desc {
      font-size: 14px;
      margin-bottom: 20px;
    }

    .contact-info-details {
      flex-direction: column;
      align-items: center;
      gap: 16px;
    }

    .contact-info-item {
      width: 100%;
      max-width: 280px;
      flex-direction: row;
      text-align: left;
      gap: 12px;
    }

    .contact-info-item i {
      width: 40px;
      height: 40px;
    }

    .contact-form-wrapper {
      padding: 24px 16px;
      border-radius: 8px;
      box-shadow: none;
      border: 1px solid #e5e5e5;
    }

    /* 폼 필드 모바일 최적화 */
    .contact-form-wrapper .uw-inquiry-file-box {
      flex-direction: column;
      align-items: stretch;
      gap: 12px;
    }

    .contact-form-wrapper .uw-inquiry-file-btn {
      width: 100%;
      justify-content: center;
    }

    .contact-form-wrapper .uw-inquiry-file-size {
      text-align: right;
    }
  }

  /* ============================================
     SMALL MOBILE (480px 이하)
     ============================================ */
  @media (max-width: 480px) {
    .page-contact-content {
      padding: 0 12px;
    }

    .contact-info-title {
      font-size: 22px;
    }

    .contact-form-wrapper {
      padding: 20px 14px;
    }

    .contact-form-wrapper .uw-captcha-wrapper {
      flex-direction: column;
      align-items: stretch;
    }

    .contact-form-wrapper .uw-captcha-image {
      width: 100%;
      height: auto;
    }

    .contact-form-wrapper .uw-captcha-refresh {
      width: 100%;
      text-align: center;
    }
  }
</style>

<?php get_footer(); ?>