<?php


namespace wu\Mail;


use wu\Mail\Config\Config;

class SmtpTransport
{
    private static $newInstance = null;

    public static function newInstance(Config $config, $new = false)
    {
        if ($new || !self::$newInstance) {
            self::$newInstance = new \Swift_SmtpTransport($config->getHost(), $config->getPort() , $config->getEncryption());
            self::$newInstance->setUsername($config->getUsername())
                ->setPassword($config->getPassword())
                ->setTimeout(5);
        }
        return self::$newInstance;
    }

}