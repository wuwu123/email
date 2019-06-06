<?php
require_once __DIR__ . "/../vendor/autoload.php";

$config = \wu\Mail\Config\Config::make([
    "form" => ['' => "错误警告"],
    'host' => 'smtp.exmail.qq.com',
    'username' => 'j.@.cc',
    'password' => '',
    'port' => '465',
    'encryption' => 'SSL',
]);

$message = \wu\Mail\Message::make();
$message->setSubject(rand(1, 100))
    ->setTo('j.wu@.cc')
    ->setHtmlBody("<a href=''>sssss</a>")
    ->setHtmlBody("什么清空");

$mail = \wu\Mail\Mail::make($config, $message);
var_dump($mail->send());