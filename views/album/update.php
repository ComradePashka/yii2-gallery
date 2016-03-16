<?php
use comradepashka\gallery\Module;
?>
<div class="post-update">
    <?= $this->render('_form', ['gallery' => Module::$gallery->name, 'currentPath' => Module::$currentPath, 'name' => Module::$currentAlbum->Name]) ?>
</div>