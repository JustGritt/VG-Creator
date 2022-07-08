<?php

namespace App\Core;

use App\Model\Document;

class Uploader
{

    protected $fileName;
    protected $fileTmpName;
    protected $fileSize;
    protected $fileError;
    protected $fileType;
    protected $filePath;

    public function __construct($_FILE)
    {
        $this->fileName = $_FILE['fileToUpload']['name'];
        $this->fileTmpName = $_FILE['fileToUpload']['tmp_name'];
        $this->fileSize = $_FILE['fileToUpload']['size'];
        $this->fileError = $_FILE['fileToUpload']['error'];
        $this->fileType = $_FILE['fileToUpload']['type'];
        $this->filePath =  'uploads/' . $this->fileName;
    }

    public function getFilePath()
    {
        return $this->filePath;
    }

    public function setFilePath($path)
    {
        $this->filePath = $path;
    }
    public function getFileType()
    {
        return $this->fileType;
    }
    public function setFileType($type)
    {
        $this->fileType = $type;
    }

    public function getFileName()
    {
        return $this->fileName;
    }

    public function setFileName($fileName)
    {
        $this->fileName = $fileName;
    }

    public function upload() : bool {
        $fileName = $this->fileName;
        $fileTmpName = $this->fileTmpName;
        $fileSize = $this->fileSize;
        $fileError = $this->fileError;

        $fileExt = explode('.', $fileName);
        $fileActualExt = strtolower(end($fileExt));
        $this->setFileType($fileActualExt);

        $allowed = array('jpg', 'jpeg', 'png');

        if (in_array($fileActualExt, $allowed)) {
            if ($fileError === 0) {
                if ($fileSize < 500000) {
                    $this->setFileName(uniqid('', true) . "." . $fileActualExt);
                    $fileNameNew = $this->getFileName();
                    $fileDestination = 'uploads/' . $fileNameNew;
                    $this->setFilePath($fileDestination);
                    move_uploaded_file($fileTmpName, $fileDestination);
                    FlashMessage::setFlash("success", "File uploaded successfully");
                    $_FILES = [];
                    return true;
                } else {
                    FlashMessage::setFlash("errors", "File is too big");
                    return false;
                }
            } else {
                FlashMessage::setFlash("errors", "There was an error uploading your file");
                return false;
            }
        }else {
            FlashMessage::setFlash("errors", "You cannot upload files of this type {$fileActualExt}");
            return false;
        }
    }


    public function getUploadForm(){

        return [
            "config"=>[
                "method"=>"POST",
                "action"=>"",
                "submit"=>"submit",
                "value"=>"Upload Image",
                "name"=>"submit",
                "enctype"=>"multipart/form-data"
            ],
            'inputs'=>[
                "file"=>[
                    "type"=>"file",
                    "id"=>"fileToUpload",
                    "name"=>"fileToUpload",
                ],
                'csrf_token'=>[
                    "type"=>"hidden",
                    "class"=>"inputForm",
                    "value"=> Security::generateCsfrToken(),
                    "id"=>"csrf_token"
                ]
            ]
        ];
    }

}