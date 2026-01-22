<?php
/**
 * UW Gallery Video Trait
 *
 * 비디오 관련 공유 기능
 *
 * @package St-works
 * @since 2.1.0
 */

if (!defined('ABSPATH')) {
  exit;
}

trait UW_Gallery_Video_Trait
{
  /**
   * 비디오 URL에서 썸네일 추출
   * YouTube: 직접 URL 생성
   * Vimeo: oEmbed API로 실제 썸네일 추출 (1시간 캐싱)
   *
   * @param string $url 비디오 URL
   * @return string 썸네일 URL
   */
  protected function get_video_thumbnail($url)
  {
    // YouTube
    if (
      preg_match('/youtube\.com\/watch\?v=([^&]+)/', $url, $matches) ||
      preg_match('/youtu\.be\/([^?]+)/', $url, $matches)
    ) {
      return 'https://img.youtube.com/vi/' . $matches[1] . '/mqdefault.jpg';
    }

    // Vimeo - oEmbed API로 실제 썸네일 추출
    if (preg_match('/vimeo\.com\/(\d+)/', $url, $matches)) {
      $vimeo_id = $matches[1];
      $transient_key = 'uw_vimeo_thumb_' . $vimeo_id;

      // 캐시된 썸네일 확인
      $cached_thumb = get_transient($transient_key);
      if ($cached_thumb !== false) {
        return $cached_thumb;
      }

      // Vimeo oEmbed API 호출
      $oembed_url = 'https://vimeo.com/api/oembed.json?url=https://vimeo.com/' . $vimeo_id;
      $response = wp_remote_get($oembed_url, array('timeout' => 10));

      if (!is_wp_error($response) && wp_remote_retrieve_response_code($response) === 200) {
        $data = json_decode(wp_remote_retrieve_body($response), true);
        if (isset($data['thumbnail_url'])) {
          $thumbnail_url = $data['thumbnail_url'];
          // 1시간 캐싱
          set_transient($transient_key, $thumbnail_url, HOUR_IN_SECONDS);
          return $thumbnail_url;
        }
      }

      // API 실패 시 플레이스홀더
      return get_theme_file_uri('assets/images/video-placeholder.png');
    }

    // 기타 - 플레이스홀더
    return get_theme_file_uri('assets/images/video-placeholder.png');
  }

  /**
   * 비디오 Embed URL 생성
   *
   * @param string $url 비디오 URL
   * @return string Embed URL
   */
  protected function get_video_embed_url($url)
  {
    // YouTube
    if (
      preg_match('/youtube\.com\/watch\?v=([^&]+)/', $url, $matches) ||
      preg_match('/youtu\.be\/([^?]+)/', $url, $matches)
    ) {
      return 'https://www.youtube.com/embed/' . $matches[1] . '?autoplay=1';
    }

    // Vimeo
    if (preg_match('/vimeo\.com\/(\d+)/', $url, $matches)) {
      return 'https://player.vimeo.com/video/' . $matches[1] . '?autoplay=1';
    }

    return $url;
  }
}
