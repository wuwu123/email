<?php


namespace wu\Mail;


use wu\Mail\Config\Config;

class Mail
{
    private $config;
    /**
     * @var Message
     */
    private $message;

    private function __construct()
    {
    }

    public static function make(Config $config, Message $message)
    {
        $model = new self();
        $model->config = $config;
        $model->message = $message;
        $model->message->setFrom($config->getForm());
        return $model;
    }

    public function send()
    {
        try {
            $transport = SmtpTransport::newInstance($this->config);
            $mailer = new \Swift_Mailer($transport);
            return [$mailer->send($this->message->getMessageObj()), ''];
        } catch (\Throwable $e) {
            return [false, $e->getMessage()];
        }
    }
}