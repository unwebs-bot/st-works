<!DOCTYPE html>
<html <?php language_attributes(); ?>>

<head>
  <meta charset="<?php bloginfo('charset'); ?>">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">

  <title><?php wp_title('|', true, 'right'); ?><?php bloginfo('name'); ?></title>



  <meta name="description" content="크레딧커넥트(CreditConnect) - 스마트 틴팅 기술의 글로벌 리더. 창호, 자동차, 인테리어용 스마트 필름 솔루션 제공.">
  <meta name="keywords" content="스마트틴팅, 스마트필름, PDLC, 조광유리, 크레딧커넥트">
  <meta property="og:title" content="CreditConnect - Corporate Site Renewal">
  <meta property="og:description" content="에너지 절감과 새로운 공간을 창조하는 스마트 틴팅 기술의 글로벌 기업">
  <meta property="og:type" content="website">

  <?php wp_head(); ?>
</head>

<body <?php body_class(); ?>>

  <!-- 헤더 영역 -->
  <header class="uw-header" id="uwHeader">
    <?php
    get_template_part('template-parts/header/uw-container');
    get_template_part('template-parts/header/uw-dropdown');
    get_template_part('template-parts/header/uw-overlay');
    ?>

  </header>
  <!-- 헤더 끝 -->