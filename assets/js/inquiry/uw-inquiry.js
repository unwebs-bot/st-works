/**
 * UW Inquiry Frontend JavaScript
 * 
 * 입력폼 프론트엔드 스크립트 (BEM 리팩토링 대응)
 * 
 * @package St-works
 */

(function ($) {
  'use strict';

  $(document).ready(function () {
    initInquiryForms();
    initFileUploads();
    initCaptcha();
  });

  function initInquiryForms() {
    $('.uw-inquiry-form').each(function () {
      var $form = $(this);

      // 입력 중 에러 클래스 제거
      $form.on('input change', '.uw-inquiry-field-input, .uw-inquiry-privacy-checkbox', function () {
        var $input = $(this);
        var $group = $input.closest('.uw-inquiry-field, .uw-inquiry-privacy');

        if ($input.attr('type') === 'checkbox' || $input.attr('type') === 'radio') {
          if ($input.is(':checked')) {
            $group.removeClass('has-error');
            $group.find('.uw-inquiry-field-error').text('');
          }
        } else {
          if ($input.val().trim() !== '') {
            $group.removeClass('has-error');
            $group.find('.uw-inquiry-field-error').text('');
          }
        }
      });

      $form.on('submit', function (e) {
        e.preventDefault();

        // 에러 초기화
        $form.find('.has-error').removeClass('has-error');
        $form.find('.uw-inquiry-field-error').text('');

        // 커스텀 유효성 검사
        var validation = validateForm($form);
        if (!validation.valid) {
          // 첫 번째 에러 메시지 알림
          alert(validation.message);

          // 첫 번째 에러 필드로 포커스
          if (validation.errorField && validation.errorField.length) {
            validation.errorField.focus();

            // 스크롤 이동
            $('html, body').animate({
              scrollTop: validation.errorField.offset().top - 100
            }, 500);
          }
          return;
        }

        submitForm($form);
      });
    });
  }

  // 캡챠 새로고침 핸들러
  function initCaptcha() {
    $(document).on('click', '[data-captcha-refresh]', function (e) {
      e.preventDefault();
      var formId = $(this).data('captcha-refresh');
      var $img = $('#uw_captcha_image_' + formId);
      if ($img.length) {
        // 타임스탬프 업데이트로 이미지 새로고침
        var src = $img.attr('src').split('?')[0];
        $img.attr('src', src + '?t=' + Date.now());
      }
    });
  }

  function validateForm($form) {
    var result = { valid: true, message: '', errorField: null };

    // 필수 필드 검사
    $form.find('[required]').each(function () {
      var $input = $(this);
      var $group = $input.closest('.uw-inquiry-field, .uw-inquiry-privacy, .uw-field-group');
      var label = $group.data('label') || '필수 항목';

      // 개인정보 동의 예외 처리
      if ($group.hasClass('uw-inquiry-privacy')) {
        label = '개인정보 처리방침 동의';
      }

      var isValid = true;
      var message = '';

      if ($input.attr('type') === 'checkbox') {
        if (!$input.is(':checked')) {
          isValid = false;
          message = "'" + label + "' 항목을 체크해주세요.";
        }
      } else if ($input.attr('type') === 'file') {
        if ($input[0].files.length === 0) {
          isValid = false;
          message = "'" + label + "' 파일을 첨부해주세요.";
        }
      } else if ($input.attr('type') === 'radio') {
        // 라디오 버튼 그룹 검사 (name 속성으로 체크)
        var name = $input.attr('name');
        if (!$form.find('input[name="' + name + '"]:checked').length) {
          isValid = false;
          message = "'" + label + "' 항목을 선택해주세요.";
        }
      } else {
        var value = $input.val();
        if (!value || value.trim() === '') {
          isValid = false;
          message = "'" + label + "' 항목을 입력해주세요.";
        }
      }

      if (!isValid) {
        result.valid = false;
        result.message = message;
        result.errorField = $input;

        $group.addClass('has-error');
        $group.find('.uw-inquiry-field-error').text(message);

        return false; // 첫 번째 에러에서 루프 중단
      }
    });

    if (!result.valid) return result;

    // 이메일 형식 검사
    $form.find('input[type="email"]').each(function () {
      var $input = $(this);

      // 값이 있을 때만 검사 (필수가 아닌데 입력한 경우를 위해, 필수는 위에서 걸러짐)
      var value = $input.val();
      if (value && !isValidEmail(value)) {
        var $group = $input.closest('.uw-inquiry-field, .uw-field-group');
        var label = $group.data('label') || '이메일';
        var message = "'" + label + "' 형식이 올바르지 않습니다.\n(예: example@email.com)";

        result.valid = false;
        result.message = message;
        result.errorField = $input;

        $group.addClass('has-error');
        $group.find('.uw-inquiry-field-error').text(message);

        return false;
      }
    });

    return result;
  }

  function isValidEmail(email) {
    var re = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
    return re.test(email);
  }

  /**
   * 파일 업로드 초기화
   */
  function initFileUploads() {
    // 커스텀 파일 버튼 클릭 시 실제 input 트리거
    $(document).on('click', '.uw-inquiry-file-btn', function (e) {
      e.preventDefault();
      $(this).closest('.uw-file-wrapper').find('input[type="file"]').click();
    });

    // 파일 선택 시 미리보기 및 상태 텍스트 업데이트
    $(document).on('change', '.uw-inquiry-field-file-input, .uw-file-wrapper input[type="file"]', function () {
      var $input = $(this);
      var $wrapper = $input.closest('.uw-inquiry-field-file, .uw-file-wrapper');
      var $preview = $wrapper.find('.uw-inquiry-field-file-preview, .uw-file-preview');
      var $fileName = $preview.find('.uw-inquiry-field-file-name, .uw-file-name');
      var $group = $input.closest('.uw-inquiry-field');
      var $fileStatus = $wrapper.find('.uw-inquiry-file-placeholder');
      var $fileSizeInfo = $wrapper.find('.uw-inquiry-file-size');

      if (this.files && this.files.length > 0) {
        var file = this.files[0];
        var name = file.name;
        var size = formatBytes(file.size);

        $fileName.text(name);
        $fileStatus.text(name).css('color', '#333');
        $fileSizeInfo.text(size + ' / 100 MB');

        $preview.addClass('active');
        $preview.show();

        // 커스텀 플레이스홀더 업데이트
        $wrapper.find('.uw-inquiry-file-placeholder').val(name);

        // 에러 제거
        $group.removeClass('has-error');
        $group.find('.uw-inquiry-field-error').text('');
      } else {
        $fileName.text('');
        $preview.removeClass('active');
        $preview.hide();
        $fileStatus.text('파일을 선택해 주세요.').css('color', '#888');
        $fileSizeInfo.text('0 Byte / 100 MB');
      }
    });

    function formatBytes(bytes, decimals = 2) {
      if (bytes === 0) return '0 Byte';
      const k = 1024;
      const dm = decimals < 0 ? 0 : decimals;
      const sizes = ['Bytes', 'KB', 'MB', 'GB', 'TB'];
      const i = Math.floor(Math.log(bytes) / Math.log(k));
      return parseFloat((bytes / Math.pow(k, i)).toFixed(dm)) + ' ' + sizes[i];
    }

    // 파일 삭제 버튼
    $(document).on('click', '.uw-inquiry-field-file-remove, .uw-file-remove', function (e) {
      e.preventDefault();
      var $btn = $(this);
      var $wrapper = $btn.closest('.uw-inquiry-field-file, .uw-file-wrapper');
      var $input = $wrapper.find('input[type="file"]');
      var $preview = $wrapper.find('.uw-inquiry-field-file-preview, .uw-file-preview');
      var $fileName = $preview.find('.uw-inquiry-field-file-name, .uw-file-name');

      // 파일 input 초기화
      $input.val('');
      $preview.removeClass('active');
      $preview.hide();
      $fileName.text('');
      $wrapper.find('.uw-inquiry-file-placeholder').text('파일을 선택해 주세요.').css('color', '#888');
      $wrapper.find('.uw-inquiry-file-size').text('0 Byte / 100 MB');
    });
  }

  function submitForm($form) {
    var formId = $form.data('form-id');
    var $btn = $form.find('.uw-inquiry-submit, .uw-submit-btn');

    // 이미 처리 중이면 중복 제출 방지
    if ($btn.prop('disabled')) {
      return;
    }

    // UI 업데이트 (로딩 상태 - .is-loading 클래스 활용)
    $btn.prop('disabled', true);
    $btn.addClass('is-loading');

    // 폼 데이터 수집
    var formData = new FormData($form[0]);
    formData.append('action', 'uw_inquiry_submit');
    formData.append('nonce', uwInquiry.nonce);
    formData.append('form_id', formId);

    $.ajax({
      url: uwInquiry.ajaxUrl,
      type: 'POST',
      data: formData,
      processData: false,
      contentType: false,
      success: function (response) {
        if (response.success) {
          // 성공
          if (response.data.type === 'redirect' && response.data.redirect) {
            // 페이지 이동
            alert(response.data.message || '문의가 접수되었습니다.');
            window.location.href = response.data.redirect;
          } else {
            // 브라우저 기본 alert 사용
            alert(response.data.message || '문의가 접수되었습니다.');

            // 폼 초기화
            $form[0].reset();

            // 파일 미리보기 초기화
            $form.find('.uw-inquiry-field-file-preview, .uw-file-preview').removeClass('active').hide();
            $form.find('.uw-inquiry-field-file-name, .uw-file-name').text('');

            // 캡챠 이미지 새로고침
            var $captchaRefreshBtn = $form.find('[data-captcha-refresh]');
            if ($captchaRefreshBtn.length) {
              $captchaRefreshBtn.click();
            }

            // 에러 클래스 제거
            $form.find('.has-error').removeClass('has-error');
          }
        } else {
          // 에러 - 브라우저 기본 alert
          alert(response.data.message || response.data || '오류가 발생했습니다.');
        }
      },
      error: function () {
        alert('서버 오류가 발생했습니다. 잠시 후 다시 시도해주세요.');
      },
      complete: function () {
        // UI 복원
        $btn.prop('disabled', false);
        $btn.removeClass('is-loading');
      }
    });
  }

})(jQuery);
