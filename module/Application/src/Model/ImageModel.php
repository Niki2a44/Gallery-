<?php
namespace Application\Model;

class ImageModel{
    private $dir = './data/images/';
    public function getFiles()
    {
        $saveDir = $this->dir;
        if (!is_dir($saveDir)) {
            if (!mkdir($saveDir)) {
                throw new \Exception('Error while creating image directory: ' . error_get_last());
            }
        }
        $files = [];
        $openDir = opendir($saveDir);
        while (($file = readdir($openDir)) !== false) {

            if ($file == '.' || $file == '..') {
                continue;
            }
            $files[] = $file;
        }
        return $files;
    }
    public function GetPathByName($name)
    {
        $correction = ["/", "\\"];
        $name = str_replace($correction, "", $name);
        $name = $this->dir . $name;
        return $name;
    }
    public function getImage($filePath)
    {
        return file_get_contents($filePath);
    }


}