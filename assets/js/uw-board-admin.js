/**
 * UW Board Admin JavaScript
 * 
 * @package St-works
 */

(function ($) {
    'use strict';

    // ==========================================================================
    // 초기화
    // ==========================================================================
    $(document).ready(function () {
        initSummernote();
        initSettingsForm();
        initBoardActions();
        initEditorForm();
        initMediaUploader();
    });

    // ==========================================================================
    // Summernote 에디터 초기화
    // ==========================================================================
    function initSummernote() {
        if ($('#uw-summernote').length) {
            $('#uw-summernote').summernote({
                height: 400,
                lang: 'ko-KR',
                toolbar: [
                    ['style', ['style']],
                    ['font', ['bold', 'italic', 'underline', 'clear']],
                    ['fontsize', ['fontsize']],
                    ['color', ['color']],
                    ['para', ['ul', 'ol', 'paragraph']],
                    ['table', ['table']],
                    ['insert', ['link', 'picture']],
                    ['view', ['fullscreen', 'codeview']]
                ],
                callbacks: {
                    onImageUpload: function (files) {
                        uploadImage(files[0], this);
                    }
                }
            });
        }
    }

    // 이미지 업로드
    function uploadImage(file, editor) {
        var formData = new FormData();
        formData.append('file', file);
        formData.append('action', 'uw_board_upload_image');
        formData.append('nonce', uwBoardAdmin.nonce);

        $.ajax({
            url: uwBoardAdmin.ajaxUrl,
            method: 'POST',
            data: formData,
            processData: false,
            contentType: false,
            success: function (response) {
                if (response.success) {
                    $(editor).summernote('insertImage', response.data.url);
                } else {
                    alert('이미지 업로드 실패: ' + response.data);
                }
            }
        });
    }

    // ==========================================================================
    // 게시판 설정 폼
    // ==========================================================================
    function initSettingsForm() {
        $('#uw-board-settings-form').on('submit', function (e) {
            e.preventDefault();

            var $form = $(this);
            var formData = $form.serialize();
            formData += '&action=uw_board_save_settings';
            formData += '&nonce=' + uwBoardAdmin.nonce;

            $.post(uwBoardAdmin.ajaxUrl, formData, function (response) {
                if (response.success) {
                    alert(response.data.message);
                    if (response.data.redirect) {
                        window.location.href = response.data.redirect;
                    }
                } else {
                    alert('오류: ' + response.data);
                }
            });
        });
    }

    // ==========================================================================
    // 게시판 액션 (복사, 삭제)
    // ==========================================================================
    function initBoardActions() {
        // 숏코드 복사
        $('.uw-copy-shortcode').on('click', function () {
            var shortcode = $(this).data('shortcode');
            navigator.clipboard.writeText(shortcode).then(function () {
                alert('숏코드가 복사되었습니다!');
            });
        });

        // 게시판 삭제
        $('.uw-delete-board').on('click', function () {
            if (!confirm('정말 삭제하시겠습니까? 게시판 설정만 삭제되며, 글은 유지됩니다.')) {
                return;
            }

            var slug = $(this).data('slug');

            $.post(uwBoardAdmin.ajaxUrl, {
                action: 'uw_board_delete_board',
                nonce: uwBoardAdmin.nonce,
                slug: slug
            }, function (response) {
                if (response.success) {
                    alert(response.data.message);
                    location.reload();
                } else {
                    alert('오류: ' + response.data);
                }
            });
        });

        // 체크박스 전체 선택/해제
        $('#cb-select-all').on('change', function () {
            var isChecked = $(this).is(':checked');
            $('.board-checkbox').prop('checked', isChecked);
        });

        // 일괄 동작 적용
        $('#doaction').on('click', function () {
            var action = $('#bulk-action-selector').val();
            var selectedBoards = [];

            $('.board-checkbox:checked').each(function () {
                selectedBoards.push($(this).val());
            });

            if (!action) {
                alert('일괄 동작을 선택해주세요.');
                return;
            }

            if (selectedBoards.length === 0) {
                alert('게시판을 선택해주세요.');
                return;
            }

            if (action === 'empty_posts') {
                if (!confirm('선택한 게시판의 모든 게시글을 삭제하시겠습니까?\n이 동작은 되돌릴 수 없습니다.')) {
                    return;
                }

                var formData = new FormData();
                formData.append('action', 'uw_board_bulk_empty_posts');
                formData.append('nonce', uwBoardAdmin.nonce);
                selectedBoards.forEach(function (slug) {
                    formData.append('board_slugs[]', slug);
                });

                $.ajax({
                    url: uwBoardAdmin.ajaxUrl,
                    type: 'POST',
                    data: formData,
                    processData: false,
                    contentType: false,
                    success: function (response) {
                        if (response.success) {
                            alert(response.data.message);
                            location.reload();
                        } else {
                            alert('오류: ' + (response.data || '알 수 없는 오류'));
                        }
                    },
                    error: function (xhr, status, error) {
                        console.log('Error:', xhr.responseText);
                        alert('AJAX 오류: ' + error);
                    }
                });
            } else if (action === 'delete_boards') {
                if (!confirm('선택한 게시판을 영구적으로 삭제하시겠습니까?\n게시판 설정과 모든 게시글이 삭제됩니다. 이 동작은 되돌릴 수 없습니다.')) {
                    return;
                }

                var formData2 = new FormData();
                formData2.append('action', 'uw_board_bulk_delete_boards');
                formData2.append('nonce', uwBoardAdmin.nonce);
                selectedBoards.forEach(function (slug) {
                    formData2.append('board_slugs[]', slug);
                });

                $.ajax({
                    url: uwBoardAdmin.ajaxUrl,
                    type: 'POST',
                    data: formData2,
                    processData: false,
                    contentType: false,
                    success: function (response) {
                        if (response.success) {
                            alert(response.data.message);
                            location.reload();
                        } else {
                            alert('오류: ' + (response.data || '알 수 없는 오류'));
                        }
                    },
                    error: function (xhr, status, error) {
                        console.log('Error:', xhr.responseText);
                        alert('AJAX 오류: ' + error);
                    }
                });
            }
        });
    }

    // ==========================================================================
    // 에디터 폼
    // ==========================================================================
    function initEditorForm() {
        $('#uw-board-editor-form').on('submit', function (e) {
            e.preventDefault();

            var $form = $(this);
            var formData = {
                action: 'uw_board_save_post',
                nonce: uwBoardAdmin.nonce,
                board_slug: $form.find('[name="board_slug"]').val(),
                post_id: $form.find('[name="post_id"]').val(),
                title: $form.find('[name="title"]').val(),
                content: $('#uw-summernote').summernote('code'),
                is_pinned: $form.find('[name="is_pinned"]').is(':checked') ? '1' : '',
                thumbnail_id: $form.find('[name="thumbnail_id"]').val(),
                attachments: []
            };

            // 첨부파일 ID 수집
            $form.find('[name="attachments[]"]').each(function () {
                if ($(this).val()) {
                    formData.attachments.push($(this).val());
                }
            });

            $.post(uwBoardAdmin.ajaxUrl, formData, function (response) {
                if (response.success) {
                    alert(response.data.message);
                    if (response.data.redirect) {
                        window.location.href = response.data.redirect;
                    }
                } else {
                    alert('오류: ' + response.data);
                }
            });
        });

        // 글 삭제
        $('.uw-delete-post').on('click', function () {
            if (!confirm('정말 삭제하시겠습니까?')) {
                return;
            }

            var postId = $(this).data('post-id');
            var boardSlug = $('.uw-board-manager').data('board-slug');

            $.post(uwBoardAdmin.ajaxUrl, {
                action: 'uw_board_delete_post',
                nonce: uwBoardAdmin.nonce,
                post_id: postId,
                board_slug: boardSlug
            }, function (response) {
                if (response.success) {
                    alert(response.data.message);
                    if (response.data.redirect) {
                        window.location.href = response.data.redirect;
                    }
                } else {
                    alert('오류: ' + response.data);
                }
            });
        });
    }

    // ==========================================================================
    // 미디어 업로더
    // ==========================================================================
    function initMediaUploader() {
        var mediaFrame;

        // 썸네일 선택
        $('.uw-select-thumbnail').on('click', function (e) {
            e.preventDefault();

            var $button = $(this);
            var $input = $button.siblings('input[name="thumbnail_id"]');
            var $name = $button.siblings('.uw-thumbnail-name');

            if (mediaFrame) {
                mediaFrame.open();
                return;
            }

            mediaFrame = wp.media({
                title: '대표 이미지 선택',
                button: { text: '선택' },
                multiple: false,
                library: { type: 'image' }
            });

            mediaFrame.on('select', function () {
                var attachment = mediaFrame.state().get('selection').first().toJSON();
                $input.val(attachment.id);
                $name.text('이미지 선택됨');
            });

            mediaFrame.open();
        });

        // 첨부파일 선택
        $('.uw-select-file').on('click', function (e) {
            e.preventDefault();

            var $button = $(this);
            var $slot = $button.closest('.uw-attachment-slot');
            var $input = $slot.find('input[type="hidden"]');
            var $name = $slot.find('.uw-file-name');

            var fileFrame = wp.media({
                title: '파일 선택',
                button: { text: '선택' },
                multiple: false
            });

            fileFrame.on('select', function () {
                var attachment = fileFrame.state().get('selection').first().toJSON();
                $input.val(attachment.id);
                $name.text(attachment.filename);

                // 삭제 버튼 추가
                if (!$slot.find('.uw-remove-file').length) {
                    $slot.append('<button type="button" class="button uw-remove-file">삭제</button>');
                }
            });

            fileFrame.open();
        });

        // 첨부파일 삭제
        $(document).on('click', '.uw-remove-file', function () {
            var $slot = $(this).closest('.uw-attachment-slot');
            $slot.find('input[type="hidden"]').val('');
            $slot.find('.uw-file-name').text('선택된 파일 없음');
            $(this).remove();
        });
    }

})(jQuery);
