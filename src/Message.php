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

    public function setHtmlBody(string $body)
    {
        $this->addBody($body, "text/html");
        return $this;
    }

    public function setBody(string $body, $type = null)
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
        $oldBody = $this->messageObj->getBody();
        $charset = $this->messageObj->getCharset();
        if (empty($oldBody)) {
            $parts = $this->messageObj->getChildren();
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
                $this->messageObj->setChildren($parts);
                $this->messageObj->addPart($body, $contentType, $charset);
            } else {
                $this->messageObj->setBody($body, $contentType);
            }
        } else {
            $oldContentType = $this->messageObj->getContentType();
            if ($oldContentType == $contentType) {
                $this->messageObj->setBody($body, $contentType);
            } else {
                $this->messageObj->setBody(null);
                $this->messageObj->setContentType(null);
                //指定文件的输出类型
                $this->messageObj->addPart($oldBody, $oldContentType, $charset);
                $this->messageObj->addPart($body, $contentType, $charset);
            }
        }
    }


    /**
     * 设置附件
     * @param array $attachs [[file_path , file_type , file_name]]
     * @return $this
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
     * @param array $to
     * @return $this
     */
    public function setReadReceiptTo(array $to)
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