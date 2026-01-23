<?php
// ACF Group Fields from User 1
$infos = get_field('infos', 'user_1');
$sns = get_field('sns', 'user_1');

// Extract Infos
$telephone = $infos['telephone'] ?? '';
$fax = $infos['fax'] ?? '';
$email = $infos['email'] ?? 'contact@st-works.co.kr';
$address = $infos['address'] ?? '';
$company_name = $infos['company_name'] ?? '';
$ceo = $infos['ceo'] ?? '';
$business_registration_number = $infos['business_registration_number'] ?? '';
$corporate_registration_number = $infos['corporate_registration_number'] ?? '';

// Extract SNS
$facebook = $sns['facebook'] ?? '';
$instagram = $sns['instagram'] ?? '';
