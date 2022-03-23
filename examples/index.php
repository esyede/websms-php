<?php

require dirname(__DIR__) . '/src/WebSMS.php';

use Esyede\WebSMS\WebSMS;

$token = 'YOUR_TOKEN'; // Token / api key anda

$sms = new WebSMS($token);

echo $sms->regular('081234567890', 'Isi pesan reguler'); die;
echo $sms->vip('081234567890', 'Isi pesan VIP'); die;
echo $sms->otp('081234567890', 'Kode OTP and adalah: 1234'); die;