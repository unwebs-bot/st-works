/**
 * UW Gallery Admin JS (Vanilla JS)
 * 
 * 관리자 기능 스크립트 - jQuery 의존성 제거
 * 
 * @package St-works
 * @since 2.0.0
 */

(function () {
  'use strict';

  /**
   * 토스트 알림 유틸리티
   */
  const UWToast = {
    container: null,

    init: function () {
      if (!this.container) {
        this.container = document.createElement('div');
        this.container.className = 'uw-toast-container';
        document.body.appendChild(this.container);
      }
    },

    show: function (message, type = 'info', duration = 3000) {
      this.init();

      const toast = document.createElement('div');
      toast.className = `uw-toast uw-toast--${type}`;

      const icons = {
        success: '✓',
        error: '✕',
        warning: '⚠',
        info: 'ℹ'
      };

      toast.innerHTML = `
        <span class="uw-toast-icon">${icons[type] || icons.info}</span>
        <span class="uw-toast-message">${message}</span>
        <button type="button" class="uw-toast-close" aria-label="닫기">×</button>
      `;

      const closeBtn = toast.querySelector('.uw-toast-close');
      closeBtn.addEventListener('click', () => this.hide(toast));

      this.container.appendChild(toast);

      if (duration > 0) {
        setTimeout(() => this.hide(toast), duration);
      }

      return toast;
    },

    hide: function (toast) {
      if (!toast || !toast.parentNode) return;
      toast.classList.add('is-hiding');
      setTimeout(() => {
        if (toast.parentNode) {
          toast.parentNode.removeChild(toast);
        }
      }, 300);
    },

    success: function (message, duration) {
      return this.show(message, 'success', duration);
    },

    error: function (message, duration) {
      return this.show(message, 'error', duration);
    },

    warning: function (message, duration) {
      return this.show(message, 'warning', duration);
    },

    info: function (message, duration) {
      return this.show(message, 'info', duration);
    }
  };

  const UWGalleryAdmin = {
    itemIndex: 0,
    draggedItem: null,
    customThumbFrame: null,

    init: function () {
      const itemsList = document.getElementById('uw-gallery-items');
      if (itemsList) {
        this.itemIndex = itemsList.querySelectorAll('.uw-gallery-item').length;
      }
      this.bindEvents();
    },

    bindEvents: function () {
      // 이미지 추가
      const addImagesBtn = document.getElementById('uw-add-images');
      if (addImagesBtn) {
        addImagesBtn.addEventListener('click', this.openMediaLibrary.bind(this));
      }

      // 비디오 추가
      const addVideoBtn = document.getElementById('uw-add-video');
      if (addVideoBtn) {
        addVideoBtn.addEventListener('click', this.openVideoModal.bind(this));
      }

      const videoConfirmBtn = document.getElementById('uw-video-confirm');
      if (videoConfirmBtn) {
        videoConfirmBtn.addEventListener('click', this.confirmVideo.bind(this));
      }

      const videoCancelBtn = document.getElementById('uw-video-cancel');
      if (videoCancelBtn) {
        videoCancelBtn.addEventListener('click', this.closeVideoModal.bind(this));
      }

      // 이미지 편집 모달
      const editItemSaveBtn = document.getElementById('uw-edit-item-save');
      if (editItemSaveBtn) {
        editItemSaveBtn.addEventListener('click', this.saveItemEdit.bind(this));
      }

      const editItemCancelBtn = document.getElementById('uw-edit-item-cancel');
      if (editItemCancelBtn) {
        editItemCancelBtn.addEventListener('click', this.closeItemEditModal.bind(this));
      }

      // 커스텀 썸네일 업로드
      const uploadCustomThumbBtn = document.getElementById('uw-upload-custom-thumb');
      if (uploadCustomThumbBtn) {
        uploadCustomThumbBtn.addEventListener('click', this.uploadCustomThumbnail.bind(this));
      }

      // 커스텀 썸네일 제거
      const removeCustomThumbBtn = document.getElementById('uw-remove-custom-thumb');
      if (removeCustomThumbBtn) {
        removeCustomThumbBtn.addEventListener('click', this.removeCustomThumbnail.bind(this));
      }

      // 이벤트 위임 (아이템 제거, 편집)
      document.addEventListener('click', (e) => {
        if (e.target.closest('.uw-item-remove')) {
          e.preventDefault();
          this.removeItem(e.target.closest('.uw-gallery-item'));
        }
        if (e.target.closest('.uw-item-edit')) {
          e.preventDefault();
          this.editItem(e.target.closest('.uw-gallery-item'));
        }
        if (e.target.closest('.uw-copy-shortcode')) {
          e.preventDefault();
          this.copyShortcode(e.target.closest('.uw-copy-shortcode'));
        }
        if (e.target.closest('.uw-gallery-delete')) {
          e.preventDefault();
          this.deleteGallery(e.target.closest('.uw-gallery-delete'));
        }
        if (e.target.closest('.uw-gallery-delete-single')) {
          e.preventDefault();
          this.deleteGallery(e.target.closest('.uw-gallery-delete-single'));
        }
        if (e.target.closest('.uw-layout-card')) {
          this.selectLayoutCard(e.target.closest('.uw-layout-card'));
        }
        // 휴지통 이동
        if (e.target.closest('.uw-gallery-trash')) {
          e.preventDefault();
          this.trashGallery(e.target.closest('.uw-gallery-trash'));
        }
        // 복구
        if (e.target.closest('.uw-gallery-restore')) {
          e.preventDefault();
          this.restoreGallery(e.target.closest('.uw-gallery-restore'));
        }
        // 영구 삭제
        if (e.target.closest('.uw-gallery-delete-permanent')) {
          e.preventDefault();
          this.deletePermanent(e.target.closest('.uw-gallery-delete-permanent'));
        }
        // 상태 변경
        if (e.target.closest('.uw-change-status')) {
          e.preventDefault();
          this.changeStatus(e.target.closest('.uw-change-status'));
        }
      });

      // 폼 저장
      const form = document.getElementById('uw-gallery-form');
      if (form) {
        form.addEventListener('submit', this.saveGallery.bind(this));
      }

      // 일괄 작업
      const bulkApplyBtn = document.getElementById('uw-bulk-apply');
      if (bulkApplyBtn) {
        bulkApplyBtn.addEventListener('click', this.bulkAction.bind(this));
      }

      // 전체 선택 체크박스
      const selectAllCb = document.getElementById('cb-select-all');
      if (selectAllCb) {
        selectAllCb.addEventListener('change', this.toggleSelectAll.bind(this));
      }

      // 레이아웃 선택 시 옵션 패널 동적 전환
      document.querySelectorAll('input[name="layout"]').forEach((radio) => {
        radio.addEventListener('change', this.toggleLayoutOptions.bind(this));
      });

      // Range 슬라이더 실시간 값 표시
      document.querySelectorAll('.uw-range-wrap input[type="range"]').forEach((range) => {
        range.addEventListener('input', this.updateRangeValue.bind(this));
      });

      // 드래그 앤 드롭 정렬
      this.initDragDrop();
    },

    // === 레이아웃 옵션 패널 전환 ===
    toggleLayoutOptions: function (e) {
      const selectedLayout = e.target.value;
      document.querySelectorAll('.uw-layout-options').forEach((panel) => {
        panel.style.display = panel.dataset.layout === selectedLayout ? '' : 'none';
      });
    },

    // === Range 슬라이더 값 업데이트 ===
    updateRangeValue: function (e) {
      const range = e.target;
      const valueSpan = range.closest('.uw-range-wrap').querySelector('.uw-range-value');
      if (valueSpan) {
        let value = range.value;
        const name = range.name;

        // 단위 처리
        if (name === 'slide_speed') {
          valueSpan.textContent = (value / 1000) + '초';
        } else if (name === 'overlay_opacity') {
          valueSpan.textContent = value + '%';
        } else {
          valueSpan.textContent = value + 'px';
        }
      }
    },

    // === 드래그 앤 드롭 ===
    initDragDrop: function () {
      const list = document.getElementById('uw-gallery-items');
      if (!list) return;

      list.addEventListener('dragstart', (e) => {
        if (!e.target.classList.contains('uw-gallery-item')) return;
        this.draggedItem = e.target;
        e.target.classList.add('is-dragging');
        e.dataTransfer.effectAllowed = 'move';
      });

      list.addEventListener('dragend', (e) => {
        if (!e.target.classList.contains('uw-gallery-item')) return;
        e.target.classList.remove('is-dragging');
        this.draggedItem = null;
        this.reindexItems();
      });

      list.addEventListener('dragover', (e) => {
        e.preventDefault();
        const afterElement = this.getDragAfterElement(list, e.clientY);
        if (afterElement == null) {
          list.appendChild(this.draggedItem);
        } else {
          list.insertBefore(this.draggedItem, afterElement);
        }
      });

      // 각 아이템에 draggable 속성 추가
      list.querySelectorAll('.uw-gallery-item').forEach(item => {
        item.setAttribute('draggable', 'true');
      });
    },

    getDragAfterElement: function (container, y) {
      const draggableElements = [...container.querySelectorAll('.uw-gallery-item:not(.is-dragging)')];
      return draggableElements.reduce((closest, child) => {
        const box = child.getBoundingClientRect();
        const offset = y - box.top - box.height / 2;
        if (offset < 0 && offset > closest.offset) {
          return { offset: offset, element: child };
        } else {
          return closest;
        }
      }, { offset: Number.NEGATIVE_INFINITY }).element;
    },

    reindexItems: function () {
      const items = document.querySelectorAll('#uw-gallery-items .uw-gallery-item');
      items.forEach((item, index) => {
        item.dataset.index = index;
        item.querySelectorAll('input[name]').forEach(input => {
          const name = input.getAttribute('name');
          if (name) {
            input.setAttribute('name', name.replace(/items\[\d+\]/, 'items[' + index + ']'));
          }
        });
      });
    },

    // === 레이아웃 카드 선택 ===
    selectLayoutCard: function (card) {
      document.querySelectorAll('.uw-layout-card').forEach(c => c.classList.remove('is-selected'));
      card.classList.add('is-selected');
      const radio = card.querySelector('input[type="radio"]');
      if (radio) radio.checked = true;
    },

    // === 미디어 라이브러리 ===
    openMediaLibrary: function (e) {
      e.preventDefault();
      const self = this;

      const frame = wp.media({
        title: uwGalleryAdmin.i18n.selectImages,
        button: { text: uwGalleryAdmin.i18n.addImages },
        multiple: 'add',
        library: { type: 'image' }
      });

      frame.on('select', function () {
        const attachments = frame.state().get('selection').toJSON();
        attachments.forEach(function (attachment) {
          self.addImageItem(attachment);
        });
      });

      frame.open();
    },

    addImageItem: function (attachment) {
      const index = this.itemIndex++;
      const thumbUrl = attachment.sizes && attachment.sizes.thumbnail
        ? attachment.sizes.thumbnail.url
        : attachment.url;

      const li = document.createElement('li');
      li.className = 'uw-gallery-item';
      li.dataset.index = index;
      li.setAttribute('draggable', 'true');

      li.innerHTML = `
        <input type="hidden" name="items[${index}][id]" value="${attachment.id}">
        <input type="hidden" name="items[${index}][thumb_id]" value="${attachment.id}">
        <input type="hidden" name="items[${index}][custom_thumb_id]" value="0" class="uw-item-custom-thumb-input">
        <input type="hidden" name="items[${index}][type]" value="image">
        <input type="hidden" name="items[${index}][video_url]" value="">
        <input type="hidden" name="items[${index}][title]" value="" class="uw-item-title-input">
        <input type="hidden" name="items[${index}][description]" value="" class="uw-item-desc-input">
        <input type="hidden" name="items[${index}][categories]" value="" class="uw-item-cats-input">

        <div class="uw-item-thumb">
          <img src="${thumbUrl}" alt="">
        </div>
        <div class="uw-item-actions">
          <button type="button" class="uw-item-edit" title="편집">✎</button>
          <button type="button" class="uw-item-remove" title="삭제">×</button>
        </div>
      `;

      document.getElementById('uw-gallery-items').appendChild(li);
    },

    // === 비디오 ===
    openVideoModal: function (e) {
      e.preventDefault();
      document.getElementById('uw-video-url').value = '';
      document.getElementById('uw-video-modal').style.display = 'flex';
    },

    closeVideoModal: function () {
      document.getElementById('uw-video-modal').style.display = 'none';
    },

    confirmVideo: function () {
      const url = document.getElementById('uw-video-url').value.trim();

      if (!url) {
        UWToast.warning('URL을 입력해주세요.');
        return;
      }

      const youtubeMatch = url.match(/(?:youtube\.com\/watch\?v=|youtu\.be\/)([^&?]+)/);
      const vimeoMatch = url.match(/vimeo\.com\/(\d+)/);

      if (!youtubeMatch && !vimeoMatch) {
        UWToast.warning('YouTube 또는 Vimeo URL만 지원됩니다.');
        return;
      }

      this.addVideoItem(url, youtubeMatch ? youtubeMatch[1] : null);
      this.closeVideoModal();
    },

    addVideoItem: function (url, youtubeId) {
      const index = this.itemIndex++;
      const thumbUrl = youtubeId
        ? `https://img.youtube.com/vi/${youtubeId}/mqdefault.jpg`
        : '';

      const li = document.createElement('li');
      li.className = 'uw-gallery-item';
      li.dataset.index = index;
      li.setAttribute('draggable', 'true');

      li.innerHTML = `
        <input type="hidden" name="items[${index}][id]" value="0">
        <input type="hidden" name="items[${index}][thumb_id]" value="0">
        <input type="hidden" name="items[${index}][custom_thumb_id]" value="0" class="uw-item-custom-thumb-input">
        <input type="hidden" name="items[${index}][type]" value="video">
        <input type="hidden" name="items[${index}][video_url]" value="${url}">
        <input type="hidden" name="items[${index}][title]" value="" class="uw-item-title-input">
        <input type="hidden" name="items[${index}][description]" value="" class="uw-item-desc-input">
        <input type="hidden" name="items[${index}][categories]" value="" class="uw-item-cats-input">

        <div class="uw-item-thumb">
          ${thumbUrl ? `<img src="${thumbUrl}" alt="">` : ''}
          <span class="uw-video-badge">▶</span>
        </div>
        <div class="uw-item-actions">
          <button type="button" class="uw-item-edit" title="편집">✎</button>
          <button type="button" class="uw-item-remove" title="삭제">×</button>
        </div>
      `;

      document.getElementById('uw-gallery-items').appendChild(li);
    },

    // === 아이템 제거 ===
    removeItem: function (item) {
      if (item) {
        item.remove();
        this.reindexItems();
      }
    },

    // === 아이템 편집 모달 ===
    editItem: function (item) {
      const index = item.dataset.index;
      const titleInput = item.querySelector('.uw-item-title-input');
      const descInput = item.querySelector('.uw-item-desc-input');
      const catsInput = item.querySelector('.uw-item-cats-input');
      const customThumbInput = item.querySelector('.uw-item-custom-thumb-input');
      const typeInput = item.querySelector('input[name*="[type]"]');
      const itemType = typeInput ? typeInput.value : 'image';

      document.getElementById('uw-edit-item-index').value = index;
      document.getElementById('uw-edit-item-type').value = itemType;
      document.getElementById('uw-edit-item-title').value = titleInput ? titleInput.value : '';
      document.getElementById('uw-edit-item-description').value = descInput ? descInput.value : '';
      document.getElementById('uw-edit-item-categories').value = catsInput ? catsInput.value : '';

      // 커스텀 썸네일 섹션 표시 (비디오만)
      const videoOnlySection = document.querySelector('.uw-video-only');
      if (videoOnlySection) {
        if (itemType === 'video') {
          videoOnlySection.style.display = 'block';
          const customThumbId = customThumbInput ? customThumbInput.value : '0';
          document.getElementById('uw-edit-item-custom-thumb-id').value = customThumbId;

          // 커스텀 썸네일 미리보기 업데이트
          this.updateCustomThumbPreview();
        } else {
          videoOnlySection.style.display = 'none';
        }
      }

      document.getElementById('uw-item-edit-modal').style.display = 'flex';
    },

    saveItemEdit: function () {
      const index = document.getElementById('uw-edit-item-index').value;
      const title = document.getElementById('uw-edit-item-title').value;
      const description = document.getElementById('uw-edit-item-description').value;
      const categories = document.getElementById('uw-edit-item-categories').value;
      const customThumbId = document.getElementById('uw-edit-item-custom-thumb-id').value;

      const item = document.querySelector(`.uw-gallery-item[data-index="${index}"]`);
      if (item) {
        const titleInput = item.querySelector('.uw-item-title-input');
        const descInput = item.querySelector('.uw-item-desc-input');
        const catsInput = item.querySelector('.uw-item-cats-input');
        const customThumbInput = item.querySelector('.uw-item-custom-thumb-input');

        if (titleInput) titleInput.value = title;
        if (descInput) descInput.value = description;
        if (catsInput) catsInput.value = categories;
        if (customThumbInput) customThumbInput.value = customThumbId;
      }

      this.closeItemEditModal();
    },

    closeItemEditModal: function () {
      document.getElementById('uw-item-edit-modal').style.display = 'none';
    },

    // === 갤러리 저장 ===
    saveGallery: function (e) {
      e.preventDefault();

      const form = document.getElementById('uw-gallery-form');
      const submitBtn = form.querySelector('button[type="submit"]');
      const formData = new FormData(form);
      formData.append('action', 'uw_gallery_save');

      submitBtn.disabled = true;
      submitBtn.textContent = '저장 중...';

      fetch(uwGalleryAdmin.ajaxUrl, {
        method: 'POST',
        body: formData
      })
        .then(response => response.json())
        .then(data => {
          if (data.success) {
            // 저장 성공 - 알림 한 번 표시 후 리다이렉트
            UWToast.success(data.data.message || '저장되었습니다.');
            if (data.data.redirect) {
              window.location.href = data.data.redirect;
            } else {
              // 리다이렉트 없으면 버튼 상태만 복원
              submitBtn.disabled = false;
              submitBtn.textContent = '저장됨 ✓';
              setTimeout(() => {
                submitBtn.textContent = '업데이트';
              }, 2000);
            }
          } else {
            UWToast.error(data.data.message || '저장에 실패했습니다.');
            submitBtn.disabled = false;
            submitBtn.textContent = form.querySelector('input[name="gallery_id"]').value ? '업데이트' : '발행';
          }
        })
        .catch(() => {
          UWToast.error('서버 오류가 발생했습니다.');
          submitBtn.disabled = false;
          submitBtn.textContent = form.querySelector('input[name="gallery_id"]').value ? '업데이트' : '발행';
        });
    },

    // === 갤러리 삭제 ===
    deleteGallery: function (link) {
      if (!confirm(uwGalleryAdmin.i18n.confirmDelete)) {
        return;
      }

      const galleryId = link.dataset.id;
      const formData = new FormData();
      formData.append('action', 'uw_gallery_delete');
      formData.append('nonce', uwGalleryAdmin.nonce);
      formData.append('id', galleryId);

      fetch(uwGalleryAdmin.ajaxUrl, {
        method: 'POST',
        body: formData
      })
        .then(response => response.json())
        .then(data => {
          if (data.success) {
            const row = document.querySelector(`tr[data-id="${galleryId}"]`);
            if (row) {
              row.style.transition = 'opacity 0.3s';
              row.style.opacity = '0';
              setTimeout(() => row.remove(), 300);
            } else {
              // 편집 페이지에서 삭제 시
              window.location.href = uwGalleryAdmin.ajaxUrl.replace('admin-ajax.php', 'admin.php?page=uw-gallery');
            }
          } else {
            UWToast.error(data.data.message || '삭제에 실패했습니다.');
          }
        })
        .catch(() => {
          UWToast.error('서버 오류가 발생했습니다.');
        });
    },

    // === 일괄 작업 ===
    bulkAction: function () {
      const action = document.getElementById('uw-bulk-action').value;
      if (!action) {
        UWToast.warning('작업을 선택해주세요.');
        return;
      }

      const checkboxes = document.querySelectorAll('.uw-gallery-checkbox:checked');
      if (checkboxes.length === 0) {
        UWToast.warning('갤러리를 선택해주세요.');
        return;
      }

      // 작업별 확인 메시지
      let confirmMsg = '';
      switch (action) {
        case 'trash':
          confirmMsg = uwGalleryAdmin.i18n.confirmTrash || '선택한 갤러리를 휴지통으로 이동하시겠습니까?';
          break;
        case 'restore':
          confirmMsg = uwGalleryAdmin.i18n.confirmRestore || '선택한 갤러리를 복구하시겠습니까?';
          break;
        case 'delete':
          confirmMsg = uwGalleryAdmin.i18n.confirmPermanentDelete || '선택한 갤러리를 영구 삭제하시겠습니까? 이 작업은 되돌릴 수 없습니다.';
          break;
        case 'private':
        case 'publish':
          confirmMsg = uwGalleryAdmin.i18n.confirmStatusChange || '상태를 변경하시겠습니까?';
          break;
        default:
          confirmMsg = '선택한 작업을 수행하시겠습니까?';
      }

      if (!confirm(confirmMsg)) {
        return;
      }

      const ids = Array.from(checkboxes).map(cb => cb.value);
      const formData = new FormData();
      formData.append('action', 'uw_gallery_bulk_action');
      formData.append('nonce', uwGalleryAdmin.nonce);
      formData.append('bulk_action', action);
      ids.forEach(id => formData.append('ids[]', id));

      fetch(uwGalleryAdmin.ajaxUrl, {
        method: 'POST',
        body: formData
      })
        .then(response => response.json())
        .then(data => {
          if (data.success) {
            UWToast.success(data.data.message);
            window.location.reload();
          } else {
            UWToast.error(data.data.message || '작업에 실패했습니다.');
          }
        })
        .catch(() => {
          UWToast.error('서버 오류가 발생했습니다.');
        });
    },

    // === 전체 선택 ===
    toggleSelectAll: function (e) {
      const checked = e.target.checked;
      document.querySelectorAll('.uw-gallery-checkbox').forEach(cb => {
        cb.checked = checked;
      });
    },

    // === 숏코드 복사 ===
    copyShortcode: function (btn) {
      const shortcode = btn.dataset.shortcode;

      navigator.clipboard.writeText(shortcode).then(() => {
        const originalText = btn.textContent;
        btn.textContent = '복사됨!';
        setTimeout(() => {
          btn.textContent = originalText;
        }, 1500);
      });
    },

    // === 커스텀 썸네일 업로드 ===
    uploadCustomThumbnail: function (e) {
      e.preventDefault();
      const self = this;

      if (!this.customThumbFrame) {
        this.customThumbFrame = wp.media({
          title: '커스텀 썸네일 선택',
          button: { text: '썸네일로 사용' },
          multiple: false,
          library: { type: 'image' }
        });

        this.customThumbFrame.on('select', function () {
          const attachment = self.customThumbFrame.state().get('selection').first().toJSON();
          document.getElementById('uw-edit-item-custom-thumb-id').value = attachment.id;
          self.updateCustomThumbPreview();
        });
      }

      this.customThumbFrame.open();
    },

    // === 커스텀 썸네일 미리보기 업데이트 ===
    updateCustomThumbPreview: function () {
      const customThumbId = document.getElementById('uw-edit-item-custom-thumb-id').value;
      const previewImg = document.getElementById('uw-custom-thumb-preview-img');
      const placeholder = document.getElementById('uw-custom-thumb-placeholder');
      const removeBtn = document.getElementById('uw-remove-custom-thumb');

      if (customThumbId && customThumbId !== '0') {
        // 워드프레스 REST API로 이미지 URL 가져오기
        fetch(`/wp-json/wp/v2/media/${customThumbId}`)
          .then(response => response.json())
          .then(data => {
            if (data.media_details && data.media_details.sizes && data.media_details.sizes.thumbnail) {
              previewImg.src = data.media_details.sizes.thumbnail.source_url;
            } else if (data.source_url) {
              previewImg.src = data.source_url;
            }
            previewImg.style.display = 'block';
            placeholder.style.display = 'none';
            removeBtn.style.display = 'inline-block';
          })
          .catch(() => {
            // API 실패 시 기본 상태로
            previewImg.style.display = 'none';
            placeholder.style.display = 'block';
            removeBtn.style.display = 'none';
          });
      } else {
        previewImg.style.display = 'none';
        previewImg.src = '';
        placeholder.style.display = 'block';
        removeBtn.style.display = 'none';
      }
    },

    // === 커스텀 썸네일 제거 ===
    removeCustomThumbnail: function (e) {
      e.preventDefault();
      document.getElementById('uw-edit-item-custom-thumb-id').value = '0';
      this.updateCustomThumbPreview();
    },

    // === 휴지통 이동 (복구 가능) ===
    trashGallery: function (link) {
      if (!confirm('이 갤러리를 휴지통으로 이동하시겠습니까?')) {
        return;
      }

      const galleryId = link.dataset.id;
      const formData = new FormData();
      formData.append('action', 'uw_gallery_trash');
      formData.append('nonce', uwGalleryAdmin.nonce);
      formData.append('id', galleryId);

      fetch(uwGalleryAdmin.ajaxUrl, {
        method: 'POST',
        body: formData
      })
        .then(response => response.json())
        .then(data => {
          if (data.success) {
            this.removeRowWithAnimation(galleryId);
          } else {
            UWToast.error(data.data.message || '휴지통 이동에 실패했습니다.');
          }
        })
        .catch(() => {
          UWToast.error('서버 오류가 발생했습니다.');
        });
    },

    // === 복구 ===
    restoreGallery: function (link) {
      if (!confirm('이 갤러리를 복구하시겠습니까?')) {
        return;
      }

      const galleryId = link.dataset.id;
      const formData = new FormData();
      formData.append('action', 'uw_gallery_restore');
      formData.append('nonce', uwGalleryAdmin.nonce);
      formData.append('id', galleryId);

      fetch(uwGalleryAdmin.ajaxUrl, {
        method: 'POST',
        body: formData
      })
        .then(response => response.json())
        .then(data => {
          if (data.success) {
            this.removeRowWithAnimation(galleryId);
          } else {
            UWToast.error(data.data.message || '복구에 실패했습니다.');
          }
        })
        .catch(() => {
          UWToast.error('서버 오류가 발생했습니다.');
        });
    },

    // === 영구 삭제 ===
    deletePermanent: function (link) {
      if (!confirm(uwGalleryAdmin.i18n.confirmPermanentDelete || '이 갤러리를 영구 삭제하시겠습니까? 이 작업은 되돌릴 수 없습니다.')) {
        return;
      }

      const galleryId = link.dataset.id;
      const formData = new FormData();
      formData.append('action', 'uw_gallery_delete');
      formData.append('nonce', uwGalleryAdmin.nonce);
      formData.append('id', galleryId);

      fetch(uwGalleryAdmin.ajaxUrl, {
        method: 'POST',
        body: formData
      })
        .then(response => response.json())
        .then(data => {
          if (data.success) {
            this.removeRowWithAnimation(galleryId);
          } else {
            UWToast.error(data.data.message || '삭제에 실패했습니다.');
          }
        })
        .catch(() => {
          UWToast.error('서버 오류가 발생했습니다.');
        });
    },

    // === 상태 변경 ===
    changeStatus: function (link) {
      const galleryId = link.dataset.id;
      const newStatus = link.dataset.status;
      const statusLabel = newStatus === 'publish' ? '공개' : '비공개';

      if (!confirm(`이 갤러리를 ${statusLabel} 상태로 변경하시겠습니까?`)) {
        return;
      }

      const formData = new FormData();
      formData.append('action', 'uw_gallery_change_status');
      formData.append('nonce', uwGalleryAdmin.nonce);
      formData.append('id', galleryId);
      formData.append('status', newStatus);

      fetch(uwGalleryAdmin.ajaxUrl, {
        method: 'POST',
        body: formData
      })
        .then(response => response.json())
        .then(data => {
          if (data.success) {
            // 현재 페이지 새로고침 (상태별 필터 적용 시)
            window.location.reload();
          } else {
            UWToast.error(data.data.message || '상태 변경에 실패했습니다.');
          }
        })
        .catch(() => {
          UWToast.error('서버 오류가 발생했습니다.');
        });
    },

    // === 헬퍼: 행 제거 애니메이션 ===
    removeRowWithAnimation: function (galleryId) {
      const row = document.querySelector(`tr[data-id="${galleryId}"]`);
      if (row) {
        row.style.transition = 'opacity 0.3s, background-color 0.3s';
        row.style.opacity = '0';
        row.style.backgroundColor = '#faafaa';
        setTimeout(() => {
          row.remove();
          this.checkEmptyTable();
        }, 300);
      }
    },

    // === 헬퍼: 빈 테이블 체크 ===
    checkEmptyTable: function () {
      const tbody = document.querySelector('.uw-gallery-list-table tbody');
      if (tbody && tbody.querySelectorAll('tr').length === 0) {
        // 빈 상태 메시지 표시 위해 새로고침
        window.location.reload();
      }
    }
  };

  // DOMContentLoaded
  document.addEventListener('DOMContentLoaded', function () {
    UWGalleryAdmin.init();
  });

})();
