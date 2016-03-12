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
    public $path;
    public $albums = [];
    public $images = [];

    private static $_galleryRootPath;
    private static $_galleryWebPath;
    private static $_placeholder;
    private static $_extensions;
    private static $_versions;
    private static $_versionRegexp;

    public function __construct($path = "/", $createLoaves = true)
    {
        $this->path = $path;
        if (empty($path)) {
            $this->addError("path", "Path is not set!");
            return;
        }
        if (!is_dir($this->getPath())) {
            $this->addError("path", $this->getPath() . " is not directory!");
            return;
        }
        $h = opendir($this->getPath());
        while (false !== ($entry = readdir($h))) {
            if ($entry != "." && $entry != "..") {
                if (is_dir($this->getPath() . $entry)) {
                    $this->albums[] = new Album($path . $entry . "/", false);
                }
                if ($createLoaves && is_file($this->getPath() . $entry) && preg_match(self::$_extensions, $entry) && !preg_match(self::$_versionRegexp, $entry)) {
                    $this->images[] = new Image($this->getPath() . $entry);
                }
            }
        }
        closedir($h);
    }

    public function getPath()
    {
        return self::$_galleryRootPath . $this->path;
    }
    public function getWebPath()
    {
        return self::$_galleryWebPath . $this->path;
    }
    public function getName()
    {
        return preg_replace("/.*\/(.+)\/$/", "\\1", $this->path);
    }

    public function getAlbumByPath($path)
    {
        if ($this->path == $path) return $this;

        foreach ($this->albums as $a) {
        }
        return null;
    }


    public function getTree($currentPath = "/", $level = 0)
    {
        $ret = [
            'text' => $this->getName(),
            'data' => $this->path,
            'state' => ['selected' => $currentPath == $this->path]
        ];
//        if ($this->albums) {
            foreach ($this->albums as $a) {
                $ret['nodes'][] = $a->getTree($currentPath, $level + 1);
            }
//        }
        return $ret;
    }

    public static function setGalleryConfig($config)
    {
        self::setGalleryRootPath($config['root']);
        self::setGalleryWebPath($config['webRoot']);
        self::setGalleryPlaceholder($config['placeholder']);
        self::setGalleryExtensions($config['extensions']);
        self::setGalleryVersions($config['versions']);
    }

    public static function setGalleryRootPath($path)
    {
        self::$_galleryRootPath = yii::getAlias($path);
    }
    public static function getGalleryRootPath()
    {
        return self::$_galleryRootPath;
    }
    public static function setGalleryWebPath($path)
    {
        self::$_galleryWebPath = $path;
    }
    public static function getGalleryWebPath()
    {
        return self::$_galleryWebPath;
    }
    public static function setGalleryPlaceholder($path)
    {
        self::$_placeholder = new Image(self::getGalleryWebPath() . $path);
    }
    public function getGalleryPlaceholder()
    {
        return self::$_placeholder;
    }
    public static function setGalleryExtensions($extensions)
    {
        self::$_extensions = $extensions;
    }
    public function getGalleryExtensions()
    {
        return self::$_extensions;
    }
    public static function setGalleryVersions($versions)
    {
        self::$_versions = $versions;
        self::$_versionRegexp = "/-(";
        foreach ($versions as $k => $v) {
            self::$_versionRegexp .= "$k|";
        }
        self::$_versionRegexp = preg_replace("/\|$/", ")\./i", self::$_versionRegexp);
    }
    public function getGalleryVersions()
    {
        return self::$_versions;
    }
}