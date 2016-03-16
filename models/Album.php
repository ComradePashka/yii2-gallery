<?php
/**
 * Created by PhpStorm.
 * User: John
 * Date: 3/12/2016
 * Time: 2:09 PM
 */

namespace comradepashka\gallery\models;

use comradepashka\gallery\Module;
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
    public $path;

    public function getParentPath()
    {
        return preg_replace("/[^\/]+\/$/", "", $this->path);
    }

    public function getWebRootPath()
    {
        return Module::$gallery->WebRootPath . $this->path;
    }

    public function getWebRoot()
    {
        return Module::$gallery->WebRoot . $this->path;
    }

    public function getName()
    {
        return preg_replace("/.*\/(.+)\/$/", "\\1", $this->path);
    }

    public function create($name)
    {
        if (@mkdir($this->WebRootPath . $name)) return new Album(['path' => $this->path . $name . "/"]);
        else {
            $this->addError("path", "Can not create album: " . $this->WebRootPath . $name);
            return $this;
        }
    }

    public function update($newname)
    {
        if (@rename($this->WebRootPath, dirname($this->WebRootPath) . "/" . $newname)) {
            $this->path = $this->ParentPath . $newname . "/";
        } else {
            $this->addError("path", "Can not rename album: $this->Name");
        }
        return $this;
    }

    public function delete()
    {
        if (!@rmdir($this->WebRootPath)) {
            $this->addError("path", "Can not delete album: $this->Name");
        }
        $this->path = $this->ParentPath;
        return $this;
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
        $h = opendir($this->WebRootPath);
        while (false !== ($entry = readdir($h))) {
            if ($entry != "." && $entry != "..") {
                if (is_dir($this->WebRootPath . $entry)) {
                    $albums[] = new Album(['path' => $this->path . $entry . "/"]);
                }
            }
        }
        closedir($h);
        return $albums;
    }

    public function getImages()
    {
        $images = [];
        $h = opendir($this->WebRootPath);
        while (false !== ($entry = readdir($h))) {
            if ($entry != "." && $entry != "..") {
                if (is_file($this->WebRootPath . $entry) && preg_match(Module::$gallery->extensions, $entry) && !preg_match(Module::$gallery->versionRegexp, $entry)) {
                    $path = $this->WebRoot . $entry;
                    $images[] = new Image(['webrootpath' => $path]);
                }
            }
        }
        closedir($h);
        return $images;
    }

    public function getTree($level = 0)
    {
        $currentPath = Module::$currentPath;
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