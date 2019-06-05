<?php


namespace wu\Mail;


class Message
{
    /**
     * @var \Swift_Message
     */
    private $messageObj;

    private function __construct()
    {
        $this->messageObj = new \Swift_Message();
    }

    public static function make()
    {
        return new self();
    }

    /**
     * 设置主题
     * @param string $subject
     * @return $this
     */
    public function setSubject(string $subject)
    {
        $this->messageObj->setSubject($subject);
        return $this;
    }

    public function setHtmlBody(string $body, $add = true)
    {
        $this->addBody($body, "text/html");
        return $this;
    }

    public function setBody(string $body, $type = null, $add = true)
    {
        $this->addBody($body, $type);
        return $this;
    }

    /**
     * 追加body
     * @param $body
     * @param $contentType
     */
    private function addBody($body, $contentType)
    {
        $message = $this->messageObj;
        $oldBody = $message->getBody();
        $charset = $message->getCharset();
        if (empty($oldBody)) {
            $parts = $message->getChildren();
            $partFound = false;
            foreach ($parts as $key => $part) {
                if (!($part instanceof \Swift_Mime_Attachment)) {
                    /* @var $part \Swift_Mime_MimePart */
                    if ($part->getContentType() == $contentType) {
                        $charset = $part->getCharset();
                        unset($parts[$key]);
                        $partFound = true;
                        break;
                    }
                }
            }
            if ($partFound) {
                reset($parts);
                $message->setChildren($parts);
                $message->addPart($body, $contentType, $charset);
            } else {
                $message->setBody($body, $contentType);
            }
        } else {
            $oldContentType = $message->getContentType();
            if ($oldContentType == $contentType) {
                $message->setBody($body, $contentType);
            } else {
                $message->setBody(null);
                $message->setContentType(null);
                $message->addPart($oldBody, $oldContentType, $charset);
                $message->addPart($body, $contentType, $charset);
            }
        }
    }


    /**
     * 设置附件
     * @param array $attachs [[file_path , file_type , file_name]]
     * @throws \Exception
     */
    public function setAttach(array $attachs)
    {
        foreach ($attachs as $attach) {
            $this->messageObj->attach(Attachment::make(...array_values($attach))->getAttachment());
        }
        return $this;
    }

    /**
     * @param string|array $to
     * @return $this
     */
    public function setTo($to)
    {
        $this->messageObj->setTo($to);
        return $this;
    }

    /**
     * 设置抄送
     */
    public function setCc($cc)
    {
        $this->messageObj->setCc($cc);
        return $this;

    }

    /**
     * 设置秘密抄送
     */
    public function setBcc($cc)
    {
        $this->messageObj->setBcc($cc);
        return $this;
    }

    /**
     * 设置回执
     * @param string $to
     * @return $this
     */
    public function setReadReceiptTo(string $to)
    {
        $this->messageObj->setReadReceiptTo($to);
        return $this;
    }

    public function setFrom($from)
    {
        $this->messageObj->setFrom($from);
        return $this;
    }

    public function getMessageObj()
    {
        return $this->messageObj;
    }

}