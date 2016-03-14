<?php
/**
 * Created by PhpStorm.
 * User: John
 * Date: 3/12/2016
 * Time: 2:09 PM
 */

namespace comradepashka\gallery\models;

use Yii;
use yii\base\Model;

/**
 * old code
 * return preg_replace("#/*$#", "/", self::$_galleryRootPath . $this->path);
 *
 */
class Album extends Model
{
    /**
     * @var Gallery
     */
    public $gallery;
    public $path;

    public function getRootPath()
    {
        return $this->gallery->getRoot() . $this->path;
    }
    public function getWebRootPath()
    {
        return $this->gallery->getWebRoot() . $this->path;
    }
    public function getName()
    {
        return preg_replace("/.*\/(.+)\/$/", "\\1", $this->path);
    }
    public function create($name)
    {
        if (@mkdir($this->RootPath . $name)) return new Album(['path' => $this->path . $name . "/", 'gallery' => $this->gallery]);
        else {
            $this->addError("path", "Can not create album: $name");
            return $this;
        }
    }
    public function update($newname)
    {
        if (@rename($this->RootPath, dirname($this->RootPath) . "/" . $newname)) {
            $this->path = preg_replace("/.*\/(.+)\/$/", $newname, $this->path);
        }
        else {
            $this->addError("path", "Can not rename album: $this->Name");
        }
        return $this;
    }
    public function delete()
    {
        if (!@rmdir($this->RootPath)) {
            $this->addError("path", "Can not delete album: $this->Name");
        }
        return $this;
//        Yii::info('Deleting ' . self::imageRoot . $path, 'albums');
    }
    public function find($path)
    {
        if ($this->path == $path) return $this;
        foreach ($this->albums as $a) {
            if ($album = $a->find($path)) return $album;
        }
        return null;
    }
    public function getAlbums()
    {
        $albums = [];
        $h = opendir($this->RootPath);
        while (false !== ($entry = readdir($h))) {
            if ($entry != "." && $entry != "..") {
                if (is_dir($this->RootPath . $entry)) {
                    $albums[] = new Album(['path' => $this->path . $entry . "/", 'gallery' => $this->gallery]);
                }
            }
        }
        closedir($h);
        return $albums;
    }
    public function getImages()
    {
        $images = [];
        $h = opendir($this->RootPath);
        while (false !== ($entry = readdir($h))) {
            if ($entry != "." && $entry != "..") {
                if (is_file($this->RootPath . $entry) && preg_match($this->gallery->extensions, $entry) && !preg_match($this->gallery->versionRegexp, $entry)) {
                    $images[] = new Image(['path' => $this->WebRootPath . $entry]);
                }
            }
        }
        closedir($h);
        return $images;
    }
    public function getTree($level = 0)
    {
        $currentPath = $this->gallery->CurrentPath;
        $ret = [
            'text' => $this->getName(),
            'data' => $this->path,
            'state' => ['selected' => $currentPath == $this->path]
        ];
        foreach ($this->albums as $a) {
            $ret['nodes'][] = $a->getTree($level + 1);
        }
        return $ret;
    }
}