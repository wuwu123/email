<?php


namespace wu\Mail;


class Attachment
{
    private $file;
    private $type;
    private $fileName = '';

    /**
     * @param $file
     * @param $type
     * @param string $fileName
     * @return Attachment
     */
    public static function make($file, $type, $fileName = '')
    {
        if (file_exists($file)) {
            throw new \Exception("无效的附件地址");
        }
        $model = new self();
        $model->file = $file;
        $model->type = $type;
        $model->fileName = $fileName;
        return $model;
    }

    /**
     * @return \Swift_Attachment
     */
    public function getAttachment()
    {
        $model = \Swift_Attachment::fromPath($this->file, $this->type);
        if ($this->fileName) {
            $model->setFilename($model);
        }
        return $model;
    }

}