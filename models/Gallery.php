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
    public $name;
    public $module;
    private $_rootPath;
    private $_webPath;
    private $_placeholder;
    private $_extensions;
    private $_versions;
    private $_versionRegexp;

    public function setRootPath($path)
    {
        $this->_rootPath = yii::getAlias($path . $this->WebPath);
    }
    public function getRootPath()
    {
        return $this->_rootPath;
    }
    public function setWebPath($path)
    {
        $this->_webPath = $path;
    }
    public function getWebPath()
    {
        return $this->_webPath;
    }
    public function getWebRootPath()
    {
        return $this->_rootPath . $this->_webPath;
    }
    public function setPlaceholder($path)
    {
        $this->_placeholder = $this->WebPath . $path;
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
    public function getVersionRegexp()
    {
        return $this->_versionRegexp;
    }
}