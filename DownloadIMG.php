<?php

interface IDownloadIMG
{
    public function getUrlImages();
    public function saveImages();
}

class DownloadIMG implements IDownloadIMG{

    private $url;
    private $path;

    function __construct($url='https://mail.ru/', $path='D:/g/xxamp/htdocs/test/img/')
    {
        $this->url = $url;
        $this->path = $path;
    }

    public function getUrlImages(){
        $images = array();

        $data  = file_get_contents($this->url);
        preg_match_all('/(img|src)=("|\')[^"\'>]+/i', $data, $media);
        unset($data);
        $data = preg_replace('/(img|src)("|\'|="|=\')(.*)/i', "$3", $media[0]);

        foreach ($data as $url) {
            $info = pathinfo($url);
            if (isset($info['extension'])) {
                if (($info['extension'] == 'jpg') ||
                    ($info['extension'] == 'jpeg') ||
                    ($info['extension'] == 'gif') ||
                    ($info['extension'] == 'png'))
                    array_push($images, $url);
            }
        }
        return $images;
    }
    public function saveImages(){
        $images = self::getUrlImages();
        foreach ($images as $image){
            $info = pathinfo($image);
            $filename = $this->path.$info['filename'].".".$info['extension'];
            file_put_contents($filename, file_get_contents($image));
        }
    }
}