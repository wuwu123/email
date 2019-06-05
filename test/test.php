<?php
require_once __DIR__ . "/../vendor/autoload.php";

$config = \wu\Mail\Config\Config::make([
    "form" => ['9' => "错误警告"],
    'host' => 'smtp.exmail.qq.com',
    'username' => '9',
    'password' => '9',
    'port' => '9',
    'encryption' => 'SSL',
]);

$message = \wu\Mail\Message::make();
$message->setSubject("99")
    ->setHtmlBody("<a href=''>sssss</a>")
    ->setTo('j.wu@knowyourself.cc')
    ->setBody("什么清空");

$mail = \wu\Mail\Mail::make($config, $message);
var_dump($mail->send());