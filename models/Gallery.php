<?php
/**
 * Created by PhpStorm.
 * User: John
 * Date: 3/13/2016
 * Time: 4:41 PM
 */

namespace comradepashka\gallery\models;

use Yii;
use yii\base\Model;

class Gallery extends Model
{
    public $module;
    public $rootAlbum;
    private $_currentPath;
    private $_root;
    private $_webRoot;
    private $_placeholder;
    private $_extensions;
    private $_versions;
    private $_versionRegexp;

/*
    public static function setGalleryConfig($config)
    {
        $this->setRoot($config['root']);
        $this->setWebRoot($config['webRoot']);
        $this->setGalleryPlaceholder($config['placeholder']);
        $this->setGalleryExtensions($config['extensions']);
        $this->setGalleryVersions($config['versions']);
    }
*/
    public function init(){
        parent::init();
        $this->_currentPath = '/';
        $this->rootAlbum = new Album(['path' => '/', 'gallery' => $this]);
    }

    public function setRoot($path)
    {
        $this->_root = yii::getAlias($path);
    }

    public function getRoot()
    {
        return $this->_root;
    }

    public function setWebRoot($path)
    {
        $this->_webRoot = $path;
    }

    public function getWebRoot()
    {
        return $this->_webRoot;
    }

    public function setPlaceholder($path)
    {
        $this->_placeholder = $this->getWebRoot() . $path;
    }

    public function getPlaceholder()
    {
        return $this->_placeholder;
    }

    public function setExtensions($extensions)
    {
        $this->_extensions = $extensions;
    }

    public function getExtensions()
    {
        return $this->_extensions;
    }

    public function setVersions($versions)
    {
        $this->_versions = $versions;
        $this->_versionRegexp = "/-(";
        foreach ($versions as $k => $v) {
            $this->_versionRegexp .= "$k|";
        }
        $this->_versionRegexp = preg_replace("/\|$/", ")\./i", $this->_versionRegexp);
    }

    public function getVersions()
    {
        return $this->_versions;
    }
    /**
     * @return mixed
     */
    public function getVersionRegexp()
    {
        return $this->_versionRegexp;
    }
    /**
     * @return mixed
     */
    public function getCurrentPath()
    {
        return $this->_currentPath;
    }
    /**
     * @param mixed $currentPath
     */
    public function setCurrentPath($currentPath)
    {
        if (is_dir($this->_root . $currentPath)) {
            $this->_currentPath = $currentPath;
        } else {
            $this->_currentPath = "SOME ERROR! cp was: $currentPath";
        }
    }

    public function getCurrentAlbum()
    {
        return $this->rootAlbum->find($this->_currentPath);
    }
}