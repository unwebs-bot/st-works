<?php
/**
 * 스팸방지 이미지 캡챠 생성
 */

if (session_status() === PHP_SESSION_NONE) {
  session_start();
}

// 랜덤 코드 생성 (숫자만)
$captcha_code = '';
for ($i = 0; $i < 6; $i++) {
  $captcha_code .= rand(0, 9);
}

// 세션에 저장
$_SESSION['uw_captcha_code'] = $captcha_code;

// 이미지 생성
$width = 150;
$height = 40;
$image = imagecreatetruecolor($width, $height);

// 색상 정의
$bg_color = imagecolorallocate($image, 255, 255, 255);
$text_color = imagecolorallocate($image, 50, 50, 50);
$line_color = imagecolorallocate($image, 180, 180, 180);
$noise_color = imagecolorallocate($image, 150, 150, 150);

// 배경 채우기
imagefilledrectangle($image, 0, 0, $width, $height, $bg_color);

// 테두리
imagerectangle($image, 0, 0, $width - 1, $height - 1, $line_color);

// 노이즈 라인 추가
for ($i = 0; $i < 5; $i++) {
  imageline(
    $image,
    rand(0, $width),
    rand(0, $height),
    rand(0, $width),
    rand(0, $height),
    $line_color
  );
}

// 노이즈 점 추가
for ($i = 0; $i < 100; $i++) {
  imagesetpixel($image, rand(0, $width), rand(0, $height), $noise_color);
}

// 텍스트 그리기
$font_size = 5; // 내장 폰트 사용
$x = 15;
for ($i = 0; $i < strlen($captcha_code); $i++) {
  $y = rand(10, 15);
  imagestring($image, $font_size, $x, $y, $captcha_code[$i], $text_color);
  $x += rand(18, 22);
}

// 헤더 설정 및 출력
header('Content-Type: image/png');
header('Cache-Control: no-cache, no-store, must-revalidate');
header('Pragma: no-cache');
header('Expires: 0');

imagepng($image);
imagedestroy($image);
exit;
